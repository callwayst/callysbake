<form method="POST" action="{{ route('admin.profile.info.update') }}" class="space-y-4">
    @csrf
    @method('PATCH')

    @if($errors->has('name') || $errors->has('email'))
    <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3">
        <ul class="text-sm text-red-500 space-y-0.5 list-disc list-inside">
            @error('name')  <li>{{ $message }}</li> @enderror
            @error('email') <li>{{ $message }}</li> @enderror
        </ul>
    </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        {{-- Name --}}
        <div class="space-y-1">
            <label class="block text-xs font-bold uppercase tracking-wide text-[#A65005]">
                Nama
            </label>
            <div class="relative">
                <span class="absolute left-3 top-2.5 text-[#D99C79]">
                    <i class="bx bxs-user text-lg"></i>
                </span>
                <input type="text" name="name"
                       value="{{ old('name', $user->name) }}"
                       placeholder="Nama lengkap"
                       class="w-full border border-[#D99C79] rounded-xl pl-10 pr-4 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]
                              transition @error('name') border-red-400 @enderror">
            </div>
        </div>

        {{-- Email --}}
        <div class="space-y-1">
            <label class="block text-xs font-bold uppercase tracking-wide text-[#A65005]">
                Email
            </label>
            <div class="relative">
                <span class="absolute left-3 top-2.5 text-[#D99C79]">
                    <i class="bx bxs-envelope text-lg"></i>
                </span>
                <input type="email" name="email"
                       value="{{ old('email', $user->email) }}"
                       placeholder="email@contoh.com"
                       class="w-full border border-[#D99C79] rounded-xl pl-10 pr-4 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]
                              transition @error('email') border-red-400 @enderror">
            </div>
        </div>
    </div>

    <div class="flex justify-end pt-1">
        <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold text-white
                       shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                style="background:linear-gradient(135deg,#A65005,#592202)">
            <i class="bx bxs-save"></i> Simpan
        </button>
    </div>
</form>