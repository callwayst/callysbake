<form method="POST" action="{{ route('admin.profile.password.update') }}" class="space-y-4">
    @csrf
    @method('PATCH')

    @if($errors->has('current_password') || $errors->has('password'))
    <div class="bg-red-50 border border-red-200 rounded-xl px-4 py-3">
        <ul class="text-sm text-red-500 space-y-0.5 list-disc list-inside">
            @error('current_password') <li>{{ $message }}</li> @enderror
            @error('password')         <li>{{ $message }}</li> @enderror
        </ul>
    </div>
    @endif

    <div class="space-y-3">
        {{-- Current Password --}}
        <div class="space-y-1">
            <label class="block text-xs font-bold uppercase tracking-wide text-[#A65005]">
                Password Saat Ini
            </label>
            <div class="relative">
                <span class="absolute left-3 top-2.5 text-[#D99C79]">
                    <i class="bx bxs-lock text-lg"></i>
                </span>
                <input type="password" name="current_password"
                       placeholder="Password saat ini"
                       class="w-full border border-[#D99C79] rounded-xl pl-10 pr-10 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]
                              transition @error('current_password') border-red-400 @enderror">
                <button type="button" onclick="togglePw('current_password','eye1')"
                        class="absolute right-3 top-2.5 text-[#D99C79] hover:text-[#A65005] transition">
                    <i id="eye1" class="bx bx-show text-xl"></i>
                </button>
            </div>
        </div>

        {{-- New Password --}}
        <div class="space-y-1">
            <label class="block text-xs font-bold uppercase tracking-wide text-[#A65005]">
                Password Baru
            </label>
            <div class="relative">
                <span class="absolute left-3 top-2.5 text-[#D99C79]">
                    <i class="bx bxs-lock-alt text-lg"></i>
                </span>
                <input type="password" name="password"
                       placeholder="Password baru (min. 6 karakter)"
                       class="w-full border border-[#D99C79] rounded-xl pl-10 pr-10 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]
                              transition @error('password') border-red-400 @enderror">
                <button type="button" onclick="togglePw('password','eye2')"
                        class="absolute right-3 top-2.5 text-[#D99C79] hover:text-[#A65005] transition">
                    <i id="eye2" class="bx bx-show text-xl"></i>
                </button>
            </div>
        </div>

        {{-- Confirm Password --}}
        <div class="space-y-1">
            <label class="block text-xs font-bold uppercase tracking-wide text-[#A65005]">
                Konfirmasi Password
            </label>
            <div class="relative">
                <span class="absolute left-3 top-2.5 text-[#D99C79]">
                    <i class="bx bxs-lock-alt text-lg"></i>
                </span>
                <input type="password" name="password_confirmation"
                       placeholder="Ulangi password baru"
                       class="w-full border border-[#D99C79] rounded-xl pl-10 pr-10 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]
                              transition">
                <button type="button" onclick="togglePw('password_confirmation','eye3')"
                        class="absolute right-3 top-2.5 text-[#D99C79] hover:text-[#A65005] transition">
                    <i id="eye3" class="bx bx-show text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <div class="flex justify-end pt-1">
        <button type="submit"
                class="inline-flex items-center gap-2 px-6 py-2.5 rounded-xl text-sm font-bold text-white
                       shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-200"
                style="background:linear-gradient(135deg,#800000,#260101)">
            <i class="bx bxs-key"></i> Update Password
        </button>
    </div>
</form>

<script>
function togglePw(inputId, iconId) {
    const input = document.getElementById(inputId) || document.querySelector(`[name="${inputId}"]`);
    const icon  = document.getElementById(iconId);
    if (!input) return;
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bx-show', 'bx-hide');
    } else {
        input.type = 'password';
        icon.classList.replace('bx-hide', 'bx-show');
    }
}
</script>