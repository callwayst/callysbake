@php $isEdit = isset($voucher); @endphp

<form action="{{ $isEdit ? route('admin.vouchers.update', $voucher->id) : route('admin.vouchers.store') }}"
      method="POST" class="space-y-6">
    @csrf
    @if($isEdit) @method('PUT') @endif

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3">
        <ul class="text-sm text-red-500 list-disc list-inside space-y-0.5">
            @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
    </div>
    @endif

    {{-- ── SEKSI 1: Info Voucher ── --}}
    <div>
        <h3 class="text-xs font-bold uppercase tracking-widest text-[#A65005] mb-4 flex items-center gap-2">
            <i class="bx bxs-purchase-tag text-base"></i> Informasi Voucher
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            {{-- Code --}}
            <div class="space-y-1">
                <label class="block text-sm font-semibold text-[#260101]">
                    Kode <span class="text-red-500">*</span>
                </label>
                <input type="text" name="code"
                       value="{{ old('code', $voucher->code ?? '') }}"
                       placeholder="PROMO10"
                       class="w-full border border-[#D99C79] rounded-xl px-4 py-2.5 text-sm uppercase tracking-widest
                              focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]
                              transition @error('code') border-red-400 @enderror"
                       required>
                @error('code') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- Type --}}
            <div class="space-y-1">
                <label class="block text-sm font-semibold text-[#260101]">
                    Tipe <span class="text-red-500">*</span>
                </label>
                <select name="type"
                        class="w-full border border-[#D99C79] rounded-xl px-4 py-2.5 text-sm
                               focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005] transition"
                        required>
                    <option value="percent" {{ old('type', $voucher->type ?? '') == 'percent' ? 'selected' : '' }}>
                        Persen (%)
                    </option>
                    <option value="fixed" {{ old('type', $voucher->type ?? '') == 'fixed' ? 'selected' : '' }}>
                        Nominal (Rp)
                    </option>
                </select>
            </div>

            {{-- Value --}}
            <div class="space-y-1">
                <label class="block text-sm font-semibold text-[#260101]">
                    Nilai <span class="text-red-500">*</span>
                </label>
                <input type="number" name="value"
                       value="{{ old('value', $voucher->value ?? '') }}"
                       placeholder="10"
                       class="w-full border border-[#D99C79] rounded-xl px-4 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]
                              transition @error('value') border-red-400 @enderror"
                       required>
                @error('value') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- Min Purchase --}}
            <div class="space-y-1">
                <label class="block text-sm font-semibold text-[#260101]">Min. Pembelian</label>
                <div class="relative">
                    <span class="absolute left-4 top-2.5 text-sm text-[#D99C79] select-none">Rp</span>
                    <input type="number" name="min_purchase"
                           value="{{ old('min_purchase', $voucher->min_purchase ?? '') }}"
                           placeholder="0"
                           class="w-full border border-[#D99C79] rounded-xl pl-10 pr-4 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005] transition">
                </div>
            </div>

            {{-- Max Discount --}}
            <div class="space-y-1">
                <label class="block text-sm font-semibold text-[#260101]">Maks. Diskon</label>
                <div class="relative">
                    <span class="absolute left-4 top-2.5 text-sm text-[#D99C79] select-none">Rp</span>
                    <input type="number" name="max_discount"
                           value="{{ old('max_discount', $voucher->max_discount ?? '') }}"
                           placeholder="0"
                           class="w-full border border-[#D99C79] rounded-xl pl-10 pr-4 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005] transition">
                </div>
            </div>

            {{-- Usage Limit --}}
            <div class="space-y-1">
                <label class="block text-sm font-semibold text-[#260101]">Batas Penggunaan</label>
                <input type="number" name="usage_limit"
                       value="{{ old('usage_limit', $voucher->usage_limit ?? '') }}"
                       placeholder="Kosongkan = tidak terbatas"
                       class="w-full border border-[#D99C79] rounded-xl px-4 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005] transition">
            </div>

            {{-- Expiry Date --}}
            <div class="space-y-1">
                <label class="block text-sm font-semibold text-[#260101]">
                    Tanggal Kedaluwarsa <span class="text-red-500">*</span>
                </label>
                <input type="date" name="expired_at"
                       value="{{ old('expired_at', isset($voucher) ? $voucher->expired_at->format('Y-m-d') : '') }}"
                       class="w-full border border-[#D99C79] rounded-xl px-4 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]
                              transition @error('expired_at') border-red-400 @enderror"
                       required>
                @error('expired_at') <p class="text-xs text-red-500">{{ $message }}</p> @enderror
            </div>

            {{-- Active toggle --}}
            <div class="flex items-center gap-3 sm:col-span-2 bg-[#F2D4C2]/30 rounded-xl px-4 py-3 border border-[#F2D4C2]">
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_active" value="1"
                           {{ old('is_active', $voucher->is_active ?? true) ? 'checked' : '' }}
                           class="sr-only peer">
                    <div class="w-11 h-6 bg-gray-200 rounded-full peer
                                peer-checked:bg-[#A65005] transition-colors
                                after:content-[''] after:absolute after:top-[2px] after:left-[2px]
                                after:bg-white after:rounded-full after:h-5 after:w-5 after:transition-all
                                peer-checked:after:translate-x-5"></div>
                </label>
                <div>
                    <p class="text-sm font-semibold text-[#260101]">Status Aktif</p>
                    <p class="text-xs text-[#D99C79]">Voucher bisa digunakan jika aktif</p>
                </div>
            </div>
        </div>
    </div>

    <div class="border-t border-[#F2D4C2]"></div>

    {{-- Submit --}}
    <div class="flex items-center justify-end gap-3 pt-1">
        <a href="{{ route('admin.vouchers.index') }}"
           class="px-5 py-2.5 rounded-xl text-sm font-semibold text-[#592202] bg-[#F2D4C2] hover:bg-[#D99C79] transition">
            Batal
        </a>
        <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold text-white
                       shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200"
                style="background:linear-gradient(135deg,#A65005,#592202)">
            <i class="bx {{ $isEdit ? 'bxs-save' : 'bxs-coupon' }}"></i>
            {{ $isEdit ? 'Simpan Perubahan' : 'Buat Voucher' }}
        </button>
    </div>
</form>