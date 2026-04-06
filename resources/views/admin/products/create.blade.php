@extends('layouts.admin')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-10 py-4">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm mb-6">
        <a href="{{ route('admin.products.index') }}"
           class="text-[#D99C79] hover:text-[#A65005] transition">Products</a>
        <span class="text-[#D99C79]">/</span>
        <span class="text-[#A65005] font-semibold">{{ $product ? 'Edit Product' : 'Add Product' }}</span>
    </nav>

    <div class="max-w-3xl mx-auto">
        {{-- Card Header --}}
        <div class="rounded-t-2xl px-6 py-5 flex items-center justify-between"
             style="background:linear-gradient(135deg,#A65005,#592202)">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="bx {{ $product ? 'bxs-edit' : 'bx-store' }} text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white">
                        {{ $product ? 'Edit Product' : 'Tambah Product Baru' }}
                    </h2>
                    <p class="text-white/60 text-xs">
                        {{ $product ? 'Perbarui informasi produk' : 'Isi detail produk baru' }}
                    </p>
                </div>
            </div>
            <a href="{{ route('admin.products.index') }}"
               class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/10
                      hover:bg-white/20 text-white text-sm font-medium transition">
                ← Kembali
            </a>
        </div>

        {{-- Card Body --}}
        <div class="bg-white rounded-b-2xl shadow-lg border border-[#F2D4C2] border-t-0 px-6 py-8">
            @include('admin.products._form')
        </div>
    </div>
</div>
@endsection