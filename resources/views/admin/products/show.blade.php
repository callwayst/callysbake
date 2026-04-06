@extends('layouts.admin')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-10 py-4">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm mb-6">
        <a href="{{ route('admin.products.index') }}"
           class="text-[#D99C79] hover:text-[#A65005] transition">Products</a>
        <span class="text-[#D99C79]">/</span>
        <span class="text-[#A65005] font-semibold">Detail</span>
    </nav>

    <div class="max-w-4xl mx-auto bg-white rounded-2xl border border-[#F2D4C2] shadow-sm overflow-hidden">

        {{-- Top bar --}}
        <div class="h-2" style="background:linear-gradient(90deg,#A65005,#592202,#800000)"></div>

        {{-- Header --}}
        <div class="px-6 py-4 border-b border-[#F2D4C2] flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl"
                      style="background:linear-gradient(135deg,#A65005,#592202)">
                    <i class='bx bx-store text-white text-lg'></i>
                </span>
                <div>
                    <h2 class="text-xl font-bold text-[#260101]">Detail Product</h2>
                    <p class="text-xs text-[#D99C79]">Informasi lengkap produk</p>
                </div>
            </div>
            <a href="{{ route('admin.products.index') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 rounded-xl text-sm font-semibold
                      text-[#592202] bg-[#F2D4C2] hover:bg-[#D99C79] transition">
                ← Kembali
            </a>
        </div>

        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-6">

            {{-- Image --}}
            <div class="rounded-2xl overflow-hidden border border-[#F2D4C2] aspect-square bg-[#F2D4C2]">
                <img src="{{ $product->image ? asset('storage/'.$product->image) : asset('images/no-image.png') }}"
                     alt="{{ $product->name }}"
                     class="w-full h-full object-cover">
            </div>

            {{-- Info --}}
            <div class="flex flex-col gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-[#260101]">{{ $product->name }}</h1>
                    <span class="inline-block mt-1 text-xs font-semibold px-3 py-1 rounded-full bg-[#F2D4C2] text-[#A65005]">
                        {{ $product->category->name ?? '-' }}
                    </span>
                </div>

                <p class="text-sm text-gray-600 leading-relaxed">
                    {{ $product->description ?? 'Tidak ada deskripsi.' }}
                </p>

                <div class="grid grid-cols-2 gap-3">
                    <div class="bg-[#F2D4C2]/50 rounded-xl p-3 border border-[#F2D4C2]">
                        <p class="text-xs text-[#D99C79] mb-0.5">Harga</p>
                        <p class="font-bold text-[#A65005]">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-[#F2D4C2]/50 rounded-xl p-3 border border-[#F2D4C2]">
                        <p class="text-xs text-[#D99C79] mb-0.5">Stok</p>
                        @php $totalStock = $product->variants->sum('stock'); @endphp
                        <p class="font-bold {{ $totalStock <= 5 ? 'text-red-600' : 'text-[#592202]' }}">
                            {{ $totalStock }} pcs
                            @if($totalStock <= 5)
                                <span class="text-xs font-normal text-red-400">(Hampir habis)</span>
                            @endif
                        </p>
                    </div>
                </div>

                {{-- Variants --}}
                <div>
                    <h3 class="text-xs font-bold uppercase tracking-widest text-[#A65005] mb-3 flex items-center gap-2">
                        <i class="bx bxs-tag text-sm"></i> Variants
                    </h3>
                    @if($product->variants->count())
                    <div class="space-y-2">
                        @foreach($product->variants as $v)
                        <div class="flex items-center justify-between bg-[#F2D4C2]/40 rounded-xl px-4 py-3
                                    border border-[#F2D4C2] hover:border-[#D99C79] transition">
                            <div>
                                <p class="font-semibold text-sm text-[#260101]">{{ $v->name }}</p>
                                <p class="text-xs text-[#A65005]">Rp {{ number_format($v->price, 0, ',', '.') }}</p>
                            </div>
                            <span class="text-xs font-bold px-2.5 py-1 rounded-full
                                {{ $v->stock <= 5 ? 'bg-red-100 text-red-600' : 'bg-emerald-100 text-emerald-700' }}">
                                {{ $v->stock }} pcs
                            </span>
                        </div>
                        @endforeach
                    </div>
                    @else
                        <p class="text-sm text-[#D99C79] italic">Tidak ada variant</p>
                    @endif
                </div>

                {{-- Buttons --}}
                <div class="flex gap-2 mt-auto pt-2">
                    <a href="{{ route('admin.products.edit', $product->id) }}"
                       class="flex-1 text-center py-2.5 rounded-xl text-sm font-bold text-white
                              shadow hover:shadow-md hover:-translate-y-0.5 transition-all"
                       style="background:linear-gradient(135deg,#A65005,#592202)">
                        <i class="bx bxs-edit mr-1"></i> Edit
                    </a>
                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST"
                          onsubmit="return confirm('Yakin hapus produk ini?')" class="flex-1">
                        @csrf @method('DELETE')
                        <button type="submit"
                                class="w-full py-2.5 rounded-xl text-sm font-bold
                                       bg-red-50 text-red-600 hover:bg-red-600 hover:text-white
                                       border border-red-200 transition-colors">
                            <i class="bx bxs-trash mr-1"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection