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

<div class="max-w-5xl mx-auto my-8 px-4 sm:px-6">

    {{-- HEADER --}}
    <div class="flex items-center justify-between mb-6 flex-wrap gap-3">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 rounded-2xl flex items-center justify-center shadow-lg"
                 style="background:linear-gradient(135deg,#800000,#260101);">
                <i class='bx bx-receipt text-[#F2D4C2] text-xl'></i>
            </div>
            <div>
                <h1 class="text-2xl font-black" style="color:#260101;">Order #{{ $order->id }}</h1>
                <p class="text-xs" style="color:#A65005;">{{ $order->created_at->format('d M Y, H:i') }}</p>
            </div>
        </div>
        <a href="{{ route('admin.orders.index') }}"
           class="flex items-center gap-2 px-4 py-2.5 rounded-2xl text-sm font-bold border-2 transition-all hover:-translate-x-1"
           style="border-color:#D99C79; color:#592202; background:white;">
            <i class='bx bx-arrow-back'></i> Kembali ke Orders
        </a>
    </div>

    {{-- STATUS TRACKER --}}
    @php
    // Cash: pending (nunggu bayar) → shipped → paid (sudah bayar) → done
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
    $badgeConfig = [
        'pending'   => ['bg'=>'#FEF3C7','text'=>'#92400E','label'=>'Menunggu Pembayaran'],
        'paid'      => ['bg'=>'#DBEAFE','text'=>'#1E40AF','label'=>'Sedang Dikemas'],
        'shipped'   => ['bg'=>'#EDE9FE','text'=>'#4C1D95','label'=>'Dikirim'],
        'done'      => ['bg'=>'#D1FAE5','text'=>'#065F46','label'=>'Selesai'],
        'cancelled' => ['bg'=>'#FEE2E2','text'=>'#991B1B','label'=>'Dibatalkan'],
    ];
    $sc = $badgeConfig[$order->status] ?? ['bg'=>'#F3F4F6','text'=>'#374151','label'=>ucfirst($order->status)];
    @endphp

    @if($order->status !== 'cancelled')
    <div class="bg-white rounded-3xl p-6 shadow-md border border-[#EAD9CE] mb-5">
        <div class="flex items-center justify-between mb-5 flex-wrap gap-2">
            <div class="flex items-center gap-3">
                <p class="text-xs font-black uppercase tracking-widest" style="color:#800000;">Status Pesanan</p>
                <span class="text-xs font-bold px-3 py-1.5 rounded-full flex items-center gap-1.5"
                      style="background:{{ $sc['bg'] }}; color:{{ $sc['text'] }};">
                    {{ $sc['label'] }}
                </span>
            </div>
            {{-- Update Status Form --}}
            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="flex items-center gap-2">
                @csrf
                <select name="status"
                        class="text-xs font-bold px-3 py-2 rounded-xl border-0 cursor-pointer focus:outline-none"
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
                <button type="submit"
                        class="px-4 py-2 rounded-xl text-xs font-bold transition-all hover:-translate-y-0.5 shadow-sm"
                        style="background:linear-gradient(135deg,#800000,#260101); color:#F2D4C2;">
                    <i class='bx bx-save'></i> Update
                </button>
            </form>
        </div>

        {{-- PROGRESS BAR --}}
        <div class="flex items-start">
            @foreach($allStatuses as $i => $s)
            <div class="flex flex-col items-center flex-1">
                <div class="w-10 h-10 rounded-full flex items-center justify-center text-sm font-black shadow-sm transition-all"
                     style="{{ $i <= $currentIndex
                         ? 'background:linear-gradient(135deg,#800000,#260101); color:#F2D4C2;'
                         : 'background:#F2D4C2; color:#D99C79;' }}">
                    @if($i < $currentIndex)
                        <i class='bx bx-check text-lg'></i>
                    @else
                        <i class='bx bx-{{ $statusIcons[$s] ?? "circle" }}'></i>
                    @endif
                </div>
                <p class="text-[11px] font-bold mt-2 text-center leading-tight max-w-[80px]"
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
    <div class="bg-red-50 border-2 border-red-200 rounded-3xl p-5 flex items-center justify-between gap-3 mb-5 flex-wrap">
        <div class="flex items-center gap-3">
            <i class='bx bx-x-circle text-red-500 text-3xl'></i>
            <p class="font-bold text-red-700">Pesanan ini telah dibatalkan</p>
        </div>
        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="flex items-center gap-2">
            @csrf
            <input type="hidden" name="status" value="paid">
            <button type="submit"
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all hover:-translate-y-0.5"
                    style="background:#DBEAFE; color:#1E40AF;">
                <i class='bx bx-revision'></i> Restore ke Sedang Dikemas
            </button>
        </form>
    </div>
    @endif

    {{-- 2 COLUMN LAYOUT --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- KOLOM KIRI --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- CUSTOMER INFO --}}
            <div class="bg-white rounded-3xl p-6 shadow-md border border-[#EAD9CE]">
                <p class="text-xs font-black uppercase tracking-widest pb-3 mb-4 border-b-2 border-[#F2D4C2] flex items-center gap-2" style="color:#800000;">
                    <i class='bx bxs-user' style="color:#A65005;"></i> Informasi Customer
                </p>
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-sm"
                         style="background:linear-gradient(135deg,#A65005,#592202);">
                        <i class='bx bx-user text-[#F2D4C2] text-xl'></i>
                    </div>
                    <div>
                        <p class="font-bold text-base" style="color:#260101;">{{ $order->user->name ?? '-' }}</p>
                        <p class="text-sm text-gray-500">{{ $order->user->email ?? '-' }}</p>
                    </div>
                </div>
                @if($order->address)
                <div class="mt-4 p-4 rounded-2xl" style="background:#FFF5EE;">
                    <p class="text-xs font-bold mb-2" style="color:#A65005;">
                        <i class='bx bxs-map'></i> Alamat Pengiriman
                    </p>
                    <p class="font-semibold text-sm" style="color:#260101;">{{ $order->address->receiver_name }}</p>
                    <p class="text-xs text-gray-500 mt-0.5 leading-relaxed">{{ $order->address->full_address }}</p>
                    <p class="text-xs text-gray-500 mt-0.5">{{ $order->address->phone }}</p>
                </div>
                @endif
            </div>

            {{-- ORDER ITEMS --}}
            <div class="bg-white rounded-3xl p-6 shadow-md border border-[#EAD9CE]">
                <p class="text-xs font-black uppercase tracking-widest pb-3 mb-4 border-b-2 border-[#F2D4C2] flex items-center gap-2" style="color:#800000;">
                    <i class='bx bxs-package' style="color:#A65005;"></i> Produk Dipesan
                </p>
                <div class="overflow-x-auto">
                    <table class="w-full min-w-[480px]">
                        <thead>
                            <tr style="background:linear-gradient(135deg,#FFF5EE,#FCE8D8);">
                                <th class="px-4 py-3 text-left text-xs font-black uppercase tracking-wide rounded-tl-xl" style="color:#800000;">Produk</th>
                                <th class="px-4 py-3 text-center text-xs font-black uppercase tracking-wide" style="color:#800000;">Qty</th>
                                <th class="px-4 py-3 text-right text-xs font-black uppercase tracking-wide" style="color:#800000;">Harga</th>
                                <th class="px-4 py-3 text-right text-xs font-black uppercase tracking-wide rounded-tr-xl" style="color:#800000;">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-[#F2D4C2]">
                            @foreach($order->items as $item)
                            <tr class="hover:bg-[#FFF5EE] transition-colors">
                                <td class="px-4 py-3">
                                    <div class="flex items-center gap-3">
                                        @if($item->variant?->product?->image)
                                        <img src="{{ asset('storage/'.$item->variant->product->image) }}"
                                             class="w-12 h-12 object-cover rounded-xl border-2 border-[#F2D4C2] flex-shrink-0">
                                        @else
                                        <div class="w-12 h-12 rounded-xl flex-shrink-0 flex items-center justify-center border-2 border-[#F2D4C2]" style="background:#FFF5EE;">
                                            <i class='bx bx-image-alt' style="color:#D99C79;"></i>
                                        </div>
                                        @endif
                                        <div>
                                            <p class="font-semibold text-sm" style="color:#260101;">{{ $item->product_name }}</p>
                                            <p class="text-xs text-gray-400">{{ $item->variant_name }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-center font-semibold text-sm" style="color:#260101;">{{ $item->qty }}</td>
                                <td class="px-4 py-3 text-right text-sm text-gray-500">IDR {{ number_format($item->price,0,',','.') }}</td>
                                <td class="px-4 py-3 text-right font-black text-sm" style="color:#800000;">IDR {{ number_format($item->subtotal,0,',','.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>{{-- end kolom kiri --}}

        {{-- KOLOM KANAN sticky --}}
        <div class="space-y-5 lg:sticky lg:top-6 lg:self-start">

            {{-- RINCIAN PEMBAYARAN --}}
            <div class="bg-white rounded-3xl p-6 shadow-md border border-[#EAD9CE]">
                <p class="text-xs font-black uppercase tracking-widest pb-3 mb-4 border-b-2 border-[#F2D4C2] flex items-center gap-2" style="color:#800000;">
                    <i class='bx bxs-receipt' style="color:#A65005;"></i> Rincian Pembayaran
                </p>
                <div class="flex justify-between py-2.5 border-b border-dashed border-[#EAD9CE] text-sm">
                    <span class="text-gray-500">Metode</span>
                    <span class="font-semibold capitalize" style="color:#260101;">
                        {{ ucwords(str_replace('_',' ',$order->payment_method ?? '-')) }}
                    </span>
                </div>
                <div class="flex justify-between py-2.5 border-b border-dashed border-[#EAD9CE] text-sm">
                    <span class="text-gray-500">Subtotal Produk</span>
                    <span class="font-semibold" style="color:#260101;">IDR {{ number_format($order->total_price,0,',','.') }}</span>
                </div>
                @if(isset($order->discount) && $order->discount > 0)
                <div class="flex justify-between py-2.5 border-b border-dashed border-[#EAD9CE] text-sm text-green-700">
                    <span class="flex items-center gap-1"><i class='bx bxs-discount'></i> Diskon</span>
                    <span class="font-semibold">- IDR {{ number_format($order->discount,0,',','.') }}</span>
                </div>
                @endif
                @if($order->voucher ?? false)
                <div class="flex justify-between py-2.5 border-b border-dashed border-[#EAD9CE] text-sm text-green-700">
                    <span class="flex items-center gap-1"><i class='bx bx-purchase-tag'></i> Voucher</span>
                    <span class="font-semibold">{{ $order->voucher->code }}</span>
                </div>
                @endif
                <div class="flex justify-between items-center mt-4 p-4 rounded-2xl"
                     style="background:linear-gradient(135deg,#800000,#260101);">
                    <span class="font-bold text-sm" style="color:#F2D4C2;">Total Pembayaran</span>
                    <span class="font-black text-lg" style="color:white;">IDR {{ number_format($order->final_price,0,',','.') }}</span>
                </div>
            </div>

            {{-- ORDER META --}}
            <div class="bg-white rounded-3xl p-6 shadow-md border border-[#EAD9CE]">
                <p class="text-xs font-black uppercase tracking-widest pb-3 mb-4 border-b-2 border-[#F2D4C2]" style="color:#800000;">Info Order</p>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Order ID</span>
                        <span class="font-bold" style="color:#800000;">#{{ $order->id }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Status</span>
                        <span class="text-xs font-bold px-2 py-1 rounded-full"
                              style="background:{{ $sc['bg'] }}; color:{{ $sc['text'] }};">
                            {{ $sc['label'] }}
                        </span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Tanggal</span>
                        <span class="font-semibold" style="color:#260101;">{{ $order->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Waktu</span>
                        <span class="font-semibold" style="color:#260101;">{{ $order->created_at->format('H:i') }} WIB</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Jumlah Produk</span>
                        <span class="font-semibold" style="color:#260101;">{{ $order->items->count() }} item</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Tipe Bayar</span>
                        <span class="font-semibold" style="color:#260101;">
                            {{ $order->payment_method === 'cash' ? 'Cash (Bayar di tempat)' : 'Online' }}
                        </span>
                    </div>
                </div>
            </div>

        </div>{{-- end kolom kanan --}}
    </div>
</div>

@endsection