{{-- resources/views/shop/orders/show.blade.php --}}
@extends('layouts.app')

@section('content')

@if(session('success'))
<div id="toast" class="fixed top-6 right-6 z-50 flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl"
     style="background:linear-gradient(135deg,#A65005,#592202); border:1px solid rgba(242,212,194,0.3); transition:opacity .5s,transform .5s;">
    <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background:rgba(242,212,194,0.2);">
        <i class='bx bx-check text-[#F2D4C2] text-lg'></i>
    </div>
    <div>
        <p class="text-[#F2D4C2] font-bold text-sm">Berhasil!</p>
        <p class="text-[#D99C79] text-xs mt-0.5">{{ session('success') }}</p>
    </div>
    <button onclick="document.getElementById('toast').remove()" class="ml-2 text-[#D99C79] hover:text-white"><i class='bx bx-x text-lg'></i></button>
</div>
<script>setTimeout(()=>{const t=document.getElementById('toast');if(t){t.style.opacity='0';t.style.transform='translateX(20px)';setTimeout(()=>t.remove(),500);}},3500);</script>
@endif

<div class="min-h-screen px-4 sm:px-8 py-8 pb-16" style="background:#F2D4C2;">
    <div class="max-w-6xl mx-auto">

        {{-- HEADER --}}
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center shadow-lg"
                     style="background:linear-gradient(135deg,#800000,#260101);">
                    <i class='bx bx-receipt text-[#F2D4C2] text-2xl'></i>
                </div>
                <div>
                    <h1 class="text-3xl font-black tracking-tight" style="color:#260101;">Order #{{ $order->id }}</h1>
                    <p class="text-sm" style="color:#A65005;">{{ $order->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
            <a href="{{ route('orders.index') }}"
               class="flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold border-2 transition-all hover:-translate-x-1"
               style="border-color:#D99C79; color:#592202; background:white;">
                <i class='bx bx-arrow-back'></i>
                <span class="hidden sm:inline">Kembali ke Pesanan</span>
                <span class="sm:hidden">Kembali</span>
            </a>
        </div>

        {{-- STATUS TRACKER --}}
        @php
        // Cash: pending (menunggu bayar) → shipped → paid (sudah bayar) → done
        // Online: paid (sudah bayar/dikemas) → shipped → done
        $allStatuses = $order->payment_method === 'cash'
            ? ['pending', 'shipped', 'paid', 'done']
            : ['paid', 'shipped', 'done'];
        $currentIndex = array_search($order->status, $allStatuses);
        $currentIndex = $currentIndex === false ? 0 : $currentIndex;
        $statusLabels = [
            'pending'  => 'Menunggu Pembayaran',
            'paid'     => 'Sedang Dikemas',
            'shipped'  => 'Dikirim',
            'done'     => 'Selesai',
        ];
        $statusIcons = [
            'pending'  => 'bx-time',
            'paid'     => 'bx-box',
            'shipped'  => 'bx-trip',
            'done'     => 'bx-check-circle',
        ];
        @endphp

        @if($order->status !== 'cancelled')
        <div class="bg-white rounded-3xl p-6 shadow-md mb-6 border border-[#EAD9CE]">
            <p class="text-xs font-black uppercase tracking-widest mb-5" style="color:#800000;">Status Pesanan</p>
            <div class="flex items-start">
                @foreach($allStatuses as $i => $s)
                <div class="flex flex-col items-center flex-1">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-black transition-all shadow-sm"
                         style="{{ $i <= $currentIndex
                             ? 'background:linear-gradient(135deg,#800000,#260101); color:#F2D4C2;'
                             : 'background:#F2D4C2; color:#D99C79;' }}">
                        @if($i < $currentIndex)
                            <i class='bx bx-check text-lg'></i>
                        @else
                            <i class='bx bx-{{ $statusIcons[$s] ?? "circle" }}'></i>
                        @endif
                    </div>
                    <p class="text-[11px] font-bold mt-2 text-center"
                       style="{{ $i <= $currentIndex ? 'color:#800000;' : 'color:#D99C79;' }}">
                        {{ $statusLabels[$s] ?? $s }}
                    </p>
                </div>
                @if(!$loop->last)
                <div class="flex-1 h-0.5 mt-5"
                     style="{{ $i < $currentIndex ? 'background:#800000;' : 'background:#F2D4C2;' }}"></div>
                @endif
                @endforeach
            </div>
        </div>
        @else
        <div class="bg-red-50 border-2 border-red-200 rounded-3xl p-5 flex items-center gap-3 mb-6">
            <i class='bx bx-x-circle text-red-500 text-3xl'></i>
            <div>
                <p class="font-bold text-red-700">Pesanan Dibatalkan</p>
                <p class="text-xs text-red-500 mt-0.5">Pesanan ini telah dibatalkan</p>
            </div>
        </div>
        @endif

        {{-- 2 COLUMN DESKTOP LAYOUT --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- KOLOM KIRI (2/3) --}}
            <div class="lg:col-span-2 space-y-5">

                {{-- DETAIL PRODUK --}}
                <div class="bg-white rounded-3xl p-6 shadow-md border border-[#EAD9CE]">
                    <p class="text-xs font-black uppercase tracking-widest pb-3 mb-4 border-b-2 border-[#F2D4C2] flex items-center gap-2" style="color:#800000;">
                        <i class='bx bxs-package' style="color:#A65005;"></i> Detail Produk
                    </p>
                    @foreach($order->items as $item)
                    <div class="flex gap-4 py-4 border-b border-[#F2D4C2] last:border-0 last:pb-0 first:pt-0">
                        @if($item->variant?->product?->image)
                        <img src="{{ asset('storage/'.$item->variant->product->image) }}"
                             class="w-20 h-20 object-cover rounded-2xl flex-shrink-0 border-2 border-[#F2D4C2]">
                        @else
                        <div class="w-20 h-20 rounded-2xl flex-shrink-0 flex items-center justify-center border-2 border-[#F2D4C2]" style="background:#FFF5EE;">
                            <i class='bx bx-image-alt text-2xl' style="color:#D99C79;"></i>
                        </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="font-bold text-base mb-0.5" style="color:#260101;">{{ $item->product_name }}</p>
                            <p class="text-sm text-gray-500 mb-1">{{ $item->variant_name }}</p>
                            <p class="text-xs text-gray-400">{{ $item->qty }}x IDR {{ number_format($item->price,0,',','.') }}</p>
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="font-black text-base" style="color:#800000;">IDR {{ number_format($item->subtotal,0,',','.') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- ALAMAT --}}
                <div class="bg-white rounded-3xl p-6 shadow-md border border-[#EAD9CE]">
                    <p class="text-xs font-black uppercase tracking-widest pb-3 mb-4 border-b-2 border-[#F2D4C2] flex items-center gap-2" style="color:#800000;">
                        <i class='bx bxs-map' style="color:#A65005;"></i> Alamat Pengiriman
                    </p>
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                             style="background:linear-gradient(135deg,#800000,#260101);">
                            <i class='bx bx-map-pin text-[#F2D4C2]'></i>
                        </div>
                        <div>
                            <p class="font-bold text-base" style="color:#260101;">{{ $order->address->receiver_name ?? '-' }}</p>
                            <p class="text-sm text-gray-500 mt-1 leading-relaxed">{{ $order->address->full_address ?? '-' }}</p>
                            <p class="text-sm text-gray-500 mt-0.5">{{ $order->address->phone ?? '-' }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                <i class='bx bx-envelope'></i>
                                {{ $order->address->email ?? $order->user->email ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>

                {{-- REVIEW (hanya kalau done) --}}
                @if($order->status === 'done')
                <div class="bg-white rounded-3xl p-6 shadow-md border border-[#EAD9CE]">
                    <p class="text-xs font-black uppercase tracking-widest pb-3 mb-5 border-b-2 border-[#F2D4C2] flex items-center gap-2" style="color:#800000;">
                        <i class='bx bxs-star' style="color:#A65005;"></i> Beri Ulasan
                    </p>
                    @foreach($order->items as $item)
                    @php $productId = $item->variant?->product?->id; @endphp
                    @if($productId)
                    <div class="mb-6 pb-6 border-b border-[#F2D4C2] last:border-0 last:mb-0 last:pb-0">
                        <div class="flex items-center gap-3 mb-4">
                            @if($item->variant?->product?->image)
                            <img src="{{ asset('storage/'.$item->variant->product->image) }}"
                                 class="w-14 h-14 object-cover rounded-xl border-2 border-[#F2D4C2]">
                            @endif
                            <div>
                                <p class="font-bold" style="color:#260101;">{{ $item->product_name }}</p>
                                <p class="text-xs text-gray-400">{{ $item->variant_name }}</p>
                            </div>
                        </div>
                        @if(in_array($productId, $reviewedProductIds))
                        <div class="flex items-center gap-2 px-4 py-3 rounded-2xl border border-green-200"
                             style="background:#F0FDF4;">
                            <i class='bx bx-check-circle text-green-600 text-xl'></i>
                            <p class="text-green-700 text-sm font-semibold">Sudah diulas — terima kasih!</p>
                        </div>
                        @else
                        <form action="{{ route('reviews.store') }}" method="POST" x-data="{ rating: 0, hover: 0 }">
                            @csrf
                            <input type="hidden" name="product_id" value="{{ $productId }}">
                            <div class="flex gap-2 mb-4">
                                @for($s = 1; $s <= 5; $s++)
                                <button type="button"
                                        @click="rating = {{ $s }}"
                                        @mouseenter="hover = {{ $s }}"
                                        @mouseleave="hover = 0"
                                        class="text-3xl transition-transform hover:scale-125 focus:outline-none">
                                    <i class='bx' :class="(hover || rating) >= {{ $s }} ? 'bxs-star text-amber-400' : 'bx-star text-gray-300'"></i>
                                </button>
                                @endfor
                                <span class="ml-2 text-sm font-semibold self-center text-gray-400" x-text="rating > 0 ? rating + '/5' : ''"></span>
                            </div>
                            <input type="hidden" name="rating" :value="rating">
                            <textarea name="comment" rows="3"
                                      placeholder="Ceritakan pengalamanmu dengan produk ini..."
                                      class="w-full text-sm border-2 outline-none rounded-2xl px-4 py-3 resize-none text-gray-700 bg-white transition-colors"
                                      style="border-color:#F2D4C2;"
                                      onfocus="this.style.borderColor='#A65005'"
                                      onblur="this.style.borderColor='#F2D4C2'"></textarea>
                            <button type="submit" x-show="rating > 0"
                                    class="mt-3 px-6 py-2.5 rounded-2xl font-bold text-sm transition-all hover:-translate-y-0.5 shadow-md"
                                    style="background:linear-gradient(135deg,#D99C79,#A65005); color:#260101;">
                                <i class='bx bx-send'></i> Kirim Ulasan
                            </button>
                        </form>
                        @endif
                    </div>
                    @endif
                    @endforeach
                </div>
                @endif

            </div>{{-- end kolom kiri --}}

            {{-- KOLOM KANAN (1/3) sticky --}}
            <div class="space-y-5 lg:sticky lg:top-6 lg:self-start">

                {{-- RINCIAN PEMBAYARAN --}}
                <div class="bg-white rounded-3xl p-6 shadow-md border border-[#EAD9CE]">
                    <p class="text-xs font-black uppercase tracking-widest pb-3 mb-4 border-b-2 border-[#F2D4C2] flex items-center gap-2" style="color:#800000;">
                        <i class='bx bxs-receipt' style="color:#A65005;"></i> Rincian Pembayaran
                    </p>
                    <div class="flex justify-between py-2.5 border-b border-dashed border-[#EAD9CE] text-sm">
                        <span class="text-gray-500">Metode</span>
                        <span class="font-semibold capitalize" style="color:#260101;">{{ ucwords(str_replace('_',' ',$order->payment_method ?? '-')) }}</span>
                    </div>
                    <div class="flex justify-between py-2.5 border-b border-dashed border-[#EAD9CE] text-sm">
                        <span class="text-gray-500">Subtotal</span>
                        <span class="font-semibold" style="color:#260101;">IDR {{ number_format($order->total_price,0,',','.') }}</span>
                    </div>
                    @if($order->discount > 0)
                    <div class="flex justify-between py-2.5 border-b border-dashed border-[#EAD9CE] text-sm text-green-700">
                        <span class="flex items-center gap-1"><i class='bx bxs-discount'></i> Diskon</span>
                        <span class="font-semibold">- IDR {{ number_format($order->discount,0,',','.') }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center mt-4 p-4 rounded-2xl"
                         style="background:linear-gradient(135deg,#800000,#260101);">
                        <span class="font-bold text-sm" style="color:#F2D4C2;">Total</span>
                        <span class="font-black text-lg" style="color:white;">IDR {{ number_format($order->final_price,0,',','.') }}</span>
                    </div>
                </div>

                {{-- AKSI --}}
                <div class="space-y-3">
                    @if(in_array($order->status, ['pending', 'paid']))
                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST"
                          onsubmit="return confirm('Batalkan pesanan ini?')">
                        @csrf
                        <button class="w-full py-3 rounded-2xl border-2 font-bold text-sm transition-all hover:bg-red-50"
                                style="border-color:#FCA5A5; color:#DC2626;">
                            <i class='bx bx-x-circle'></i> Batalkan Pesanan
                        </button>
                    </form>
                    @endif
                    @if($order->status === 'shipped')
                    <form action="{{ route('orders.confirm', $order->id) }}" method="POST">
                        @csrf
                        <button class="w-full py-3 rounded-2xl font-bold text-sm transition-all hover:-translate-y-0.5 shadow-lg"
                                style="background:linear-gradient(135deg,#D99C79,#A65005); color:#260101; box-shadow:0 4px 0 rgba(38,1,1,0.3);">
                            <i class='bx bx-check-circle'></i> Pesanan Diterima
                        </button>
                    </form>
                    @endif
                    <a href="{{ route('products.index') }}"
                       class="w-full py-3 rounded-2xl font-bold text-sm border-2 transition-all hover:-translate-y-0.5 flex items-center justify-center gap-2"
                       style="border-color:#D99C79; color:#592202; background:white;">
                        <i class='bx bx-store'></i> Lanjut Belanja
                    </a>
                </div>

            </div>{{-- end kolom kanan --}}
        </div>
    </div>
</div>
@endsection