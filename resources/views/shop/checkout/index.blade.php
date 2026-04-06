@extends('layouts.app')

@section('content')

{{-- TOAST ALERT --}}
@if(session('success'))
<div id="toast"
     class="fixed top-6 right-6 z-50 flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl text-white font-semibold text-sm"
     style="background:linear-gradient(135deg,#A65005,#592202); border:1px solid rgba(242,212,194,0.3); box-shadow:0 8px 32px rgba(89,34,2,0.45); transition: opacity 0.5s, transform 0.5s;">
    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" style="background:rgba(242,212,194,0.2);">
        <i class='bx bx-check text-[#F2D4C2] text-lg'></i>
    </div>
    <div>
        <p class="text-[#F2D4C2] font-bold text-sm leading-tight">Berhasil!</p>
        <p class="text-[#D99C79] text-xs font-normal mt-0.5">{{ session('success') }}</p>
    </div>
    <button onclick="document.getElementById('toast').remove()" class="ml-2 text-[#D99C79] hover:text-white transition-colors">
        <i class='bx bx-x text-lg'></i>
    </button>
</div>
<script>setTimeout(()=>{const t=document.getElementById('toast');if(t){t.style.opacity='0';t.style.transform='translateX(20px)';setTimeout(()=>t.remove(),500);}},3500);</script>
@endif

@if($errors->any())
<div id="error-toast"
     class="fixed top-6 right-6 z-50 flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl"
     style="background:linear-gradient(135deg,#800000,#260101); border:1px solid rgba(242,212,194,0.2); transition: opacity 0.5s, transform 0.5s;">
    <div class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0" style="background:rgba(242,212,194,0.15);">
        <i class='bx bx-error text-[#F2D4C2] text-lg'></i>
    </div>
    <div>
        <p class="text-[#F2D4C2] font-bold text-sm leading-tight">Oops!</p>
        @foreach($errors->all() as $error)
        <p class="text-[#D99C79] text-xs font-normal mt-0.5">{{ $error }}</p>
        @endforeach
    </div>
    <button onclick="document.getElementById('error-toast').remove()" class="ml-2 text-[#D99C79] hover:text-white transition-colors">
        <i class='bx bx-x text-lg'></i>
    </button>
</div>
<script>setTimeout(()=>{const t=document.getElementById('error-toast');if(t){t.style.opacity='0';t.style.transform='translateX(20px)';setTimeout(()=>t.remove(),500);}},5000);</script>
@endif

<form id="form-set-default" method="POST" style="display:none;">
    @csrf
    @method('PATCH')
</form>
<form id="form-delete-address" method="POST" style="display:none;">
    @csrf
    @method('DELETE')
</form>

<div class="min-h-screen px-5 py-8 pb-16"
     style="background: radial-gradient(ellipse at 10% 20%, rgba(166,80,5,0.2) 0%, transparent 50%), radial-gradient(ellipse at 90% 80%, rgba(38,1,1,0.5) 0%, transparent 50%), #800000;"
     x-data="{ showAddressModal: false, showAddForm: false, paymentMethod: '{{ old('payment_method', $paymentMethods[0] ?? '') }}' }">

    {{-- MODAL ALAMAT --}}
    <div x-show="showAddressModal" x-transition.opacity style="display:none;"
         class="fixed inset-0 z-50 flex items-center justify-center p-5 backdrop-blur-sm"
         style="background:rgba(38,1,1,0.72);">
        <div @click.away="showAddressModal = false"
             class="bg-white rounded-3xl w-full max-w-lg max-h-[90vh] overflow-y-auto shadow-2xl">

            <div class="flex items-center justify-between px-6 pt-6 pb-4 border-b border-[#F2D4C2]">
                <h3 class="text-sm font-black text-[#800000] uppercase tracking-widest flex items-center gap-2">
                    <i class='bx bxs-map'></i> Alamat Pengiriman
                </h3>
                <button @click="showAddressModal = false" type="button" class="text-gray-400 hover:text-gray-600 transition-colors">
                    <i class='bx bx-x text-2xl'></i>
                </button>
            </div>

            <div class="p-6 space-y-4">
                <form action="{{ route('checkout.select-address') }}" method="POST" id="selectAddressForm">
                    @csrf
                    <div class="space-y-2 max-h-72 overflow-y-auto pr-1">
                        @forelse($addresses as $address)
                        <div class="flex items-start gap-3 p-3.5 border-2 rounded-2xl transition-all
                                    {{ (session('checkout_address_id') == $address->id || ($address->is_default && !session('checkout_address_id'))) ? 'border-[#800000] bg-red-50' : 'border-gray-200 hover:border-[#D99C79]' }}">
                            <input type="radio" name="selected_address" value="{{ $address->id }}"
                                   class="mt-1 accent-[#800000] flex-shrink-0 cursor-pointer"
                                   {{ (session('checkout_address_id') == $address->id || ($address->is_default && !session('checkout_address_id'))) ? 'checked' : '' }}>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap mb-0.5">
                                    <p class="font-bold text-sm text-gray-800">{{ $address->receiver_name }}</p>
                                    @if($address->is_default)
                                    <span class="text-[10px] bg-red-100 text-[#800000] px-2 py-0.5 rounded-full font-bold">Default</span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500 leading-relaxed">{{ $address->full_address }}</p>
                                <p class="text-xs text-gray-500">{{ $address->phone }}</p>
                                <div class="flex items-center gap-3 mt-2">
                                    @if(!$address->is_default)
                                    <button type="button"
                                            onclick="setDefaultAddress('{{ route('addresses.setDefault', $address->id) }}')"
                                            class="text-[11px] font-semibold transition-colors flex items-center gap-1"
                                            style="color:#A65005;"
                                            onmouseover="this.style.color='#592202'"
                                            onmouseout="this.style.color='#A65005'">
                                        <i class='bx bx-star'></i> Jadikan Default
                                    </button>
                                    <span class="text-gray-200">|</span>
                                    @endif
                                    <button type="button"
                                            onclick="deleteAddress('{{ route('addresses.destroy', $address->id) }}')"
                                            class="text-[11px] font-semibold text-red-400 hover:text-red-600 transition-colors flex items-center gap-1">
                                        <i class='bx bx-trash'></i> Hapus
                                    </button>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-8 text-gray-400">
                            <i class='bx bx-map text-4xl'></i>
                            <p class="text-sm mt-2 font-semibold">Belum ada alamat tersimpan</p>
                            <p class="text-xs mt-1 text-gray-400">Tambahkan alamat baru di bawah</p>
                        </div>
                        @endforelse
                    </div>

                    @if($addresses->count() > 0)
                    <button type="submit"
                            class="w-full mt-4 py-3 rounded-2xl font-bold text-sm flex items-center justify-center gap-2 transition-all hover:-translate-y-0.5 shadow-lg"
                            style="background:linear-gradient(135deg,#800000,#260101); color:#F2D4C2;">
                        <i class='bx bx-check'></i> Gunakan Alamat Ini
                    </button>
                    @endif
                </form>

                <div class="flex items-center gap-3">
                    <div class="flex-1 h-px bg-[#F2D4C2]"></div>
                    <span class="text-xs text-gray-400 font-semibold">atau</span>
                    <div class="flex-1 h-px bg-[#F2D4C2]"></div>
                </div>

                <button @click="showAddForm = !showAddForm" type="button"
                        class="w-full py-2.5 rounded-2xl font-bold text-sm border-2 flex items-center justify-center gap-2 transition-all"
                        style="border-color:#D99C79; color:#592202;"
                        :class="showAddForm ? 'bg-[#FFF5EE]' : 'bg-white hover:bg-[#FFF5EE]'">
                    <i class='bx' :class="showAddForm ? 'bx-minus' : 'bx-plus'"></i>
                    <span x-text="showAddForm ? 'Batal' : 'Tambah Alamat Baru'"></span>
                </button>

                <div x-show="showAddForm" x-transition style="display:none;">
                    <form action="{{ route('addresses.store') }}" method="POST"
                          class="space-y-3 bg-[#FFF5EE] rounded-2xl p-4 border border-[#F2D4C2]">
                        @csrf
                        <p class="text-xs font-black text-[#800000] uppercase tracking-widest">Alamat Baru</p>
                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Nama Penerima *</label>
                            <input type="text" name="receiver_name" placeholder="Nama lengkap penerima"
                                   value="{{ old('receiver_name') }}"
                                   class="w-full border-2 border-[#F2D4C2] focus:border-[#A65005] outline-none rounded-xl px-3 py-2.5 text-sm bg-white" required>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">No. HP *</label>
                            <input type="text" name="phone" placeholder="08xx-xxxx-xxxx"
                                   value="{{ old('phone') }}"
                                   class="w-full border-2 border-[#F2D4C2] focus:border-[#A65005] outline-none rounded-xl px-3 py-2.5 text-sm bg-white" required>
                        </div>
                        <div>
                            <label class="text-xs font-semibold text-gray-600 mb-1 block">Alamat Lengkap *</label>
                            <textarea name="address" rows="2" placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan..."
                                      class="w-full border-2 border-[#F2D4C2] focus:border-[#A65005] outline-none rounded-xl px-3 py-2.5 text-sm resize-none bg-white"
                                      required>{{ old('address') }}</textarea>
                        </div>
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-semibold text-gray-600 mb-1 block">Kota *</label>
                                <input type="text" name="city" placeholder="Kota / Kabupaten"
                                       value="{{ old('city') }}"
                                       class="w-full border-2 border-[#F2D4C2] focus:border-[#A65005] outline-none rounded-xl px-3 py-2.5 text-sm bg-white" required>
                            </div>
                            <div>
                                <label class="text-xs font-semibold text-gray-600 mb-1 block">Kode Pos *</label>
                                <input type="text" name="postal_code" placeholder="12345"
                                       value="{{ old('postal_code') }}"
                                       class="w-full border-2 border-[#F2D4C2] focus:border-[#A65005] outline-none rounded-xl px-3 py-2.5 text-sm bg-white" required>
                            </div>
                        </div>
                        <button type="submit"
                                class="w-full py-3 rounded-2xl font-bold text-sm flex items-center justify-center gap-2 transition-all hover:-translate-y-0.5 shadow-md"
                                style="background:linear-gradient(135deg,#D99C79,#A65005); color:#260101;">
                            <i class='bx bx-save'></i> Simpan Alamat
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-[1120px] mx-auto">

        {{-- HEADER + BACK --}}
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0"
                     style="background:rgba(242,212,194,0.15); border:1.5px solid rgba(242,212,194,0.35);">
                    <i class='bx bxs-credit-card-alt text-[#F2D4C2] text-2xl'></i>
                </div>
                <h1 class="text-3xl font-black text-[#F2D4C2] tracking-tight">Checkout</h1>
            </div>
            <a href="{{ route('cart.index') }}"
               class="inline-flex items-center gap-2 text-[#F2D4C2] hover:text-white text-sm font-semibold transition-all hover:-translate-x-1 opacity-70 hover:opacity-100">
                <i class='bx bx-arrow-back text-lg'></i>
                <span class="hidden sm:inline">Kembali ke Keranjang</span>
                <span class="sm:hidden">Kembali</span>
            </a>
        </div>

        {{-- GRID --}}
        <div class="flex flex-col gap-5 lg:grid lg:gap-8" style="grid-template-columns: 1fr 400px;">

            {{-- KOLOM KIRI --}}
            <div class="flex flex-col gap-5 lg:gap-6">

                {{-- ALAMAT --}}
                <div class="bg-[#FDF6F0] rounded-3xl p-6 shadow-xl">
                    <p class="text-xs font-black uppercase tracking-widest pb-3 mb-4 border-b-2 border-[#F2D4C2] flex items-center gap-2"
                       style="color:#800000;">
                        <i class='bx bxs-map' style="color:#A65005;"></i> Alamat Pengiriman
                    </p>
                    @php
                        $displayAddress = session('checkout_address_id')
                            ? $addresses->firstWhere('id', session('checkout_address_id'))
                            : ($defaultAddress ?? $addresses->first());
                    @endphp
                    @if($displayAddress)
                    <div class="flex justify-between items-start gap-4 p-4 rounded-2xl"
                         style="background:linear-gradient(135deg,#FFF5EE,#FCE8D8); border:1.5px solid #D99C79;">
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                <p class="font-bold text-gray-800 text-sm">{{ $displayAddress->receiver_name }}</p>
                                @if($displayAddress->is_default)
                                <span class="text-[10px] bg-red-100 text-[#800000] px-2 py-0.5 rounded-full font-bold">Default</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 leading-relaxed">{{ $displayAddress->full_address }}</p>
                            <p class="text-xs text-gray-500">{{ $displayAddress->phone }}</p>
                        </div>
                        <button @click="showAddressModal = true" type="button"
                            class="flex-shrink-0 flex items-center gap-1 text-[#F2D4C2] text-xs font-bold px-3 py-2 rounded-xl transition-all hover:-translate-y-0.5 shadow-md whitespace-nowrap"
                            style="background:#800000;">
                            <i class='bx bx-edit-alt'></i> Ubah
                        </button>
                    </div>
                    @else
                    <div class="flex justify-between items-center p-4 rounded-2xl border-2 border-dashed border-[#D99C79]">
                        <div class="flex items-center gap-2 text-gray-500">
                            <i class='bx bx-map text-lg'></i>
                            <p class="text-sm">Belum ada alamat pengiriman</p>
                        </div>
                        <button @click="showAddressModal = true; showAddForm = true" type="button"
                            class="flex items-center gap-1 text-[#F2D4C2] text-xs font-bold px-3 py-2 rounded-xl transition-all hover:-translate-y-0.5"
                            style="background:#800000;">
                            <i class='bx bx-plus'></i> Tambah
                        </button>
                    </div>
                    @endif
                </div>

                {{-- DETAIL PESANAN --}}
                <div class="bg-[#FDF6F0] rounded-3xl p-6 shadow-xl">
                    <p class="text-xs font-black uppercase tracking-widest pb-3 mb-4 border-b-2 border-[#F2D4C2] flex items-center gap-2" style="color:#800000;">
                        <i class='bx bxs-package' style="color:#A65005;"></i> Detail Pesanan
                    </p>
                    @foreach($checkoutItems as $item)
                    @php
                        $hasDiscount    = $item['discount_amount'] > 0;
                        $originalSubtotal   = $item['price'] * $item['quantity'];
                        $discountedSubtotal = $item['discounted_price'] * $item['quantity'];
                    @endphp
                    <div class="flex gap-4 py-4 border-b border-[#EFE3D8] last:border-0 last:pb-0 first:pt-0">
                        <img src="{{ asset('storage/'.$item['image']) }}" alt="{{ $item['name'] }}"
                             class="w-[72px] h-[72px] object-cover rounded-xl flex-shrink-0 border-2 border-[#F2D4C2]">
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-800 text-sm mb-0.5">{{ $item['name'] }}</p>
                            <p class="text-xs text-gray-500 mb-0.5">{{ $item['variant'] }}</p>

                            {{-- Harga per unit --}}
                            @if($hasDiscount)
                                <div class="flex items-center gap-2 mb-1">
                                    <p class="text-xs text-gray-400 line-through">IDR {{ number_format($item['price'],0,',','.') }}/item</p>
                                    <p class="text-xs font-semibold text-green-700">IDR {{ number_format($item['discounted_price'],0,',','.') }}/item</p>
                                </div>
                                <span class="inline-flex items-center gap-1 text-[10px] font-semibold text-green-700 bg-green-50 border border-green-200 px-2 py-0.5 rounded-md mb-1">
                                    <i class='bx bx-purchase-tag'></i> {{ $item['voucher'] }}
                                    — Hemat IDR {{ number_format($item['discount_amount'],0,',','.') }}
                                </span>
                            @else
                                <p class="text-xs text-gray-400 mb-1">IDR {{ number_format($item['price'],0,',','.') }}/item</p>
                            @endif

                            <p class="text-xs text-gray-400">Qty: {{ $item['quantity'] }}</p>
                        </div>

                        {{-- Subtotal kolom kanan --}}
                        <div class="flex-shrink-0 pt-0.5 text-right">
                            @if($hasDiscount)
                                <p class="text-xs text-gray-400 line-through">IDR {{ number_format($originalSubtotal,0,',','.') }}</p>
                                <p class="font-bold text-green-700 text-sm whitespace-nowrap">IDR {{ number_format($discountedSubtotal,0,',','.') }}</p>
                            @else
                                <p class="font-bold text-[#800000] text-sm whitespace-nowrap">IDR {{ number_format($originalSubtotal,0,',','.') }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- METODE PEMBAYARAN --}}
                <div class="bg-[#FDF6F0] rounded-3xl p-6 shadow-xl">
                    <p class="text-xs font-black uppercase tracking-widest pb-3 mb-4 border-b-2 border-[#F2D4C2] flex items-center gap-2" style="color:#800000;">
                        <i class='bx bxs-wallet' style="color:#A65005;"></i> Metode Pembayaran
                    </p>
                    @php
                    $paymentIcons = [
                        'cash'          => 'bx bxs-dollar-circle',
                        'bank_transfer' => 'bx bxs-bank',
                        'credit_card'   => 'bx bxs-credit-card',
                        'gopay'         => 'bx bxl-google',
                        'ovo'           => 'bx bxs-zap',
                        'dana'          => 'bx bxs-coin',
                        'cod'           => 'bx bxs-truck',
                        'qris'          => 'bx bx-qr',
                    ];
                    @endphp
                    <div class="grid grid-cols-2 gap-3">
                        @foreach($paymentMethods as $method)
                        <label class="flex items-center gap-2 px-4 py-3 border-2 rounded-2xl cursor-pointer font-semibold text-sm transition-all hover:-translate-y-0.5"
                               :class="paymentMethod == '{{ $method }}'
                                   ? 'bg-[#800000] text-white border-[#800000] shadow-lg'
                                   : 'border-gray-200 text-gray-700 hover:border-[#800000] hover:bg-red-50'">
                            <input type="radio" name="dummy_payment" class="sr-only"
                                   x-on:click="paymentMethod='{{ $method }}'">
                            <i class="{{ $paymentIcons[$method] ?? 'bx bx-credit-card' }} text-lg"
                               :class="paymentMethod == '{{ $method }}' ? 'text-[#F2D4C2]' : 'text-[#A65005]'"></i>
                            {{ ucwords(str_replace('_',' ',$method)) }}
                        </label>
                        @endforeach
                    </div>
                </div>

            </div>{{-- end kolom kiri --}}

            {{-- KOLOM KANAN --}}
            <div class="flex flex-col gap-5 lg:gap-6 lg:sticky lg:top-6 lg:self-start">

                {{-- RINCIAN PEMBAYARAN --}}
                <div class="bg-[#FDF6F0] rounded-3xl p-6 shadow-xl">
                    <p class="text-xs font-black uppercase tracking-widest pb-3 mb-4 border-b-2 border-[#F2D4C2] flex items-center gap-2" style="color:#800000;">
                        <i class='bx bxs-receipt' style="color:#A65005;"></i> Rincian Pembayaran
                    </p>
                    <div class="flex justify-between py-2.5 border-b border-dashed border-[#EAD9CE] text-sm text-gray-500">
                        <span>Subtotal Produk</span>
                        <span class="font-semibold text-gray-700">IDR {{ number_format($totalProducts,0,',','.') }}</span>
                    </div>
                    <div class="flex justify-between py-2.5 border-b border-dashed border-[#EAD9CE] text-sm text-gray-500">
                        <span>Ongkos Kirim</span>
                        <span class="font-semibold text-gray-700">IDR {{ number_format($shippingCost,0,',','.') }}</span>
                    </div>
                    <div class="flex justify-between py-2.5 border-b border-dashed border-[#EAD9CE] text-sm text-gray-500">
                        <span>Biaya Layanan</span>
                        <span class="font-semibold text-gray-700">IDR {{ number_format($serviceFee,0,',','.') }}</span>
                    </div>
                    @if($totalDiscount > 0)
                    <div class="flex justify-between py-2.5 border-b border-dashed border-[#EAD9CE] text-sm text-green-700">
                        <span class="flex items-center gap-1"><i class='bx bxs-discount'></i> Total Diskon</span>
                        <span class="font-semibold">- IDR {{ number_format($totalDiscount,0,',','.') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center mt-4 p-4 rounded-2xl shadow-lg"
                         style="background:linear-gradient(135deg,#800000,#260101);">
                        <span class="font-bold text-[#F2D4C2] text-sm">Total Pembayaran</span>
                        <span class="font-black text-white text-lg tracking-tight">IDR {{ number_format($totalPayable,0,',','.') }}</span>
                    </div>
                </div>

                {{-- TOMBOL CHECKOUT --}}
                <form action="{{ route('checkout.store') }}" method="POST">
                    @csrf
                    @foreach($checkoutItems as $item)
                    <input type="hidden" name="selected_products[]" value="{{ $item['id'] }}">
                    @endforeach
                    <input type="hidden" name="payment_method" :value="paymentMethod">
                    <button type="submit"
                        class="w-full flex items-center justify-center gap-2 font-black text-[#260101] py-4 rounded-2xl text-base tracking-wide transition-all hover:-translate-y-1 active:translate-y-0"
                        style="background:linear-gradient(135deg,#D99C79,#A65005); box-shadow:0 4px 0 rgba(38,1,1,0.55), 0 8px 28px rgba(217,156,121,0.4);">
                        <i class='bx bxs-lock-alt text-xl'></i>
                        Konfirmasi & Bayar
                    </button>
                </form>

            </div>{{-- end kolom kanan --}}

        </div>{{-- end grid --}}
    </div>
</div>

<script>
function setDefaultAddress(url) {
    if (!confirm('Jadikan alamat ini sebagai default?')) return;
    const form = document.getElementById('form-set-default');
    form.action = url;
    form.submit();
}

function deleteAddress(url) {
    if (!confirm('Hapus alamat ini? Tindakan ini tidak bisa dibatalkan.')) return;
    const form = document.getElementById('form-delete-address');
    form.action = url;
    form.submit();
}
</script>

@endsection