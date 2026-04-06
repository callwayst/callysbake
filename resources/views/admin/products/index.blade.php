@extends('layouts.admin')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-10 py-4 space-y-4">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-[#260101] flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-11 h-11 rounded-2xl mt-4"
                      style="background:linear-gradient(135deg,#A65005,#592202)">
                    <i class='bx bx-store text-white text-2xl'></i>
                </span>
                Manage Products
            </h1>
            <p class="text-sm text-[#D99C79] mt-1 ml-14">Kelola semua produk toko kamu</p>
        </div>
        <a href="{{ route('admin.products.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white font-semibold
                  shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200"
           style="background:linear-gradient(135deg,#A65005,#592202)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add Product
        </a>
    </div>

    {{-- FILTER BAR --}}
    <form method="GET"
          class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm p-4
                 flex flex-col md:flex-row gap-3 items-center">
        <div class="relative flex-1 w-full">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama produk..."
                   class="w-full border border-[#D99C79] rounded-xl pl-10 pr-4 py-2.5 text-sm
                          focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005] transition"
                   onkeydown="if(event.key==='Enter'){this.form.submit()}">
            <svg class="absolute left-3 top-3 w-4 h-4 text-[#D99C79]"
                 fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
            </svg>
        </div>

        <select name="category"
                class="border border-[#D99C79] rounded-xl px-4 py-2.5 text-sm w-full md:w-52
                       focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]"
                onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>

        <div class="flex gap-2 w-full md:w-auto">
            <button type="submit"
                    class="flex-1 md:flex-none px-5 py-2.5 rounded-xl text-white text-sm font-semibold
                           shadow hover:shadow-md transition"
                    style="background:#A65005">
                Filter
            </button>
            <a href="{{ route('admin.products.index') }}"
               class="flex-1 md:flex-none px-5 py-2.5 rounded-xl text-sm font-semibold text-center
                      border border-[#D99C79] text-[#A65005] hover:bg-[#F2D4C2] transition">
                Reset
            </a>
        </div>
    </form>

    {{-- PRODUCT GRID --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
        @forelse($products as $p)
        <div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm
                    hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col">

            {{-- Image --}}
            <div class="w-full aspect-square overflow-hidden bg-[#F2D4C2] relative group">
                <img src="{{ $p->image ? asset('storage/'.$p->image) : asset('images/no-image.png') }}"
                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                     alt="{{ $p->name }}">
                {{-- Stock badge --}}
                <span class="absolute top-2 left-2 text-[10px] font-bold px-2 py-0.5 rounded-full
                             {{ $p->stock <= 5 ? 'bg-red-500 text-white' : 'bg-white/90 text-[#592202]' }}">
                    Stok {{ $p->final_stock }}
                </span>
            </div>

            {{-- Body --}}
            <div class="p-3 flex flex-col flex-1 gap-1">
                <h3 class="font-bold text-xs sm:text-sm line-clamp-2 text-[#260101] leading-tight">
                    {{ $p->name }}
                </h3>
                <p class="text-[10px] sm:text-xs text-[#D99C79]">{{ $p->category->name }}</p>
                <p class="font-bold text-[#A65005] text-xs sm:text-sm mt-auto">
                    Rp {{ number_format($p->price, 0, ',', '.') }}
                </p>

                {{-- Actions --}}
                <div class="flex gap-1.5 mt-2">
                    <a href="{{ route('admin.products.show', $p->id) }}"
                       class="flex-1 text-[10px] sm:text-xs text-center py-1.5 rounded-lg font-semibold
                              text-[#592202] bg-[#F2D4C2] hover:bg-[#D99C79] transition">
                        Detail
                    </a>
                    <a href="{{ route('admin.products.edit', $p->id) }}"
                       class="flex-1 text-[10px] sm:text-xs text-center py-1.5 rounded-lg font-semibold
                              text-white transition"
                       style="background:#592202">
                        Edit
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-20">
            <i class="bx bx-package text-6xl text-[#F2D4C2] block mb-3"></i>
            <p class="text-[#D99C79] font-semibold">Tidak ada produk ditemukan</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div>{{ $products->links() }}</div>

</div>
@endsection