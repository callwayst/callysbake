@extends('layouts.app')

@section('content')

{{-- HEADER --}}
<div class="rounded-2xl overflow-hidden mb-8"
     style="background:linear-gradient(135deg,#592202 0%,#A65005 60%,#D99C79 100%)">
  <div class="relative px-7 py-8">
    <div class="absolute right-6 bottom-0 text-[80px] opacity-10 leading-none select-none">🛍️</div>
    <p class="text-[#F2D4C2]/80 text-sm mb-1">Pilihan terbaik untuk kamu</p>
    <h1 class="text-white text-2xl font-bold" style="font-family:'Dancing Script',cursive">
      Semua Produk
    </h1>
  </div>
</div>

{{-- FILTER BAR --}}
<div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm p-4 mb-6">
  <form action="{{ route('products.index') }}" method="GET"
        class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">

    {{-- Search --}}
    <div class="flex items-center gap-2 flex-1 border border-[#F2D4C2] rounded-xl px-4 py-2.5 bg-[#fdfaf8] focus-within:border-[#D99C79] transition">
      <i class='bx bx-search text-[#D99C79]'></i>
      <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari produk..."
             class="flex-1 outline-none text-sm bg-transparent text-[#260101] placeholder-[#D99C79]">
    </div>

    {{-- Filters --}}
    <div class="flex gap-2 flex-wrap sm:flex-nowrap">
      <select name="category" onchange="this.form.submit()"
              class="border border-[#F2D4C2] rounded-xl px-3 py-2.5 text-sm text-[#260101] bg-[#fdfaf8] outline-none focus:border-[#D99C79] transition">
        <option value="">Semua Kategori</option>
        @foreach($categories as $cat)
          <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
            {{ $cat->name }}
          </option>
        @endforeach
      </select>

      <select name="price_range" onchange="this.form.submit()"
              class="border border-[#F2D4C2] rounded-xl px-3 py-2.5 text-sm text-[#260101] bg-[#fdfaf8] outline-none focus:border-[#D99C79] transition">
        <option value="">Semua Harga</option>
        <option value="0-50000"       {{ request('price_range') == '0-50000'       ? 'selected' : '' }}>0 – 50k</option>
        <option value="50001-100000"  {{ request('price_range') == '50001-100000'  ? 'selected' : '' }}>50k – 100k</option>
        <option value="100001-200000" {{ request('price_range') == '100001-200000' ? 'selected' : '' }}>100k – 200k</option>
        <option value="200001-500000" {{ request('price_range') == '200001-500000' ? 'selected' : '' }}>200k – 500k</option>
      </select>

      <button type="submit"
              class="px-5 py-2.5 rounded-xl text-sm font-semibold text-white transition hover:opacity-90"
              style="background:linear-gradient(135deg,#A65005,#592202)">
        Cari
      </button>

      @if(request()->hasAny(['q','category','price_range']))
        <a href="{{ route('products.index') }}"
           class="px-4 py-2.5 rounded-xl text-sm font-medium text-[#A65005] bg-[#FDF3EC] border border-[#F2D4C2] hover:border-[#D99C79] transition">
          Reset
        </a>
      @endif
    </div>

  </form>
</div>

{{-- GRID --}}
@if($products->isEmpty())
  <div class="bg-white rounded-2xl border border-[#F2D4C2] p-16 text-center">
    <div class="text-6xl mb-4">🔍</div>
    <p class="text-[#D99C79]">Produk tidak ditemukan.</p>
    <a href="{{ route('products.index') }}" class="inline-block mt-4 text-sm text-[#A65005] font-medium hover:underline">
      Lihat semua produk
    </a>
  </div>
@else
  <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
    @foreach($products as $product)
      @php
        $avgRating   = $product->reviews_avg_rating ?? 0;
        $reviewCount = $product->reviews_count ?? 0;
      @endphp
      <a href="{{ route('products.show', $product->id) }}"
         class="group bg-white rounded-2xl border border-[#F2D4C2] shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-200 flex flex-col overflow-hidden">

        {{-- Image --}}
        <div class="aspect-square bg-[#F9EDE3] overflow-hidden">
          @if($product->image)
            <img src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}"
                 class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
          @else
            <div class="w-full h-full flex items-center justify-center text-5xl">🧁</div>
          @endif
        </div>

        {{-- Info --}}
        <div class="p-3 flex flex-col gap-1.5 flex-1">
          <p class="text-sm font-semibold text-[#260101] line-clamp-2 leading-snug">{{ $product->name }}</p>

          {{-- Rating --}}
          <div class="flex items-center gap-1">
            <div class="flex gap-0.5">
              @for($i=1; $i<=5; $i++)
                <i class="bx {{ $i <= round($avgRating) ? 'bxs-star text-amber-400' : 'bx-star text-gray-200' }} text-xs"></i>
              @endfor
            </div>
            <span class="text-[0.65rem] text-[#D99C79]">({{ $reviewCount }})</span>
          </div>

          <p class="text-[#A65005] font-bold text-sm mt-auto">
            Rp {{ number_format($product->price, 0, ',', '.') }}
          </p>

          <div class="flex items-center gap-1.5 bg-green-50 text-green-600 text-[0.65rem] font-medium px-2 py-1 rounded-lg">
            <i class='bx bxs-truck text-xs'></i> Est. 1–3 hari
          </div>
        </div>

      </a>
    @endforeach
  </div>

  {{-- PAGINATION --}}
  @if($products->hasPages())
    <div class="mt-8">{{ $products->links() }}</div>
  @endif
@endif

@endsection