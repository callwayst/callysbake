<x-guest-layout>
<div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm p-8">

  <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-5"
       style="background:linear-gradient(135deg,#A65005,#592202)">
    <i class='bx bx-lock-open-alt text-[#F2D4C2] text-2xl'></i>
  </div>

  <h1 class="playfair text-2xl font-bold text-[#592202] mb-1">Lupa Password?</h1>
  <p class="text-sm text-[#D99C79] mb-7">
    Masukkan email kamu dan kami akan kirimkan link untuk reset password.
  </p>

  <x-auth-session-status class="mb-4 text-sm text-green-600 bg-green-50 border border-green-200 rounded-xl px-4 py-3" :status="session('status')" />

  <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
    @csrf
    <div class="field">
      <label>Email</label>
      <input type="email" name="email" value="{{ old('email') }}" placeholder="kamu@email.com" required autofocus>
      @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>
    <button type="submit" class="btn-primary">
      <i class='bx bx-mail-send mr-1'></i> Kirim Link Reset
    </button>
    <p class="text-center text-xs text-[#D99C79]">
      Ingat password?
      <a href="{{ route('login') }}" class="text-[#A65005] font-semibold hover:text-[#592202] transition">
        Kembali masuk
      </a>
    </p>
  </form>

</div>
</x-guest-layout>