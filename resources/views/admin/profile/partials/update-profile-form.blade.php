<form method="POST" action="{{ route('admin.profile.info.update') }}" class="space-y-4">
    @csrf
    @method('PATCH')

    @if($errors->has('phone') || $errors->has('address') || $errors->has('bio'))
    <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3">
        <ul class="text-sm text-red-500 space-y-0.5 list-disc list-inside">
            @error('phone')   <li>{{ $message }}</li> @enderror
            @error('address') <li>{{ $message }}</li> @enderror
            @error('bio')     <li>{{ $message }}</li> @enderror
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        {{-- Phone --}}
        <div class="space-y-1 sm:col-span-2 md:col-span-1">
            <label class="block text-xs font-bold uppercase tracking-wide text-[#A65005]">
                Nomor HP
            </label>
            <div class="relative">
                <span class="absolute left-3 top-2.5 text-[#D99C79]">
                    <i class="bx bxs-phone text-lg"></i>
                </span>
                <input type="text" name="phone"
                       value="{{ old('phone', $user->phone) }}"
                       placeholder="0812 3456 7890"
                       maxlength="20"
                       class="w-full border border-[#D99C79] rounded-xl pl-10 pr-4 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]
                              transition @error('phone') border-red-400 @enderror">
            </div>
        </div>

        {{-- Address --}}
        <div class="space-y-1 sm:col-span-2">
            <label class="block text-xs font-bold uppercase tracking-wide text-[#A65005]">
                Alamat
            </label>
            <div class="relative">
                <span class="absolute left-3 top-3 text-[#D99C79]">
                    <i class="bx bxs-map text-lg"></i>
                </span>
                <textarea name="address" rows="2"
                          placeholder="Jl. Contoh No. 10, Kota..."
                          class="w-full border border-[#D99C79] rounded-xl pl-10 pr-4 py-2.5 text-sm
                                 focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]
                                 transition resize-none @error('address') border-red-400 @enderror">{{ old('address', $user->address) }}</textarea>
            </div>
        </div>

        {{-- Bio --}}
        <div class="space-y-1 sm:col-span-2">
            <label class="block text-xs font-bold uppercase tracking-wide text-[#A65005]">
                Bio
            </label>
            <div class="relative">
                <span class="absolute left-3 top-3 text-[#D99C79]">
                    <i class="bx bxs-edit-alt text-lg"></i>
                </span>
                <textarea name="bio" rows="3"
                          placeholder="Ceritakan sedikit tentang diri kamu..."
                          class="w-full border border-[#D99C79] rounded-xl pl-10 pr-4 py-2.5 text-sm
                                 focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]
                                 transition resize-none @error('bio') border-red-400 @enderror">{{ old('bio', $user->bio) }}</textarea>
            </div>
        </div>
    </div>

    <div class="flex justify-end pt-1">
        <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold text-white
                       shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                style="background:linear-gradient(135deg,#D99C79,#A65005)">
            <i class="bx bxs-save"></i> Simpan Detail
        </button>
    </div>
</form>