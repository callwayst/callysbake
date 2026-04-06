<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Voucher;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::with(['variant.product', 'voucher'])
            ->where('user_id', Auth::id())
            ->get()
            ->filter(fn($item) => $item->variant);

        // Voucher yang sudah dipakai di cart item manapun
        $usedVoucherIds = $cartItems->pluck('voucher_id')->filter()->values();

        $userVouchers = Voucher::where('user_id', Auth::id())
            ->where('is_active', true)
            ->whereNotIn('id', $usedVoucherIds) // ← exclude yang sudah dipakai
            ->where(function ($q) {
                $q->whereNull('expired_at')
                ->orWhere('expired_at', '>=', Carbon::today());
            })->get();

        return view('shop.cart.index', compact('cartItems', 'userVouchers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'variant_id' => 'required|exists:product_variants,id',
            'qty'        => 'required|integer|min:1'
        ]);

        $item = CartItem::where('user_id', Auth::id())
            ->where('variant_id', $request->variant_id)
            ->first();

        if ($item) {
            $item->increment('quantity', $request->qty);
        } else {
            CartItem::create([
                'user_id'    => Auth::id(),
                'variant_id' => $request->variant_id,
                'quantity'   => $request->qty
            ]);
        }

        return back()->with('success', 'Added to cart');
    }

    public function destroy(CartItem $cart)
    {
        if ($cart->user_id !== Auth::id()) abort(403);
        $cart->delete();
        return redirect()->route('cart.index')->with('success', 'Item berhasil dihapus dari keranjang.');
    }

    public function applyVoucher(Request $request)
    {
        $request->validate([
            'cart_item_id' => 'required|exists:cart_items,id',
            'voucher_code' => 'required|exists:vouchers,code',
        ]);

        $cartItem = CartItem::with('variant')
            ->where('id', $request->cart_item_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        // Pastikan voucher ini milik user yang login
        $voucher = Voucher::where('code', $request->voucher_code)
            ->where('user_id', Auth::id())
            ->where('is_active', true)
            ->where(function ($q) {
                $q->whereNull('expired_at')
                  ->orWhere('expired_at', '>=', now());
            })->first();

        if (!$voucher) {
            return back()->with('error', 'Voucher tidak valid atau bukan milik kamu.');
        }

        // Validasi min_purchase dari SUBTOTAL
        $subtotal = $cartItem->variant->price * $cartItem->quantity;
        if ($voucher->min_purchase > 0 && $subtotal < $voucher->min_purchase) {
            return back()->with('error',
                'Voucher ' . $voucher->code . ' butuh minimal belanja IDR ' .
                number_format($voucher->min_purchase, 0, ',', '.') .
                '. Subtotal item ini IDR ' . number_format($subtotal, 0, ',', '.') . '.'
            );
        }

        $cartItem->voucher_id = $voucher->id;
        $cartItem->save();

        return back()->with('success', 'Voucher ' . $voucher->code . ' berhasil dipakai!');
    }

    public function removeVoucher(Request $request)
    {
        $request->validate(['cart_item_id' => 'required|exists:cart_items,id']);

        $cartItem = CartItem::where('id', $request->cart_item_id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $cartItem->voucher_id = null;
        $cartItem->save();

        return back()->with('success', 'Voucher berhasil dilepas.');
    }
}