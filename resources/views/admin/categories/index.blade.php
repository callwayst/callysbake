@extends('layouts.admin')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-10 py-8 space-y-8">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-[#260101] flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-11 h-11 rounded-2xl mt-3"
                      style="background:linear-gradient(135deg,#A65005,#592202)">
                    <i class='bx bxs-category text-white text-2xl'></i>
                </span>
                Manage Categories
            </h1>
            <p class="text-sm text-[#D99C79] mt-1 ml-14">Kelola kategori produk toko</p>
        </div>
        <a href="{{ route('admin.categories.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white font-semibold
                  shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200"
           style="background:linear-gradient(135deg,#A65005,#592202)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add Category
        </a>
    </div>

    {{-- ALERTS --}}
    @if(session('success'))
    <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-4 py-3 text-sm">
        <i class="bx bxs-check-circle text-lg"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 text-sm">
        <i class="bx bxs-error-circle text-lg"></i> {{ session('error') }}
    </div>
    @endif

    {{-- DESKTOP TABLE --}}
    <div class="hidden md:block bg-white rounded-2xl border border-[#F2D4C2] shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-[#F2D4C2]">
            <thead>
                <tr style="background:linear-gradient(135deg,#A65005,#592202)">
                    <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-white w-12">#</th>
                    <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-white">Nama</th>
                    <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-white">Slug</th>
                    <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-white">Produk</th>
                    <th class="px-5 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-white">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-[#F2D4C2]">
                @forelse($categories as $category)
                <tr class="hover:bg-[#F2D4C2]/30 transition">
                    <td class="px-5 py-3.5 text-sm text-[#D99C79]">{{ $loop->iteration }}</td>
                    <td class="px-5 py-3.5">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                                 style="background:linear-gradient(135deg,#F2D4C2,#D99C79)">
                                <i class="bx bxs-category text-[#A65005] text-sm"></i>
                            </div>
                            <span class="font-semibold text-sm text-[#260101]">{{ $category->name }}</span>
                        </div>
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="text-xs font-mono bg-[#F2D4C2]/60 text-[#592202] px-2.5 py-1 rounded-lg">
                            {{ $category->slug }}
                        </span>
                    </td>
                    <td class="px-5 py-3.5">
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full bg-[#F2D4C2] text-[#A65005]">
                            {{ $category->products_count }} produk
                        </span>
                    </td>
                    <td class="px-5 py-3.5">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.categories.edit', $category->id) }}"
                               class="w-8 h-8 flex items-center justify-center rounded-lg
                                      text-[#F2D4C2] hover:opacity-80 transition"
                               style="background:#592202" title="Edit">
                                <i class='bx bx-edit text-base'></i>
                            </a>
                            <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus kategori {{ $category->name }}?')">
                                @csrf @method('DELETE')
                                <button class="w-8 h-8 flex items-center justify-center rounded-lg
                                               bg-red-50 text-red-500 hover:bg-red-500 hover:text-white
                                               border border-red-200 transition" title="Hapus">
                                    <i class='bx bx-trash text-base'></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-16 text-center">
                        <i class="bx bxs-category text-5xl text-[#F2D4C2] block mb-2"></i>
                        <p class="text-[#D99C79]">Belum ada kategori</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MOBILE CARDS --}}
    <div class="block md:hidden space-y-3">
        @forelse($categories as $category)
        <div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm overflow-hidden">
            <div class="h-1" style="background:linear-gradient(90deg,#A65005,#592202)"></div>
            <div class="p-4 flex items-center justify-between gap-3">
                <div class="flex items-center gap-3 min-w-0">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                         style="background:linear-gradient(135deg,#F2D4C2,#D99C79)">
                        <i class="bx bxs-category text-[#A65005] text-lg"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="font-bold text-sm text-[#260101] truncate">{{ $category->name }}</p>
                        <p class="text-xs font-mono text-[#D99C79] truncate">{{ $category->slug }}</p>
                        <span class="text-xs font-semibold px-2 py-0.5 rounded-full bg-[#F2D4C2] text-[#A65005] mt-1 inline-block">
                            {{ $category->products_count }} produk
                        </span>
                    </div>
                </div>
                <div class="flex gap-2 flex-shrink-0">
                    <a href="{{ route('admin.categories.edit', $category->id) }}"
                       class="w-9 h-9 flex items-center justify-center rounded-xl text-[#F2D4C2] transition"
                       style="background:#592202">
                        <i class='bx bx-edit text-base'></i>
                    </a>
                    <form action="{{ route('admin.categories.destroy', $category->id) }}" method="POST"
                          onsubmit="return confirm('Hapus kategori ini?')">
                        @csrf @method('DELETE')
                        <button class="w-9 h-9 flex items-center justify-center rounded-xl
                                       bg-red-50 text-red-500 border border-red-200
                                       hover:bg-red-500 hover:text-white transition">
                            <i class='bx bx-trash text-base'></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-16">
            <i class="bx bxs-category text-5xl text-[#F2D4C2] block mb-2"></i>
            <p class="text-[#D99C79]">Belum ada kategori</p>
        </div>
        @endforelse
    </div>

</div>
@endsection