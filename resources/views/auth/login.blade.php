<x-guest-layout>
<div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm p-8">

  <h1 class="playfair text-2xl font-bold text-[#592202] mb-1">Selamat Datang!</h1>
  <p class="text-sm text-[#D99C79] mb-7">Masuk ke akun CallysBake kamu</p>

  <x-auth-session-status class="mb-4 text-sm text-green-600 bg-green-50 border border-green-200 rounded-xl px-4 py-3" :status="session('status')" />
    @if(session('success'))
    <div class="mb-4 p-3 rounded-lg bg-green-50 border border-green-300 text-green-700 text-sm">
        {{ session('success') }}
    </div>
    @endif
  <form method="POST" action="{{ route('login') }}" class="space-y-5">
    @csrf

    <div class="field">
      <label>Email</label>
      <input type="email" name="email" value="{{ old('email') }}" placeholder="kamu@email.com" required autofocus>
      @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="field">
      <label>Password</label>
      <input type="password" name="password" placeholder="••••••••" required>
      @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="flex items-center justify-between">
      <label class="flex items-center gap-2 cursor-pointer">
        <input type="checkbox" name="remember"
               class="w-4 h-4 rounded border-[#D99C79] text-[#A65005] focus:ring-[#D99C79]">
        <span class="text-xs text-[#592202]">Ingat saya</span>
      </label>
      @if(Route::has('password.request'))
        <a href="{{ route('password.request') }}"
           class="text-xs text-[#A65005] hover:text-[#592202] transition font-medium">
          Lupa password?
        </a>
      @endif
    </div>

    <button type="submit" class="btn-primary">
      <i class='bx bx-log-in mr-1'></i> Masuk
    </button>

    <p class="text-center text-xs text-[#D99C79]">
      Belum punya akun?
      <a href="{{ route('register') }}" class="text-[#A65005] font-semibold hover:text-[#592202] transition">
        Daftar sekarang
      </a>
    </p>

  </form>
</div>
</x-guest-layout>