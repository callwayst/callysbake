<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Address;

class CheckoutController extends Controller
{
    /**
     * Hitung diskon dari SUBTOTAL (bukan per unit).
     * Menerima subtotal (price * qty), mengembalikan discount_amount dan final_subtotal.
     */
    private function applyVoucher(float $subtotal, $voucher): array
    {
        if (!$voucher) {
            return [
                'final_subtotal'  => $subtotal,
                'discount_amount' => 0,
            ];
        }

        $discountAmount = $voucher->calculateDiscount($subtotal);
        $discountAmount = min($discountAmount, $subtotal);
        $finalSubtotal  = max(0, $subtotal - $discountAmount);

        return [
            'final_subtotal'  => $finalSubtotal,
            'discount_amount' => $discountAmount,
        ];
    }

    public function index(Request $request)
    {
        $cartItems = CartItem::with(['variant.product', 'voucher'])
            ->where('user_id', Auth::id())
            ->whereIn('id', $request->selected_products ?? [])
            ->get()
            ->filter(fn($item) => $item->variant);

        if ($cartItems->isEmpty()) return back()->with('error', 'Pilih item terlebih dahulu');

        $checkoutItems = $cartItems->map(function ($item) {
            $originalPrice = $item->variant->price;
            $qty           = $item->quantity;
            $subtotal      = $originalPrice * $qty;
            $voucher       = $item->voucher;

            ['final_subtotal' => $finalSubtotal, 'discount_amount' => $discountAmount] =
                $this->applyVoucher($subtotal, $voucher);

            return [
                'id'               => $item->id,
                'name'             => $item->variant->product->name,
                'variant'          => $item->variant->name,
                'price'            => $originalPrice,
                'discounted_price' => $finalSubtotal / $qty,
                'discount_amount'  => $discountAmount,
                'quantity'         => $qty,
                'image'            => $item->variant->product->image,
                'voucher'          => $voucher?->code ?? null,
            ];
        })->values()->toArray();

        $totalProducts = collect($checkoutItems)->sum(fn($i) => $i['price'] * $i['quantity']);
        $totalDiscount = collect($checkoutItems)->sum(fn($i) => $i['discount_amount']);
        $shippingCost  = 0;
        $serviceFee    = 0;
        $totalPayable  = max(0, $totalProducts - $totalDiscount + $shippingCost + $serviceFee);

        $paymentMethods = ['cash', 'bank_transfer', 'ovo', 'gopay', 'qris', 'dana'];
        $addresses      = Address::where('user_id', Auth::id())->get();
        $defaultAddress = $addresses->firstWhere('is_default', true) ?? $addresses->first();

        session(['checkout_selected_products' => $request->selected_products ?? []]);

        return view('shop.checkout.index', compact(
            'checkoutItems', 'totalProducts', 'shippingCost', 'serviceFee',
            'totalDiscount', 'totalPayable', 'paymentMethods', 'addresses', 'defaultAddress'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'selected_products' => 'required|array',
            'payment_method'    => 'required|string',
        ]);

        $items = CartItem::with(['variant.product', 'voucher'])
            ->where('user_id', Auth::id())
            ->whereIn('id', $request->selected_products)
            ->get()
            ->filter(fn($item) => $item->variant);

        if ($items->isEmpty()) return back()->with('error', 'Cart kosong');

        $paymentMethod  = $request->payment_method;
        $addresses      = Auth::user()->addresses()->get();
        $defaultAddress = $addresses->firstWhere('is_default', true);
        $addressId      = session('checkout_address_id') ?? ($defaultAddress?->id ?? null);

        if (!$addressId) {
            return back()->with('error', 'Pilih alamat pengiriman terlebih dahulu.');
        }

        // Validasi stok SEBELUM transaksi dimulai
        foreach ($items as $item) {
            if ($item->variant->stock < $item->quantity) {
                return back()->with('error',
                    "Stok produk \"{$item->variant->product->name}\" tidak mencukupi. " .
                    "Tersisa {$item->variant->stock} pcs, kamu pesan {$item->quantity} pcs."
                );
            }
        }

        DB::transaction(function () use ($items, $paymentMethod, $addressId, &$order) {

            // Status: cash = pending (belum dibayar), selainnya = paid
            $orderStatus = $paymentMethod === 'cash' ? 'pending' : 'paid';

            $order                 = new Order();
            $order->user_id        = Auth::id();
            $order->status         = $orderStatus;
            $order->total_price    = 0.00;
            $order->final_price    = 0.00;
            $order->payment_method = $paymentMethod;
            $order->address_id     = $addressId;
            $order->save();

            $total         = 0;
            $totalDiscount = 0;

            foreach ($items as $item) {
                $originalPrice = $item->variant->price;
                $voucher       = $item->voucher;
                $qty           = $item->quantity;
                $subtotal      = $originalPrice * $qty;

                ['final_subtotal' => $finalSubtotal, 'discount_amount' => $discountAmount] =
                    $this->applyVoucher($subtotal, $voucher);

                $discountedPricePerUnit = $finalSubtotal / $qty;

                $total         += $finalSubtotal;
                $totalDiscount += $discountAmount;

                OrderItem::create([
                    'order_id'     => $order->id,
                    'variant_id'   => $item->variant_id,
                    'product_name' => $item->variant->product->name,
                    'variant_name' => $item->variant->name,
                    'price'        => $discountedPricePerUnit,
                    'qty'          => $qty,
                    'subtotal'     => $finalSubtotal,
                ]);

                // ✅ FIX: Kurangi stok variant setelah order item dibuat
                $item->variant->decrement('stock', $qty);
            }

            CartItem::whereIn('id', $items->pluck('id'))->delete();

            $order->total_price = $total + $totalDiscount; // harga sebelum diskon
            $order->final_price = $total;                  // harga setelah diskon
            $order->save();
        });

        if ($paymentMethod === 'cash') {
            return redirect()->route('orders.index')
                ->with('success', 'Selamat! Pemesanan kamu berhasil. Status order: Pending (Belum dibayar)');
        }

        return redirect()->route('orders.index')
            ->with('success', 'Selamat! Pemesanan dan pembayaran kamu berhasil.');
    }

    public function selectAddress(Request $request)
    {
        $request->validate([
            'selected_address' => 'required|exists:addresses,id',
        ]);

        session(['checkout_address_id' => $request->selected_address]);

        return back();
    }

    public function destroy(Address $address)
    {
        if ($address->user_id !== Auth::id()) abort(403);

        if ($address->orders()->exists()) {
            return redirect()->route('checkout.index', [
                'selected_products' => session('checkout_selected_products', []),
            ])->with('error', 'Alamat tidak bisa dihapus karena masih digunakan oleh pesanan.');
        }

        if (session('checkout_address_id') == $address->id) {
            session()->forget('checkout_address_id');
        }

        $address->delete();

        return redirect()->route('checkout.index', [
            'selected_products' => session('checkout_selected_products', []),
        ])->with('success', 'Alamat berhasil dihapus.');
    }

    public function setDefault(Address $address)
    {
        if ($address->user_id !== Auth::id()) abort(403);

        $address->setAsDefault();
        session(['checkout_address_id' => $address->id]);

        return redirect()->route('checkout.index', [
            'selected_products' => session('checkout_selected_products', []),
        ])->with('success', 'Alamat default diubah.');
    }
}