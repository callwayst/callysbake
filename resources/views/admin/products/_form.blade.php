<form action="{{ $product ? route('admin.products.update', $product->id) : route('admin.products.store') }}"
      method="POST" enctype="multipart/form-data" class="space-y-6">
    @csrf
    @if($product) @method('PUT') @endif

    {{-- Errors --}}
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3">
        <ul class="text-sm text-red-500 list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
    </div>
    @endif

    {{-- ── SEKSI 1: Info Produk ── --}}
    <div>
        <h3 class="text-xs font-bold uppercase tracking-widest text-[#A65005] mb-4 flex items-center gap-2">
            <i class="bx bxs-purchase-tag text-base"></i> Informasi Produk
        </h3>
        <div class="space-y-4">

            {{-- Name --}}
            <div class="space-y-1">
                <label class="block text-sm font-semibold text-[#260101]">
                    Nama Produk <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name"
                       value="{{ old('name', $product->name ?? '') }}"
                       placeholder="Masukkan nama produk"
                       class="w-full border border-[#D99C79] rounded-xl px-4 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]
                              transition @error('name') border-red-400 @enderror"
                       required>
                @error('name') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- Category --}}
            <div class="space-y-1">
                <label class="block text-sm font-semibold text-[#260101]">
                    Kategori <span class="text-red-500">*</span>
                </label>
                <select name="category_id"
                        class="w-full border border-[#D99C79] rounded-xl px-4 py-2.5 text-sm
                               focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005] transition"
                        required>
                    <option value="">-- Pilih Kategori --</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ old('category_id', $product->category_id ?? '') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- Description --}}
            <div class="space-y-1">
                <label class="block text-sm font-semibold text-[#260101]">Deskripsi</label>
                <textarea name="description" rows="4"
                          placeholder="Deskripsikan produk kamu..."
                          class="w-full border border-[#D99C79] rounded-xl px-4 py-2.5 text-sm
                                 focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]
                                 transition resize-none">{{ old('description', $product->description ?? '') }}</textarea>
            </div>

            {{-- Price + Stock --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block text-sm font-semibold text-[#260101]">
                        Harga <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                        <span class="absolute left-4 top-2.5 text-sm text-[#D99C79] font-medium select-none">Rp</span>
                        <input type="number" name="price"
                               value="{{ old('price', $product->price ?? '') }}"
                               placeholder="0"
                               class="w-full border border-[#D99C79] rounded-xl pl-10 pr-4 py-2.5 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]
                                      transition @error('price') border-red-400 @enderror"
                               required>
                    </div>
                    <p class="text-xs text-[#D99C79]">Masukkan angka tanpa titik atau koma</p>
                    @error('price') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1">
                    <label class="block text-sm font-semibold text-[#260101]">
                        Stok <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="stock"
                           value="{{ old('stock', $product->stock ?? '') }}"
                           placeholder="0"
                           class="w-full border border-[#D99C79] rounded-xl px-4 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]
                                  transition @error('stock') border-red-400 @enderror"
                           required>
                    @error('stock') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="border-t border-[#F2D4C2]"></div>

    {{-- ── SEKSI 2: Gambar ── --}}
    <div>
        <h3 class="text-xs font-bold uppercase tracking-widest text-[#A65005] mb-4 flex items-center gap-2">
            <i class="bx bxs-image text-base"></i> Gambar Produk
        </h3>
        <div class="flex flex-col sm:flex-row gap-5 items-start">
            {{-- Preview --}}
            <div class="w-full sm:w-40 h-40 rounded-2xl overflow-hidden border-2 border-dashed border-[#D99C79]
                        bg-[#F2D4C2]/40 flex items-center justify-center flex-shrink-0">
                <img id="preview"
                     src="{{ $product && $product->image ? asset('storage/'.$product->image) : asset('images/no-image.png') }}"
                     alt="Preview"
                     class="w-full h-full object-cover">
            </div>
            <div class="flex-1 space-y-2 w-full">
                <label class="block text-sm font-semibold text-[#260101]">Upload Gambar</label>
                <label for="imageInput"
                       class="flex items-center gap-3 cursor-pointer border-2 border-dashed border-[#D99C79]
                              rounded-xl px-4 py-4 hover:border-[#A65005] hover:bg-[#F2D4C2]/30 transition group">
                    <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                         style="background:linear-gradient(135deg,#F2D4C2,#D99C79)">
                        <i class="bx bx-upload text-[#A65005] text-xl group-hover:scale-110 transition-transform"></i>
                    </div>
                    <div>
                        <p class="text-sm font-semibold text-[#260101]">Klik untuk upload</p>
                        <p class="text-xs text-[#D99C79]">PNG, JPG, JPEG — maks 2MB</p>
                    </div>
                </label>
                <input type="file" name="image" id="imageInput" accept="image/*" class="hidden">
                <p id="fileName" class="text-xs text-[#D99C79] italic">Belum ada file dipilih</p>
            </div>
        </div>
    </div>

    <div class="border-t border-[#F2D4C2]"></div>

    {{-- ── SEKSI 3: Variants ── --}}
    <div>
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-xs font-bold uppercase tracking-widest text-[#A65005] flex items-center gap-2">
                <i class="bx bxs-tag-alt text-base"></i> Variants
            </h3>
            <button type="button" id="addVariant"
                    class="inline-flex items-center gap-1.5 px-4 py-1.5 rounded-xl text-xs font-bold
                           text-white shadow hover:shadow-md hover:-translate-y-0.5 transition-all"
                    style="background:linear-gradient(135deg,#A65005,#592202)">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Variant
            </button>
        </div>

        <div id="variantsList" class="space-y-3">
            @if($product && $product->variants->count())
                @foreach($product->variants as $v)
                <div class="variant-row flex flex-col sm:flex-row gap-3 items-start sm:items-center
                            bg-[#F2D4C2]/30 border border-[#F2D4C2] rounded-xl p-4 hover:border-[#D99C79] transition">
                    <input type="text" name="variants[name][]" value="{{ $v->name }}"
                           placeholder="Nama variant (e.g. Ukuran S)"
                           class="flex-1 border border-[#D99C79] rounded-xl px-3 py-2 text-sm w-full
                                  focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]" required>
                    <div class="relative w-full sm:w-36">
                        <span class="absolute left-3 top-2.5 text-xs text-[#D99C79] select-none">Rp</span>
                        <input type="number" name="variants[price][]" value="{{ $v->price }}"
                               placeholder="Harga"
                               class="w-full border border-[#D99C79] rounded-xl pl-8 pr-3 py-2 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]" required>
                    </div>
                    <input type="number" name="variants[stock][]" value="{{ $v->stock }}"
                           placeholder="Stok"
                           class="w-full sm:w-24 border border-[#D99C79] rounded-xl px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]" required>
                    <button type="button"
                            class="removeVariant flex-shrink-0 w-8 h-8 flex items-center justify-center
                                   rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white
                                   border border-red-200 transition-colors text-lg font-bold">
                        ×
                    </button>
                </div>
                @endforeach
            @else
                <div class="variant-row flex flex-col sm:flex-row gap-3 items-start sm:items-center
                            bg-[#F2D4C2]/30 border border-[#F2D4C2] rounded-xl p-4">
                    <input type="text" name="variants[name][]"
                           placeholder="Nama variant (e.g. Ukuran S)"
                           class="flex-1 border border-[#D99C79] rounded-xl px-3 py-2 text-sm w-full
                                  focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]" required>
                    <div class="relative w-full sm:w-36">
                        <span class="absolute left-3 top-2.5 text-xs text-[#D99C79] select-none">Rp</span>
                        <input type="number" name="variants[price][]"
                               placeholder="Harga"
                               class="w-full border border-[#D99C79] rounded-xl pl-8 pr-3 py-2 text-sm
                                      focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]" required>
                    </div>
                    <input type="number" name="variants[stock][]"
                           placeholder="Stok"
                           class="w-full sm:w-24 border border-[#D99C79] rounded-xl px-3 py-2 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]" required>
                    <button type="button"
                            class="removeVariant flex-shrink-0 w-8 h-8 flex items-center justify-center
                                   rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white
                                   border border-red-200 transition-colors text-lg font-bold">
                        ×
                    </button>
                </div>
            @endif
        </div>
    </div>

    <div class="border-t border-[#F2D4C2]"></div>

    {{-- Submit --}}
    <div class="flex items-center justify-end gap-3 pt-1">
        <a href="{{ route('admin.products.index') }}"
           class="px-5 py-2.5 rounded-xl text-sm font-semibold text-[#592202] bg-[#F2D4C2] hover:bg-[#D99C79] transition">
            Batal
        </a>
        <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold text-white
                       shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200"
                style="background:linear-gradient(135deg,#A65005,#592202)">
            <i class="bx {{ $product ? 'bxs-save' : 'bx-plus' }}"></i>
            {{ $product ? 'Simpan Perubahan' : 'Buat Produk' }}
        </button>
    </div>
</form>

<script>
// Image preview
const imageInput = document.getElementById('imageInput');
const preview    = document.getElementById('preview');
const fileName   = document.getElementById('fileName');

imageInput.addEventListener('change', function () {
    const file = this.files[0];
    if (file) {
        preview.src  = URL.createObjectURL(file);
        fileName.textContent = file.name;
    }
});

// Variant row template
function variantRow() {
    return `
    <div class="variant-row flex flex-col sm:flex-row gap-3 items-start sm:items-center
                bg-[#F2D4C2]/30 border border-[#F2D4C2] rounded-xl p-4 hover:border-[#D99C79] transition">
        <input type="text" name="variants[name][]"
               placeholder="Nama variant (e.g. Ukuran S)"
               class="flex-1 border border-[#D99C79] rounded-xl px-3 py-2 text-sm w-full
                      focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]" required>
        <div class="relative w-full sm:w-36">
            <span class="absolute left-3 top-2.5 text-xs text-[#D99C79] select-none">Rp</span>
            <input type="number" name="variants[price][]"
                   placeholder="Harga"
                   class="w-full border border-[#D99C79] rounded-xl pl-8 pr-3 py-2 text-sm
                          focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]" required>
        </div>
        <input type="number" name="variants[stock][]"
               placeholder="Stok"
               class="w-full sm:w-24 border border-[#D99C79] rounded-xl px-3 py-2 text-sm
                      focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]" required>
        <button type="button"
                class="removeVariant flex-shrink-0 w-8 h-8 flex items-center justify-center
                       rounded-xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white
                       border border-red-200 transition-colors text-lg font-bold">
            ×
        </button>
    </div>`;
}

document.getElementById('addVariant').addEventListener('click', function () {
    document.getElementById('variantsList').insertAdjacentHTML('beforeend', variantRow());
});

document.addEventListener('click', function (e) {
    if (e.target.classList.contains('removeVariant')) {
        const rows = document.querySelectorAll('.variant-row');
        if (rows.length > 1) {
            e.target.closest('.variant-row').remove();
        }
    }
});
</script>