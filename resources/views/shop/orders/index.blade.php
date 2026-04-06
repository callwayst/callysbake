{{-- resources/views/shop/orders/index.blade.php --}}
@extends('layouts.app')

@section('content')

{{-- TOAST SUCCESS --}}
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

@if(session('error'))
<div id="toast-err" class="fixed top-6 right-6 z-50 flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl"
     style="background:linear-gradient(135deg,#800000,#260101); border:1px solid rgba(242,212,194,0.2); transition:opacity .5s,transform .5s;">
    <div class="w-8 h-8 rounded-full flex items-center justify-center" style="background:rgba(242,212,194,0.15);">
        <i class='bx bx-error text-[#F2D4C2] text-lg'></i>
    </div>
    <div>
        <p class="text-[#F2D4C2] font-bold text-sm">Gagal!</p>
        <p class="text-[#D99C79] text-xs mt-0.5">{{ session('error') }}</p>
    </div>
    <button onclick="document.getElementById('toast-err').remove()" class="ml-2 text-[#D99C79] hover:text-white"><i class='bx bx-x text-lg'></i></button>
</div>
<script>setTimeout(()=>{const t=document.getElementById('toast-err');if(t){t.style.opacity='0';t.style.transform='translateX(20px)';setTimeout(()=>t.remove(),500);}},4000);</script>
@endif

<div class="min-h-screen px-4 sm:px-8 py-8 pb-16" style="background:#F2D4C2;">
    <div class="max-w-6xl mx-auto">

        {{-- HEADER --}}
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-lg"
                     style="background:linear-gradient(135deg,#800000,#260101);">
                    <i class='bx bx-package text-[#F2D4C2] text-2xl'></i>
                </div>
                <div>
                    <h1 class="text-3xl font-black tracking-tight" style="color:#260101;">Pesanan Saya</h1>
                    <p class="text-sm mt-0.5" style="color:#A65005;">Riwayat & status semua pesananmu</p>
                </div>
            </div>
            <a href="{{ route('products.index') }}"
               class="hidden sm:flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold transition-all hover:-translate-y-0.5 shadow-md"
               style="background:linear-gradient(135deg,#A65005,#592202); color:#F2D4C2;">
                <i class='bx bx-store'></i> Lanjut Belanja
            </a>
        </div>

        {{-- TABS --}}
        @php
        $tabs = [
            'all'       => ['label' => 'Semua',                'icon' => 'bx-list-ul'],
            'pending'   => ['label' => 'Menunggu Pembayaran',  'icon' => 'bx-time'],
            'paid'      => ['label' => 'Sedang Dikemas',       'icon' => 'bx-box'],
            'shipped'   => ['label' => 'Dikirim',              'icon' => 'bx-trip'],
            'done'      => ['label' => 'Selesai',              'icon' => 'bx-check-circle'],
            'cancelled' => ['label' => 'Dibatalkan',           'icon' => 'bx-x-circle'],
        ];
        @endphp
        <div class="flex gap-2 overflow-x-auto pb-2 mb-6" style="scrollbar-width:none;">
            @foreach($tabs as $key => $t)
            <a href="?tab={{ $key }}"
               class="flex-shrink-0 flex items-center gap-2 px-4 py-2.5 rounded-2xl text-xs font-bold transition-all whitespace-nowrap border-2"
               style="{{ $tab === $key
                   ? 'background:#800000; color:#F2D4C2; border-color:#800000; box-shadow:0 4px 14px rgba(128,0,0,0.3);'
                   : 'background:white; color:#592202; border-color:#D99C79;' }}">
                <i class='bx {{ $t["icon"] }}'></i>
                {{ $t['label'] }}
                @if($counts[$key] > 0)
                <span class="px-2 py-0.5 rounded-full text-[10px] font-black"
                      style="{{ $tab === $key ? 'background:rgba(242,212,194,0.3); color:#F2D4C2;' : 'background:#F2D4C2; color:#800000;' }}">
                    {{ $counts[$key] }}
                </span>
                @endif
            </a>
            @endforeach
        </div>

        {{-- ORDER LIST --}}
        @php
        $statusConfig = [
            'pending'   => ['bg' => '#FEF3C7', 'text' => '#92400E', 'dot' => '#F59E0B', 'label' => 'Menunggu Pembayaran'],
            'paid'      => ['bg' => '#DBEAFE', 'text' => '#1E40AF', 'dot' => '#3B82F6', 'label' => 'Sedang Dikemas'],
            'shipped'   => ['bg' => '#EDE9FE', 'text' => '#4C1D95', 'dot' => '#7C3AED', 'label' => 'Dikirim'],
            'done'      => ['bg' => '#D1FAE5', 'text' => '#065F46', 'dot' => '#10B981', 'label' => 'Selesai'],
            'cancelled' => ['bg' => '#FEE2E2', 'text' => '#991B1B', 'dot' => '#EF4444', 'label' => 'Dibatalkan'],
        ];
        @endphp

        @forelse($orders as $order)
        @php $sc = $statusConfig[$order->status] ?? ['bg'=>'#F3F4F6','text'=>'#374151','dot'=>'#9CA3AF','label'=>ucfirst($order->status)]; @endphp
        <div class="bg-white rounded-3xl shadow-md mb-4 overflow-hidden border border-[#EAD9CE] hover:shadow-xl hover:-translate-y-0.5 transition-all duration-300">

            {{-- ORDER HEADER --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-[#F2D4C2]"
                 style="background:linear-gradient(135deg,#FFF5EE,#FCE8D8);">
                <div class="flex items-center gap-3 flex-wrap">
                    <i class='bx bx-receipt text-lg' style="color:#A65005;"></i>
                    <span class="font-black text-sm" style="color:#260101;">Order #{{ $order->id }}</span>
                    <span class="text-xs text-gray-400 hidden sm:inline">• {{ $order->created_at->format('d M Y, H:i') }}</span>
                    <span class="text-xs text-gray-400 sm:hidden">• {{ $order->created_at->format('d M Y') }}</span>
                    @if($order->payment_method)
                    <span class="text-xs px-2 py-0.5 rounded-full font-semibold" style="background:#F2D4C2; color:#592202;">
                        {{ ucwords(str_replace('_',' ',$order->payment_method)) }}
                    </span>
                    @endif
                </div>
                <span class="text-xs font-bold px-3 py-1.5 rounded-full flex items-center gap-1.5 flex-shrink-0"
                      style="background:{{ $sc['bg'] }}; color:{{ $sc['text'] }};">
                    <span class="w-1.5 h-1.5 rounded-full" style="background:{{ $sc['dot'] }};"></span>
                    {{ $sc['label'] }}
                </span>
            </div>

            {{-- DESKTOP: TABLE LAYOUT | MOBILE: CARD LAYOUT --}}
            <div class="hidden md:block px-6 py-4">
                <div class="space-y-3">
                    @foreach($order->items->take(3) as $item)
                    <div class="flex items-center gap-4">
                        @if($item->variant?->product?->image)
                        <img src="{{ asset('storage/'.$item->variant->product->image) }}"
                             class="w-14 h-14 object-cover rounded-xl flex-shrink-0 border-2 border-[#F2D4C2]">
                        @else
                        <div class="w-14 h-14 rounded-xl flex-shrink-0 flex items-center justify-center" style="background:#F2D4C2;">
                            <i class='bx bx-image-alt text-xl' style="color:#A65005;"></i>
                        </div>
                        @endif
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-sm truncate" style="color:#260101;">{{ $item->product_name }}</p>
                            <p class="text-xs text-gray-500">{{ $item->variant_name }} • Qty {{ $item->qty }}</p>
                        </div>
                        <p class="font-bold text-sm whitespace-nowrap" style="color:#800000;">
                            IDR {{ number_format($item->subtotal,0,',','.') }}
                        </p>
                    </div>
                    @endforeach
                    @if($order->items->count() > 3)
                    <p class="text-xs text-gray-400">+{{ $order->items->count() - 3 }} produk lainnya</p>
                    @endif
                </div>
            </div>

            {{-- MOBILE: compact --}}
            <div class="md:hidden px-5 py-4 space-y-2">
                @foreach($order->items->take(2) as $item)
                <div class="flex items-center gap-3">
                    @if($item->variant?->product?->image)
                    <img src="{{ asset('storage/'.$item->variant->product->image) }}"
                         class="w-12 h-12 object-cover rounded-xl flex-shrink-0 border-2 border-[#F2D4C2]">
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-sm truncate" style="color:#260101;">{{ $item->product_name }}</p>
                        <p class="text-xs text-gray-500">{{ $item->variant_name }} • {{ $item->qty }}x</p>
                    </div>
                    <p class="font-bold text-sm whitespace-nowrap" style="color:#800000;">
                        IDR {{ number_format($item->subtotal,0,',','.') }}
                    </p>
                </div>
                @endforeach
                @if($order->items->count() > 2)
                <p class="text-xs text-gray-400">+{{ $order->items->count() - 2 }} produk lainnya</p>
                @endif
            </div>

            {{-- FOOTER --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 px-6 py-4 border-t border-[#F2D4C2]"
                 style="background:linear-gradient(135deg,#FFF5EE,#FCE8D8);">
                <div>
                    <p class="text-xs text-gray-500">Total Pembayaran</p>
                    <p class="font-black text-xl" style="color:#800000;">IDR {{ number_format($order->final_price,0,',','.') }}</p>
                </div>
                <div class="flex gap-2 flex-wrap">
                    @if(in_array($order->status, ['pending', 'paid']))
                    <form action="{{ route('orders.cancel', $order->id) }}" method="POST"
                          onsubmit="return confirm('Batalkan pesanan ini?')">
                        @csrf
                        <button class="px-4 py-2 rounded-xl border-2 text-xs font-bold transition-all hover:bg-red-50"
                                style="border-color:#FCA5A5; color:#DC2626;">
                            <i class='bx bx-x'></i> Batalkan
                        </button>
                    </form>
                    @endif
                    @if($order->status === 'shipped')
                    <form action="{{ route('orders.confirm', $order->id) }}" method="POST">
                        @csrf
                        <button class="px-4 py-2 rounded-xl text-xs font-bold text-white transition-all hover:-translate-y-0.5 shadow-md"
                                style="background:linear-gradient(135deg,#A65005,#592202);">
                            <i class='bx bx-check'></i> Pesanan Diterima
                        </button>
                    </form>
                    @endif
                    <a href="{{ route('orders.show', $order->id) }}"
                       class="px-4 py-2 rounded-xl text-xs font-bold border-2 transition-all hover:-translate-y-0.5"
                       style="border-color:#D99C79; color:#592202; background:white;">
                        <i class='bx bx-detail'></i> Lihat Detail
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-24 bg-white rounded-3xl border border-[#EAD9CE]">
            <div class="w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner"
                 style="background:#F2D4C2;">
                <i class='bx bx-package text-4xl' style="color:#D99C79;"></i>
            </div>
            <p class="font-black text-lg mb-1" style="color:#592202;">Belum ada pesanan</p>
            <p class="text-sm text-gray-400 mb-6">Yuk mulai belanja dan temukan produk favoritmu!</p>
            <a href="{{ route('products.index') }}"
               class="inline-flex items-center gap-2 px-6 py-3 rounded-2xl text-sm font-bold transition-all hover:-translate-y-0.5 shadow-lg"
               style="background:linear-gradient(135deg,#A65005,#592202); color:#F2D4C2;">
                <i class='bx bx-store'></i> Mulai Belanja
            </a>
        </div>
        @endforelse

    </div>
</div>
@endsection