@extends('layouts.admin')

@section('content')

{{-- TOAST --}}
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

<div class="max-w-7xl mx-auto my-6 px-4 sm:px-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center shadow-lg"
                 style="background:linear-gradient(135deg,#800000,#260101);">
                <i class='bx bx-cart text-[#F2D4C2] text-xl'></i>
            </div>
            <div>
                <h1 class="text-2xl font-black" style="color:#260101;">Manage Orders</h1>
                <p class="text-xs" style="color:#A65005;">{{ $counts['all'] }} total pesanan</p>
            </div>
        </div>

        {{-- STATUS BADGES --}}
        <div class="flex gap-2 flex-wrap">
            @php
            $badgeConfig = [
                'pending'   => ['bg'=>'#FEF3C7','text'=>'#92400E','label'=>'Menunggu Pembayaran'],
                'paid'      => ['bg'=>'#DBEAFE','text'=>'#1E40AF','label'=>'Sedang Dikemas'],
                'shipped'   => ['bg'=>'#EDE9FE','text'=>'#4C1D95','label'=>'Dikirim'],
                'done'      => ['bg'=>'#D1FAE5','text'=>'#065F46','label'=>'Selesai'],
                'cancelled' => ['bg'=>'#FEE2E2','text'=>'#991B1B','label'=>'Batal'],
            ];
            @endphp
            @foreach($badgeConfig as $key => $bc)
            @if($counts[$key] > 0)
            <span class="text-xs font-bold px-3 py-1.5 rounded-full"
                  style="background:{{ $bc['bg'] }}; color:{{ $bc['text'] }};">
                {{ $bc['label'] }}: {{ $counts[$key] }}
            </span>
            @endif
            @endforeach
        </div>
    </div>

    {{-- FILTER --}}
    <form method="GET" class="bg-white rounded-2xl p-4 shadow-sm border border-[#EAD9CE] mb-6">
        <div class="flex flex-wrap gap-3 items-center">
            <select name="status"
                    class="border-2 border-[#F2D4C2] rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#A65005] flex-1 min-w-[140px]"
                    style="color:#260101;">
                <option value="">Semua Status</option>
                @foreach([
                    'pending'   => 'Menunggu Pembayaran',
                    'paid'      => 'Sedang Dikemas',
                    'shipped'   => 'Dikirim',
                    'done'      => 'Selesai',
                    'cancelled' => 'Dibatalkan'
                ] as $k => $v)
                <option value="{{ $k }}" {{ request('status') == $k ? 'selected' : '' }}>{{ $v }}</option>
                @endforeach
            </select>

            <select name="payment_method"
                    class="border-2 border-[#F2D4C2] rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#A65005] flex-1 min-w-[140px]"
                    style="color:#260101;">
                <option value="">Semua Pembayaran</option>
                @foreach([
                    'cash'          => 'Cash',
                    'transfer_bank' => 'Transfer Bank',
                    'credit_card'   => 'Credit Card',
                    'gopay'         => 'GoPay',
                    'ovo'           => 'OVO',
                    'dana'          => 'Dana',
                    'qris'          => 'QRIS',
                    'cod'           => 'COD'
                ] as $k => $v)
                <option value="{{ $k }}" {{ request('payment_method') == $k ? 'selected' : '' }}>{{ $v }}</option>
                @endforeach
            </select>

            <input type="text" name="search" placeholder="Cari nama customer..."
                   value="{{ request('search') }}"
                   class="border-2 border-[#F2D4C2] rounded-xl px-3 py-2 text-sm focus:outline-none focus:border-[#A65005] flex-1 min-w-[180px]"
                   style="color:#260101;">

            <button type="submit"
                    class="px-5 py-2 rounded-xl text-sm font-bold transition-all hover:-translate-y-0.5 shadow-md"
                    style="background:linear-gradient(135deg,#A65005,#592202); color:#F2D4C2;">
                <i class='bx bx-filter-alt'></i> Filter
            </button>

            <a href="{{ route('admin.orders.index') }}"
               class="px-5 py-2 rounded-xl text-sm font-bold border-2 transition-all hover:bg-red-50"
               style="border-color:#FCA5A5; color:#DC2626;">
                <i class='bx bx-reset'></i> Reset
            </a>
        </div>
    </form>

    {{-- DESKTOP TABLE --}}
    <div class="hidden md:block bg-white rounded-3xl shadow-md border border-[#EAD9CE] overflow-hidden">
        <div class="overflow-x-auto w-full">
            <table class="w-full" style="min-width:900px;">
                <thead>
                    <tr style="background:linear-gradient(135deg,#800000,#260101);">
                        <th class="px-4 py-4 text-left text-xs font-black text-[#F2D4C2] uppercase tracking-widest w-20">Order</th>
                        <th class="px-4 py-4 text-left text-xs font-black text-[#F2D4C2] uppercase tracking-widest">Customer</th>
                        <th class="px-4 py-4 text-left text-xs font-black text-[#F2D4C2] uppercase tracking-widest">Produk</th>
                        <th class="px-4 py-4 text-left text-xs font-black text-[#F2D4C2] uppercase tracking-widest w-32">Total</th>
                        <th class="px-4 py-4 text-left text-xs font-black text-[#F2D4C2] uppercase tracking-widest w-32">Pembayaran</th>
                        <th class="px-4 py-4 text-left text-xs font-black text-[#F2D4C2] uppercase tracking-widest w-40">Status</th>
                        <th class="px-4 py-4 text-left text-xs font-black text-[#F2D4C2] uppercase tracking-widest w-28">Tanggal</th>
                        <th class="px-4 py-4 text-left text-xs font-black text-[#F2D4C2] uppercase tracking-widest w-24">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-[#F2D4C2]">
                    @forelse($orders as $order)
                    @php
                    $sc = $badgeConfig[$order->status] ?? ['bg'=>'#F3F4F6','text'=>'#374151','label'=>ucfirst($order->status)];
                    @endphp
                    <tr class="hover:bg-[#FFF5EE] transition-colors">
                        <td class="px-4 py-4">
                            <span class="font-black text-sm" style="color:#800000;">#{{ $order->id }}</span>
                        </td>
                        <td class="px-4 py-4">
                            <p class="font-semibold text-sm" style="color:#260101;">{{ $order->user->name ?? '-' }}</p>
                            <p class="text-xs text-gray-400">{{ $order->user->email ?? '' }}</p>
                        </td>
                        <td class="px-4 py-4">
                            <p class="text-sm" style="color:#260101;">{{ $order->items->count() }} produk</p>
                            <p class="text-xs text-gray-400 truncate max-w-[120px]">
                                {{ $order->items->first()?->product_name ?? '-' }}
                                @if($order->items->count() > 1) +{{ $order->items->count()-1 }} lainnya @endif
                            </p>
                        </td>
                        <td class="px-4 py-4">
                            <span class="font-black text-sm" style="color:#800000;">
                                IDR {{ number_format($order->final_price,0,',','.') }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <span class="text-xs font-semibold px-2 py-1 rounded-full" style="background:#F2D4C2; color:#592202;">
                                {{ ucwords(str_replace('_',' ',$order->payment_method ?? '-')) }}
                            </span>
                        </td>
                        <td class="px-4 py-4">
                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                @csrf
                                <select name="status" onchange="this.form.submit()"
                                        class="text-xs font-bold px-2 py-1.5 rounded-full border-0 cursor-pointer focus:outline-none"
                                        style="background:{{ $sc['bg'] }}; color:{{ $sc['text'] }};">
                                    @foreach([
                                        'pending'   => 'Menunggu Pembayaran',
                                        'paid'      => 'Sedang Dikemas',
                                        'shipped'   => 'Dikirim',
                                        'done'      => 'Selesai',
                                        'cancelled' => 'Dibatalkan'
                                    ] as $k => $v)
                                    <option value="{{ $k }}" {{ $order->status == $k ? 'selected' : '' }}>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </form>
                        </td>
                        <td class="px-4 py-4">
                            <p class="text-xs text-gray-500">{{ $order->created_at->format('d M Y') }}</p>
                            <p class="text-xs text-gray-400">{{ $order->created_at->format('H:i') }}</p>
                        </td>
                        <td class="px-4 py-4">
                            <a href="{{ route('admin.orders.show', $order->id) }}"
                               class="inline-flex items-center gap-1 px-3 py-2 rounded-xl text-xs font-bold transition-all hover:-translate-y-0.5 shadow-sm whitespace-nowrap"
                               style="background:linear-gradient(135deg,#A65005,#592202); color:#F2D4C2;">
                                <i class='bx bx-detail'></i> Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-5 py-16 text-center">
                            <i class='bx bx-package text-5xl' style="color:#D99C79;"></i>
                            <p class="font-bold mt-2" style="color:#A65005;">Tidak ada pesanan ditemukan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MOBILE CARDS --}}
    <div class="md:hidden space-y-4 mt-4">
        @forelse($mobileOrders as $order)
        @php $sc = $badgeConfig[$order->status] ?? ['bg'=>'#F3F4F6','text'=>'#374151','label'=>ucfirst($order->status)]; @endphp
        <div class="bg-white rounded-3xl shadow-md border border-[#EAD9CE] overflow-hidden">
            <div class="flex items-center justify-between px-5 py-3 border-b border-[#F2D4C2]"
                 style="background:linear-gradient(135deg,#FFF5EE,#FCE8D8);">
                <span class="font-black text-sm" style="color:#800000;">#{{ $order->id }}</span>
                <span class="text-xs font-bold px-3 py-1 rounded-full"
                      style="background:{{ $sc['bg'] }}; color:{{ $sc['text'] }};">
                    {{ $sc['label'] }}
                </span>
            </div>
            <div class="px-5 py-4 space-y-2">
                <div class="flex justify-between">
                    <p class="font-semibold text-sm" style="color:#260101;">{{ $order->user->name ?? '-' }}</p>
                    <p class="font-black text-sm" style="color:#800000;">IDR {{ number_format($order->final_price,0,',','.') }}</p>
                </div>
                <p class="text-xs text-gray-500">{{ $order->items->count() }} produk • {{ ucwords(str_replace('_',' ',$order->payment_method ?? '-')) }}</p>
                <p class="text-xs text-gray-400">{{ $order->created_at->format('d M Y, H:i') }}</p>

                <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                    @csrf
                    <select name="status" onchange="this.form.submit()"
                            class="w-full text-xs font-bold px-3 py-2 rounded-xl border-0 cursor-pointer focus:outline-none mt-1"
                            style="background:{{ $sc['bg'] }}; color:{{ $sc['text'] }};">
                        @foreach([
                            'pending'   => 'Menunggu Pembayaran',
                            'paid'      => 'Sedang Dikemas',
                            'shipped'   => 'Dikirim',
                            'done'      => 'Selesai',
                            'cancelled' => 'Dibatalkan'
                        ] as $k => $v)
                        <option value="{{ $k }}" {{ $order->status == $k ? 'selected' : '' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </form>
            </div>
            <div class="px-5 pb-4">
                <a href="{{ route('admin.orders.show', $order->id) }}"
                   class="w-full py-2.5 rounded-xl text-xs font-bold flex items-center justify-center gap-1 transition-all hover:-translate-y-0.5 shadow-sm"
                   style="background:linear-gradient(135deg,#A65005,#592202); color:#F2D4C2;">
                    <i class='bx bx-detail'></i> Lihat Detail
                </a>
            </div>
        </div>
        @empty
        <div class="text-center py-12 bg-white rounded-3xl border border-[#EAD9CE]">
            <i class='bx bx-package text-5xl' style="color:#D99C79;"></i>
            <p class="font-bold mt-2" style="color:#A65005;">Tidak ada pesanan</p>
        </div>
        @endforelse

        {{-- PAGINATION MOBILE --}}
        <div class="flex justify-between mt-4">
            @if($mobileOrders->onFirstPage())
                <span></span>
            @else
                <a href="{{ $mobileOrders->previousPageUrl() }}"
                   class="px-4 py-2 rounded-xl text-sm font-bold border-2 transition-all"
                   style="border-color:#D99C79; color:#592202; background:white;">
                   &laquo; Sebelumnya
                </a>
            @endif
            @if($mobileOrders->hasMorePages())
                <a href="{{ $mobileOrders->nextPageUrl() }}"
                   class="px-4 py-2 rounded-xl text-sm font-bold border-2 transition-all"
                   style="border-color:#D99C79; color:#592202; background:white;">
                   Selanjutnya &raquo;
                </a>
            @else
                <span></span>
            @endif
        </div>
    </div>

</div>
@endsection