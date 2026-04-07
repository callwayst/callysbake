<form action="{{ $user ? route('admin.users.update', $user->id) : route('admin.users.store') }}"
      method="POST" class="space-y-6">
    @csrf
    @if($user) @method('PUT') @endif

    {{-- Error bag --}}
    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-xl p-4">
        <p class="text-sm font-semibold text-red-700 mb-1">Terdapat kesalahan:</p>
        <ul class="text-sm text-red-500 space-y-0.5 list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    {{-- ── SEKSI 1: Informasi Akun ── --}}
    <div>
        <h3 class="text-xs font-bold uppercase tracking-widest text-[#A65005] mb-4 flex items-center gap-2">
            <i class="bx bxs-user-circle text-base"></i> Informasi Akun
        </h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

            {{-- Nama --}}
            <div class="space-y-1">
                <label class="block text-sm font-semibold text-[#260101]">
                    Nama <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name"
                       value="{{ old('name', $user->name ?? '') }}"
                       placeholder="Nama lengkap"
                       class="w-full border border-[#D99C79] rounded-xl px-4 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]
                              transition @error('name') border-red-400 @enderror"
                       required>
                @error('name')<p class="text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Email --}}
            <div class="space-y-1">
                <label class="block text-sm font-semibold text-[#260101]">
                    Email <span class="text-red-500">*</span>
                </label>
                <input type="email" name="email"
                       value="{{ old('email', $user->email ?? '') }}"
                       placeholder="email@contoh.com"
                       class="w-full border border-[#D99C79] rounded-xl px-4 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]
                              transition @error('email') border-red-400 @enderror"
                       required>
                @error('email')<p class="text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Role --}}
            <div class="space-y-1">
                <label class="block text-sm font-semibold text-[#260101]">
                    Role <span class="text-red-500">*</span>
                </label>
                <select name="role"
                        class="w-full border border-[#D99C79] rounded-xl px-4 py-2.5 text-sm
                               focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005] transition"
                        required>
                    <option value="admin"    {{ old('role', $user->role ?? '') === 'admin'    ? 'selected' : '' }}>Admin</option>
                    <option value="user" {{ old('role', $user->role ?? '') === 'user' ? 'selected' : '' }}>User</option>
                </select>
                @error('role')<p class="text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Status --}}
            <div class="space-y-1">
                <label class="block text-sm font-semibold text-[#260101]">
                    Status <span class="text-red-500">*</span>
                </label>
                <select name="status"
                        class="w-full border border-[#D99C79] rounded-xl px-4 py-2.5 text-sm
                               focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005] transition"
                        required>
                    <option value="1" {{ (string) old('status', $user->status ?? 1) === '1' ? 'selected' : '' }}>Active</option>
                    <option value="0" {{ (string) old('status', $user->status ?? 1) === '0' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')<p class="text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

        </div>
    </div>

    <div class="border-t border-[#F2D4C2]"></div>

    {{-- ── SEKSI 2: Password ── --}}
    <div>
        <h3 class="text-xs font-bold uppercase tracking-widest text-[#A65005] mb-4 flex items-center gap-2">
            <i class="bx bxs-lock-alt text-base"></i> Password
        </h3>
        <div class="relative">
            <input type="password" name="password" id="passwordInput"
                   placeholder="{{ $user ? 'Kosongkan jika tidak ingin mengubah' : 'Masukkan password' }}"
                   class="w-full border border-[#D99C79] rounded-xl px-4 py-2.5 pr-12 text-sm
                          focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]
                          transition @error('password') border-red-400 @enderror"
                   {{ !$user ? 'required' : '' }}>
            <button type="button" onclick="togglePassword()"
                    class="absolute right-3 top-2.5 text-[#D99C79] hover:text-[#A65005] transition">
                <i class="bx bx-show text-xl" id="eyeIcon"></i>
            </button>
        </div>
        @if($user)
            <p class="text-xs text-[#D99C79] mt-1">Biarkan kosong jika tidak ingin mengubah password.</p>
        @endif
        @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="border-t border-[#F2D4C2]"></div>

    {{-- ── SEKSI 3: Kontak & Alamat ── --}}
    <div>
        <h3 class="text-xs font-bold uppercase tracking-widest text-[#A65005] mb-4 flex items-center gap-2">
            <i class="bx bxs-phone-call text-base"></i> Kontak & Alamat
        </h3>
        <div class="space-y-4">

            {{-- Phone --}}
            <div class="space-y-1">
                <label class="block text-sm font-semibold text-[#260101]">Nomor HP</label>
                <div class="relative">
                    <span class="absolute left-4 top-2.5 text-sm text-[#D99C79] select-none font-medium">+62</span>
                    <input type="text" name="phone"
                           value="{{ old('phone', $user->phone ?? '') }}"
                           placeholder="812 3456 7890"
                           maxlength="15"
                           class="w-full border border-[#D99C79] rounded-xl pl-14 pr-4 py-2.5 text-sm
                                  focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]
                                  transition @error('phone') border-red-400 @enderror">
                </div>
                @error('phone')<p class="text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

            {{-- Address --}}
            <div class="space-y-1">
                <label class="block text-sm font-semibold text-[#260101]">Alamat</label>
                <textarea name="address" rows="3"
                          placeholder="Jl. Contoh No. 10, Kelurahan, Kecamatan, Kota, Kode Pos"
                          class="w-full border border-[#D99C79] rounded-xl px-4 py-2.5 text-sm
                                 focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]
                                 transition resize-none @error('address') border-red-400 @enderror">{{ old('address', $user->address ?? '') }}</textarea>
                @error('address')<p class="text-xs text-red-500">{{ $message }}</p>@enderror
            </div>

        </div>
    </div>

    <div class="border-t border-[#F2D4C2]"></div>

    {{-- Submit --}}
    <div class="flex items-center justify-end gap-3 pt-1">
        <a href="{{ route('admin.users.index') }}"
           class="px-5 py-2.5 rounded-xl text-sm font-semibold text-[#592202]
                  bg-[#F2D4C2] hover:bg-[#D99C79] transition">
            Batal
        </a>
        <button type="submit"
                class="px-6 py-2.5 rounded-xl text-sm font-bold text-white shadow-lg
                       hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200"
                style="background: linear-gradient(135deg,#A65005,#592202)">
            <i class="bx {{ $user ? 'bxs-save' : 'bxs-user-plus' }} mr-1.5"></i>
            {{ $user ? 'Simpan Perubahan' : 'Buat User' }}
        </button>
    </div>
</form>

<script>
function togglePassword() {
    const input = document.getElementById('passwordInput');
    const icon  = document.getElementById('eyeIcon');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bx-show', 'bx-hide');
    } else {
        input.type = 'password';
        icon.classList.replace('bx-hide', 'bx-show');
    }
}
</script>