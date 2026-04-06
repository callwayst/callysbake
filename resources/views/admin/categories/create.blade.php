@extends('layouts.admin')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-10 py-8">

    <nav class="flex items-center gap-2 text-sm mb-6">
        <a href="{{ route('admin.categories.index') }}"
           class="text-[#D99C79] hover:text-[#A65005] transition">Categories</a>
        <span class="text-[#D99C79]">/</span>
        <span class="text-[#A65005] font-semibold">Tambah Kategori</span>
    </nav>

    <div class="max-w-lg mx-auto">
        <div class="rounded-t-2xl px-6 py-5 flex items-center justify-between"
             style="background:linear-gradient(135deg,#A65005,#592202)">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="bx bxs-category text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white">Tambah Kategori Baru</h2>
                    <p class="text-white/60 text-xs">Buat kategori produk baru</p>
                </div>
            </div>
            <a href="{{ route('admin.categories.index') }}"
               class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/10
                      hover:bg-white/20 text-white text-sm font-medium transition">
                ← Kembali
            </a>
        </div>

        <div class="bg-white rounded-b-2xl shadow-lg border border-[#F2D4C2] border-t-0 px-6 py-8">
            @if($errors->any())
            <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3 mb-6">
                <ul class="text-sm text-red-500 list-disc list-inside space-y-0.5">
                    @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-5">
                @csrf

                <div class="space-y-1">
                    <label class="block text-sm font-semibold text-[#260101]">
                        Nama Kategori <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name"
                           value="{{ old('name') }}"
                           placeholder="Contoh: Kue, Minuman, Snack..."
                           class="w-full border border-[#D99C79] rounded-xl px-4 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]
                                  transition @error('name') border-red-400 @enderror"
                           required>
                    @error('name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1">
                    <label class="block text-sm font-semibold text-[#260101]">Slug</label>
                    <input type="text" name="slug" id="slugInput"
                           value="{{ old('slug') }}"
                           placeholder="kue-ulang-tahun"
                           class="w-full border border-[#D99C79] rounded-xl px-4 py-2.5 text-sm font-mono
                                  focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]
                                  transition @error('slug') border-red-400 @enderror">
                    <p class="text-xs text-[#D99C79]">Kosongkan untuk generate otomatis dari nama.</p>
                    @error('slug') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="border-t border-[#F2D4C2]"></div>

                <div class="flex items-center justify-end gap-3 pt-1">
                    <a href="{{ route('admin.categories.index') }}"
                       class="px-5 py-2.5 rounded-xl text-sm font-semibold
                              text-[#592202] bg-[#F2D4C2] hover:bg-[#D99C79] transition">
                        Batal
                    </a>
                    <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold
                                   text-white shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all"
                            style="background:linear-gradient(135deg,#A65005,#592202)">
                        <i class="bx bx-plus"></i> Buat Kategori
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
const nameInput = document.querySelector('input[name="name"]');
const slugInput = document.getElementById('slugInput');
nameInput.addEventListener('input', function () {
    if (!slugInput.dataset.manual) {
        slugInput.value = this.value.toLowerCase().trim()
            .replace(/[^a-z0-9\s-]/g, '').replace(/\s+/g, '-');
    }
});
slugInput.addEventListener('input', function () { this.dataset.manual = 'true'; });
</script>
@endsection