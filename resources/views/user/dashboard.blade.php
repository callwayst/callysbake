@extends('layouts.app')

@section('content')

{{-- HERO --}}
<div class="rounded-2xl overflow-hidden mb-8"
     style="background:linear-gradient(135deg,#592202 0%,#A65005 55%,#D99C79 100%)">
  <div class="relative px-7 py-8">
    <div class="absolute right-6 bottom-0 text-[90px] opacity-10 leading-none select-none">🍰</div>
    <p class="text-[#F2D4C2]/80 text-sm mb-1">Selamat datang kembali 👋</p>
    <h1 class="text-white text-2xl md:text-3xl font-bold mb-4"
        style="font-family:'Dancing Script',cursive">
      {{ auth()->user()->name }}
    </h1>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
      @foreach([
        ['icon'=>'bx-cart',        'val'=>$cartCount,       'label'=>'Di Keranjang'],
        ['icon'=>'bx-package',     'val'=>$totalOrders,     'label'=>'Total Order'],
        ['icon'=>'bx-time-five',   'val'=>$pendingOrders,   'label'=>'Pending'],
        ['icon'=>'bx-check-circle','val'=>$completedOrders, 'label'=>'Selesai'],
      ] as $s)
        <div class="bg-white/15 backdrop-blur-sm rounded-xl px-4 py-3 flex items-center gap-3 border border-white/20">
          <i class="bx {{ $s['icon'] }} text-2xl text-[#F2D4C2]"></i>
          <div>
            <div class="text-white text-xl font-bold leading-none">{{ $s['val'] }}</div>
            <div class="text-[#F2D4C2]/75 text-xs mt-0.5">{{ $s['label'] }}</div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>

{{-- GRID --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 lg:items-stretch">

  {{-- KIRI --}}
  <div class="lg:col-span-2 flex flex-col gap-6">

    {{-- TOP PRODUCTS --}}
    <div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm p-6">
      <div class="flex items-center justify-between mb-5">
        <h2 class="font-bold text-[#A65005] flex items-center gap-2"
            style="font-family:'Playfair Display',serif">
          <i class='bx bxs-hot text-lg'></i> Pencarian Populer
        </h2>
        <a href="{{ route('products.index') }}"
           class="text-xs text-[#A65005] hover:text-[#592202] font-medium flex items-center gap-1 transition">
          Lihat semua <i class='bx bx-right-arrow-alt'></i>
        </a>
      </div>
      @if($topProducts->isEmpty())
        <p class="text-center text-sm text-[#D99C79] py-8">Belum ada produk tersedia.</p>
      @else
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
          @foreach($topProducts as $product)
            <a href="{{ route('products.show', $product->id) }}"
               class="group block rounded-xl border border-[#F2D4C2] overflow-hidden hover:shadow-md hover:-translate-y-1 transition-all duration-200">
              <div class="aspect-square bg-[#F9EDE3] overflow-hidden">
                @if($product->image)
                  <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}"
                       class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                @else
                  <div class="w-full h-full flex items-center justify-center text-4xl">🧁</div>
                @endif
              </div>
              <div class="p-3">
                <p class="text-xs font-semibold text-[#260101] truncate">{{ $product->name }}</p>
                <p class="text-xs text-[#D99C79] mt-0.5">{{ $product->category?->name ?? '-' }}</p>
                <div class="flex items-center justify-between mt-2">
                  <span class="text-xs font-bold text-[#A65005]">
                    Rp {{ number_format($product->min_price ?? $product->price, 0, ',', '.') }}
                  </span>
                  @if($product->sold_count > 0)
                    <span class="text-[0.6rem] text-[#D99C79] bg-[#F9EDE3] px-1.5 py-0.5 rounded-full">
                      {{ $product->sold_count }}x terjual
                    </span>
                  @endif
                </div>
                @if($product->average_rating)
                  <div class="flex items-center gap-0.5 mt-1.5">
                    <i class='bx bxs-star text-yellow-400 text-xs'></i>
                    <span class="text-[0.65rem] text-[#D99C79]">{{ $product->average_rating }}</span>
                  </div>
                @endif
              </div>
            </a>
          @endforeach
        </div>
      @endif
    </div>

    {{-- RECENT ORDERS --}}
    <div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm p-6 flex flex-col flex-1">
      <div class="flex items-center justify-between mb-5">
        <h2 class="font-bold text-[#A65005] flex items-center gap-2"
            style="font-family:'Playfair Display',serif">
          <i class='bx bx-receipt text-lg'></i> Order Terbaru
        </h2>
        <a href="{{ route('orders.index') }}"
           class="text-xs text-[#A65005] hover:text-[#592202] font-medium flex items-center gap-1 transition">
          Semua order <i class='bx bx-right-arrow-alt'></i>
        </a>
      </div>
      <div class="flex-1">
        @if($recentOrders->isEmpty())
          <div class="h-full flex flex-col items-center justify-center py-10">
            <div class="text-5xl mb-3">🛒</div>
            <p class="text-sm text-[#D99C79]">Belum ada order. Yuk mulai belanja!</p>
            <a href="{{ route('products.index') }}"
               class="inline-block mt-4 bg-[#A65005] text-white text-sm px-5 py-2 rounded-xl hover:bg-[#592202] transition">
              Belanja Sekarang
            </a>
          </div>
        @else
          <div class="space-y-3">
            @foreach($recentOrders as $order)
              @php
                $statusColor = match($order->status) {
                  'Completed'  => 'bg-green-100 text-green-700',
                  'Pending'    => 'bg-yellow-100 text-yellow-700',
                  'Processing' => 'bg-blue-100 text-blue-700',
                  'Cancelled'  => 'bg-red-100 text-red-700',
                  default      => 'bg-gray-100 text-gray-600',
                };
              @endphp
              <a href="{{ route('orders.show', $order->id) }}"
                 class="flex items-center justify-between p-3.5 rounded-xl border border-[#F2D4C2] hover:bg-[#F9EDE3] hover:border-[#D99C79] transition group">
                <div class="flex items-center gap-3">
                  <div class="w-9 h-9 rounded-xl bg-[#F2D4C2] flex items-center justify-center flex-shrink-0">
                    <i class='bx bx-package text-[#A65005]'></i>
                  </div>
                  <div>
                    <p class="text-sm font-semibold text-[#260101]">#{{ $order->id }}</p>
                    <p class="text-xs text-[#D99C79]">
                      {{ $order->items->count() }} item • {{ $order->created_at->format('d M Y') }}
                    </p>
                  </div>
                </div>
                <div class="flex items-center gap-3">
                  <p class="text-sm font-bold text-[#A65005] hidden sm:block">
                    Rp {{ number_format($order->final_price, 0, ',', '.') }}
                  </p>
                  <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $statusColor }}">
                    {{ $order->status }}
                  </span>
                  <i class='bx bx-chevron-right text-[#D99C79] group-hover:text-[#A65005] transition'></i>
                </div>
              </a>
            @endforeach
          </div>
        @endif
      </div>
    </div>

  </div>

  {{-- KANAN --}}
  <div class="flex flex-col gap-6">

    {{-- PROFILE --}}
    <div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm p-5 flex items-center gap-4">
      <div class="w-12 h-12 rounded-full bg-gradient-to-br from-[#F2D4C2] to-[#D99C79] flex items-center justify-center text-[#A65005] font-bold text-lg overflow-hidden flex-shrink-0">
        @if(auth()->user()->avatar)
          <img src="{{ asset('storage/'.auth()->user()->avatar) }}" class="w-full h-full object-cover">
        @else
          {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
        @endif
      </div>
      <div class="min-w-0">
        <p class="font-semibold text-sm text-[#260101] truncate">{{ auth()->user()->name }}</p>
        <p class="text-xs text-[#D99C79] truncate">{{ auth()->user()->email }}</p>
        @if(auth()->user()->phone)
          <p class="text-xs text-[#D99C79] truncate">{{ auth()->user()->phone }}</p>
        @endif
      </div>
      <a href="{{ route('shop.profile.edit') }}"
         class="ml-auto shrink-0 text-xs bg-[#F2D4C2] text-[#A65005] font-medium px-3 py-1.5 rounded-lg hover:bg-[#D99C79] hover:text-white transition">
        Edit
      </a>
    </div>

    {{-- QUICK ACTIONS --}}
    <div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm p-6">
      <h2 class="font-bold text-[#A65005] mb-4 flex items-center gap-2"
          style="font-family:'Playfair Display',serif">
        <i class='bx bx-zap text-lg'></i> Menu Cepat
      </h2>
      <div class="grid grid-cols-2 gap-3">
        @foreach([
          ['route'=>'products.index',     'icon'=>'bx-store',        'label'=>'Produk'],
          ['route'=>'cart.index',         'icon'=>'bx-cart',         'label'=>'Keranjang'],
          ['route'=>'orders.index',       'icon'=>'bx-package',      'label'=>'Pesanan'],
          ['route'=>'user.vouchers.index','icon'=>'bx-purchase-tag', 'label'=>'Voucher'],
        ] as $q)
          <a href="{{ route($q['route']) }}"
             class="flex flex-col items-center gap-2 p-4 rounded-xl bg-[#FDF3EC] border border-[#F2D4C2] hover:border-[#D99C79] hover:shadow-sm hover:-translate-y-0.5 transition-all">
            <i class="bx {{ $q['icon'] }} text-2xl text-[#A65005]"></i>
            <span class="text-xs font-medium text-[#260101]">{{ $q['label'] }}</span>
          </a>
        @endforeach
      </div>
    </div>

    {{-- VOUCHER --}}
    <div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm p-6">
      <div class="flex items-center justify-between mb-4">
        <h2 class="font-bold text-[#A65005] flex items-center gap-2"
            style="font-family:'Playfair Display',serif">
          <i class='bx bxs-coupon text-lg'></i> Voucher
        </h2>
        <a href="{{ route('user.vouchers.index') }}"
           class="text-xs text-[#A65005] hover:text-[#592202] font-medium transition">
          Lihat semua
        </a>
      </div>
      <div class="rounded-xl overflow-hidden border border-dashed border-[#D99C79]"
           style="background:linear-gradient(135deg,#fdf8f4,#F2D4C2)">
        <div class="px-5 py-4 flex items-center gap-4">
          <div class="w-12 h-12 rounded-full bg-[#A65005] flex items-center justify-center flex-shrink-0">
            <i class='bx bxs-coupon text-[#F2D4C2] text-2xl'></i>
          </div>
          <div>
            <p class="text-2xl font-bold text-[#A65005]">{{ $availableVouchers }}</p>
            <p class="text-xs text-[#592202] font-medium">voucher tersedia</p>
          </div>
        </div>
        @if($availableVouchers > 0)
          <div class="px-5 py-2.5 bg-[#A65005]/10 border-t border-dashed border-[#D99C79]">
            <p class="text-xs text-[#592202]">
              <i class='bx bx-info-circle'></i> Gunakan saat checkout untuk diskon!
            </p>
          </div>
        @endif
      </div>
    </div>

    {{-- STATISTIK — flex-1 supaya ikut tinggi kolom kiri --}}
    <div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm p-6 flex flex-col flex-1">
      <h2 class="font-bold text-[#A65005] mb-4 flex items-center gap-2"
          style="font-family:'Playfair Display',serif">
        <i class='bx bx-bar-chart-alt-2 text-lg'></i> Statistik Kamu
      </h2>
      <div class="space-y-1 flex-1">
        @foreach([
          ['label'=>'Total Order',  'val'=>$totalOrders,     'icon'=>'bx-package',      'color'=>'text-blue-500',   'bar'=>'bg-blue-400'],
          ['label'=>'Selesai',      'val'=>$completedOrders, 'icon'=>'bx-check-circle', 'color'=>'text-green-500',  'bar'=>'bg-green-400'],
          ['label'=>'Pending',      'val'=>$pendingOrders,   'icon'=>'bx-time-five',    'color'=>'text-yellow-500', 'bar'=>'bg-yellow-400'],
          ['label'=>'Di Keranjang', 'val'=>$cartCount,       'icon'=>'bx-cart',         'color'=>'text-[#A65005]',  'bar'=>'bg-[#A65005]'],
        ] as $stat)
          @php $pct = $totalOrders > 0 ? min(100, round($stat['val'] / $totalOrders * 100)) : 0; @endphp
          <div class="py-2.5 border-b border-[#F2D4C2] last:border-0">
            <div class="flex items-center justify-between mb-1.5">
              <div class="flex items-center gap-2">
                <i class="bx {{ $stat['icon'] }} {{ $stat['color'] }} text-base"></i>
                <span class="text-xs text-[#260101]">{{ $stat['label'] }}</span>
              </div>
              <span class="font-bold text-sm text-[#A65005]">{{ $stat['val'] }}</span>
            </div>
            <div class="h-1.5 bg-[#F2D4C2] rounded-full overflow-hidden">
              <div class="h-full {{ $stat['bar'] }} rounded-full transition-all duration-500"
                   style="width:{{ $pct }}%"></div>
            </div>
          </div>
        @endforeach
      </div>
    </div>

    {{-- BANNER PROMO --}}
    <div class="rounded-2xl overflow-hidden relative"
         style="background:linear-gradient(135deg,#592202,#800000)">
      <div class="absolute right-3 bottom-0 text-6xl opacity-10 select-none">🎂</div>
      <div class="relative p-5">
        <p class="text-[#F2D4C2]/80 text-xs mb-1">Spesial buat kamu</p>
        <p class="text-white font-bold text-base leading-snug mb-3"
           style="font-family:'Playfair Display',serif">
          Temukan bahan baking premium pilihan kami!
        </p>
        <a href="{{ route('products.index') }}"
           class="inline-flex items-center gap-1.5 bg-[#F2D4C2] text-[#592202] text-xs font-bold px-4 py-2 rounded-xl hover:bg-white transition">
          Belanja Sekarang <i class='bx bx-right-arrow-alt'></i>
        </a>
      </div>
    </div>

  </div>

</div>

@endsection