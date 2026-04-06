@extends('layouts.app')

@section('content')

{{-- TOAST SUCCESS --}}
@if(session('success'))
<div id="toast"
     class="fixed top-6 right-6 z-50 flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl"
     style="background:linear-gradient(135deg,#A65005,#592202); border:1px solid rgba(242,212,194,0.3); box-shadow:0 8px 32px rgba(89,34,2,0.45); transition:opacity 0.5s,transform 0.5s;">
    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" style="background:rgba(242,212,194,0.2);">
        <i class='bx bx-check text-[#F2D4C2] text-lg'></i>
    </div>
    <div>
        <p class="text-[#F2D4C2] font-bold text-sm">Berhasil!</p>
        <p class="text-[#D99C79] text-xs mt-0.5">{{ session('success') }}</p>
    </div>
    <button onclick="document.getElementById('toast').remove()" class="ml-2 text-[#D99C79] hover:text-white">
        <i class='bx bx-x text-lg'></i>
    </button>
</div>
<script>setTimeout(()=>{const t=document.getElementById('toast');if(t){t.style.opacity='0';t.style.transform='translateX(20px)';setTimeout(()=>t.remove(),500);}},3500);</script>
@endif

{{-- TOAST ERROR --}}
@if(session('error'))
<div id="toast-error"
     class="fixed top-6 right-6 z-50 flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl"
     style="background:linear-gradient(135deg,#800000,#260101); border:1px solid rgba(242,212,194,0.2); transition:opacity 0.5s,transform 0.5s;">
    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" style="background:rgba(242,212,194,0.15);">
        <i class='bx bx-error text-[#F2D4C2] text-lg'></i>
    </div>
    <div>
        <p class="text-[#F2D4C2] font-bold text-sm">Gagal!</p>
        <p class="text-[#D99C79] text-xs mt-0.5">{{ session('error') }}</p>
    </div>
    <button onclick="document.getElementById('toast-error').remove()" class="ml-2 text-[#D99C79] hover:text-white">
        <i class='bx bx-x text-lg'></i>
    </button>
</div>
<script>setTimeout(()=>{const t=document.getElementById('toast-error');if(t){t.style.opacity='0';t.style.transform='translateX(20px)';setTimeout(()=>t.remove(),500);}},4000);</script>
@endif

<div class="max-w-6xl mx-auto px-4 py-3 space-y-6">

    {{-- HEADER --}}
    <div class="flex justify-between items-center flex-wrap gap-2">
        <h1 class="text-3xl font-bold text-[#A65005] flex items-center gap-2">
            <i class='bx bx-cart text-3xl'></i> Your Cart
        </h1>
        <span class="bg-[#A65005] text-white px-3 py-1 rounded-full text-sm">
            {{ $cartItems->count() }} items
        </span>
    </div>

    {{-- CART FORM --}}
    <form action="{{ route('checkout.index') }}" method="GET" class="bg-[#800000] p-6 rounded-2xl shadow space-y-6">
        <div class="space-y-4">
            @foreach($cartItems as $item)
            @php
                $appliedVoucher = $item->voucher;
                $originalPrice  = $item->variant->price;
                $qty            = $item->quantity;
                $subtotal       = $originalPrice * $qty;
                // Diskon dihitung dari SUBTOTAL
                $discountAmount = $appliedVoucher ? $appliedVoucher->calculateDiscount($subtotal) : 0;
                $discountAmount = min($discountAmount, $subtotal); // jangan melebihi subtotal
                $finalSubtotal  = max(0, $subtotal - $discountAmount);
            @endphp
            <div class="cart-item bg-white rounded-2xl p-4 shadow-sm transition-all duration-300"
                data-id="{{ $item->id }}"
                data-price="{{ $originalPrice }}"
                data-voucher-type="{{ $appliedVoucher?->type ?? '' }}"
                data-voucher-value="{{ $appliedVoucher?->value ?? 0 }}"
                data-voucher-max="{{ $appliedVoucher?->max_discount ?? 0 }}"
                data-min-purchase="{{ $appliedVoucher?->min_purchase ?? 0 }}">

                {{-- TOP ROW --}}
                <div class="flex items-center gap-4">
                    <input type="checkbox" name="selected_products[]" value="{{ $item->id }}"
                        class="item-check w-5 h-5 flex-shrink-0">
                    <img src="{{ asset('storage/'.$item->variant->product->image) }}"
                        class="w-24 h-24 object-cover rounded-lg flex-shrink-0">

                    {{-- Product Info --}}
                    <div class="flex-1 flex flex-col justify-center gap-1 min-w-0">
                        <p class="font-semibold text-gray-800 truncate">{{ $item->variant->product->name }}</p>
                        <p class="text-sm text-gray-500">{{ $item->variant->name }}</p>
                        <p class="text-xs text-gray-500">IDR {{ number_format($originalPrice,0,',','.') }} / item</p>
                    </div>

                    {{-- Desktop: Qty & Subtotal & Delete --}}
                    <div class="hidden sm:flex items-center gap-3 flex-shrink-0">
                        <div class="flex items-center gap-2">
                            <button type="button" class="qty-btn decrement bg-gray-200 w-7 h-7 rounded font-bold">-</button>
                            <input type="number" class="qty-input w-14 text-center border rounded" value="{{ $qty }}" min="1">
                            <button type="button" class="qty-btn increment bg-gray-200 w-7 h-7 rounded font-bold">+</button>
                        </div>
                        <div class="text-center min-w-[120px]">
                            <div class="subtotal-original text-xs text-gray-400 line-through hidden"></div>
                            <div class="subtotal font-semibold text-gray-800">IDR 0</div>
                        </div>
                        <form action="{{ route('cart.destroy', $item->id) }}" method="POST"
                            onsubmit="return confirm('Hapus item ini dari keranjang?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-8 h-8 flex items-center justify-center rounded-lg text-red-400 hover:text-white hover:bg-red-500 transition-all">
                                <i class='bx bx-trash text-base'></i>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Mobile: Qty & Subtotal & Delete --}}
                <div class="sm:hidden flex items-center justify-between mt-3 pl-9">
                    <div class="flex items-center gap-2">
                        <button type="button" class="qty-btn decrement bg-gray-200 w-7 h-7 rounded text-sm font-bold">-</button>
                        <input type="number" class="qty-input w-12 text-center border rounded text-sm" value="{{ $qty }}" min="1">
                        <button type="button" class="qty-btn increment bg-gray-200 w-7 h-7 rounded text-sm font-bold">+</button>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="text-right">
                            <div class="subtotal-original text-xs text-gray-400 line-through hidden"></div>
                            <div class="subtotal font-semibold text-gray-800 text-sm">IDR 0</div>
                        </div>
                        <form action="{{ route('cart.destroy', $item->id) }}" method="POST"
                              onsubmit="return confirm('Hapus item ini dari keranjang?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="w-7 h-7 flex items-center justify-center rounded-lg text-red-400 hover:text-white hover:bg-red-500 transition-all">
                                <i class='bx bx-trash text-sm'></i>
                            </button>
                        </form>
                    </div>
                </div>

                {{-- VOUCHER SECTION --}}
                <div class="mt-3">
                    @if($appliedVoucher)
                        <div class="flex items-center gap-2 bg-green-50 border border-green-200 rounded-xl px-3 py-2">
                            <i class='bx bx-purchase-tag text-green-600 flex-shrink-0'></i>
                            <div class="flex-1 min-w-0">
                                <span class="text-green-700 font-semibold text-sm">{{ $appliedVoucher->code }}</span>
                                <span class="text-green-600 text-xs ml-2">
                                    Hemat IDR {{ number_format($discountAmount,0,',','.') }}
                                    @if($appliedVoucher->type === 'percent')
                                        ({{ $appliedVoucher->value }}%@if($appliedVoucher->max_discount), maks. IDR {{ number_format($appliedVoucher->max_discount,0,',','.') }}@endif)
                                    @endif
                                </span>
                            </div>
                            {{-- FIX: gunakan route yang benar dengan method POST --}}
                            <form action="{{ route('cart.removeVoucher') }}" method="POST" class="flex-shrink-0">
                                @csrf
                                <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
                                <button type="submit" class="text-xs text-red-400 hover:text-red-600 font-semibold flex items-center gap-1 transition-colors">
                                    <i class='bx bx-x'></i> Lepas
                                </button>
                            </form>
                        </div>
                    @else
                        @if($userVouchers->isNotEmpty())
                        <form action="{{ route('cart.applyVoucher') }}" method="POST" class="flex gap-2">
                            @csrf
                            <input type="hidden" name="cart_item_id" value="{{ $item->id }}">
                            <select name="voucher_code"
                                    class="flex-1 border border-gray-200 rounded-xl px-3 py-2 text-sm text-gray-700 focus:outline-none focus:border-[#A65005] bg-gray-50">
                                <option value="">-- Pilih Voucher --</option>
                                @foreach($userVouchers as $v)
                                <option value="{{ $v->code }}">
                                    {{ $v->code }}
                                    — {{ $v->type === 'percent'
                                        ? $v->value.'% off'.($v->max_discount ? ' (maks. IDR '.number_format($v->max_discount,0,',','.').')' : '')
                                        : 'IDR '.number_format($v->value,0,',','.').' off' }}
                                    @if($v->min_purchase > 0) | min. IDR {{ number_format($v->min_purchase,0,',','.') }} @endif
                                </option>
                                @endforeach
                            </select>
                            <button type="submit"
                                    class="bg-[#800000] text-white px-4 py-2 rounded-xl text-xs font-semibold hover:bg-[#600000] whitespace-nowrap">
                                Pakai
                            </button>
                        </form>
                        @else
                        <p class="text-xs text-gray-400 italic">
                            Belum punya voucher.
                            <a href="{{ route('user.vouchers.index') }}" class="text-[#A65005] underline">Klaim voucher dulu</a>
                        </p>
                        @endif
                    @endif
                </div>

            </div>
            @endforeach

            {{-- SELECT ALL + TOTAL + CHECKOUT --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mt-4 bg-white p-4 rounded-2xl">
                <label class="flex items-center gap-2 text-black font-semibold">
                    <input type="checkbox" id="select-all" class="w-5 h-5"> Select All
                </label>
                <div class="text-center flex-1">
                    <div id="total-original-wrapper" class="text-xs text-gray-400 hidden">
                        Harga asli: <span id="total-original" class="line-through"></span>
                    </div>
                    <div id="total-discount-wrapper" class="text-xs text-green-600 font-semibold hidden">
                        Hemat: <span id="total-discount"></span>
                    </div>
                    <div class="font-bold text-lg text-black">Total: <span id="total">IDR 0</span></div>
                </div>
                <button type="submit" class="bg-[#592202] text-white px-6 py-2 rounded-xl hover:bg-[#3d1601]">
                    Checkout
                </button>
            </div>
        </div>
    </form>
</div>

<style>
.qty-input::-webkit-outer-spin-button,
.qty-input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
.qty-input { -moz-appearance: textfield; }
</style>

<script>
const fmt = n => "IDR " + Math.round(n).toLocaleString('id-ID');

/**
 * Hitung diskon dari SUBTOTAL (price * qty), bukan per unit.
 * Konsisten dengan logika di PHP controller dan Voucher model.
 */
function calcDiscount(subtotal, type, value, maxDiscount, minPurchase) {
    if (!type || subtotal <= 0) return 0;
    if (minPurchase > 0 && subtotal < minPurchase) return 0;

    let disc = type === 'percent'
        ? subtotal * (value / 100)
        : value;

    if (type === 'percent' && maxDiscount > 0) {
        disc = Math.min(disc, maxDiscount);
    }

    // Diskon tidak boleh melebihi subtotal
    return Math.min(disc, subtotal);
}

function calculate() {
    let totalOriginal = 0, totalDiscount = 0, totalFinal = 0, hasDiscount = false;

    document.querySelectorAll('.cart-item').forEach(card => {
        const checked     = card.querySelector('.item-check').checked;
        const price       = parseFloat(card.dataset.price) || 0;
        const qty         = parseInt(card.querySelector('.qty-input')?.value) || 0;
        const vType       = card.dataset.voucherType;
        const vValue      = parseFloat(card.dataset.voucherValue) || 0;
        const vMax        = parseFloat(card.dataset.voucherMax) || 0;
        const minPurchase = parseFloat(card.dataset.minPurchase) || 0;

        const subtotal = price * qty;
        // FIX: pass subtotal ke calcDiscount, bukan price
        const discount = calcDiscount(subtotal, vType, vValue, vMax, minPurchase);
        const finalSub = subtotal - discount;

        const origEl = card.querySelector('.subtotal-original');
        const subEl  = card.querySelector('.subtotal');

        if (checked) {
            if (discount > 0) {
                origEl?.classList.remove('hidden');
                if (origEl) origEl.innerText = fmt(subtotal);
                if (subEl)  { subEl.innerText = fmt(finalSub); subEl.className = 'subtotal font-semibold text-green-700'; }
            } else {
                origEl?.classList.add('hidden');
                if (subEl) { subEl.innerText = fmt(subtotal); subEl.className = 'subtotal font-semibold text-gray-800'; }
            }
            totalOriginal += subtotal;
            totalDiscount += discount;
            totalFinal    += finalSub;
            if (discount > 0) hasDiscount = true;
        } else {
            origEl?.classList.add('hidden');
            if (subEl) { subEl.innerText = fmt(0); subEl.className = 'subtotal font-semibold text-gray-800'; }
        }
    });

    document.getElementById('total').innerText = fmt(totalFinal);
    const origWrap = document.getElementById('total-original-wrapper');
    const discWrap = document.getElementById('total-discount-wrapper');
    if (hasDiscount) {
        origWrap.classList.remove('hidden');
        discWrap.classList.remove('hidden');
        document.getElementById('total-original').innerText = fmt(totalOriginal);
        document.getElementById('total-discount').innerText  = fmt(totalDiscount);
    } else {
        origWrap.classList.add('hidden');
        discWrap.classList.add('hidden');
    }
}

document.querySelectorAll('.qty-btn').forEach(btn => {
    btn.onclick = function () {
        const input = btn.closest('.cart-item').querySelector('.qty-input');
        let val = parseInt(input.value);
        if (btn.classList.contains('increment')) val++;
        else if (btn.classList.contains('decrement') && val > 1) val--;
        input.value = val;
        calculate();
    };
});

document.querySelectorAll('.qty-input').forEach(i => i.onchange = calculate);
document.querySelectorAll('.item-check').forEach(cb => cb.onchange = calculate);
document.getElementById('select-all').onchange = function() {
    document.querySelectorAll('.item-check').forEach(cb => cb.checked = this.checked);
    calculate();
};

calculate();
</script>

@endsection