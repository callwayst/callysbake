<x-guest-layout>
<div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm p-8">

  <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-5"
       style="background:linear-gradient(135deg,#800000,#592202)">
    <i class='bx bx-shield-alt-2 text-[#F2D4C2] text-2xl'></i>
  </div>

  <h1 class="playfair text-2xl font-bold text-[#592202] mb-1">Area Aman</h1>
  <p class="text-sm text-[#D99C79] mb-7">
    Konfirmasi password kamu sebelum melanjutkan ke area ini.
  </p>

  <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
    @csrf
    <div class="field">
      <label>Password</label>
      <input type="password" name="password" placeholder="••••••••" required>
      @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>
    <button type="submit" class="btn-primary">
      <i class='bx bx-check-shield mr-1'></i> Konfirmasi
    </button>
  </form>

</div>
</x-guest-layout>