<x-guest-layout>
<div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm p-8">

  <h1 class="playfair text-2xl font-bold text-[#592202] mb-1">Buat Akun Baru</h1>
  <p class="text-sm text-[#D99C79] mb-7">Bergabung dan mulai berbelanja di CallysBake</p>

  <form method="POST" action="{{ route('register') }}" class="space-y-5">
    @csrf

    <div class="field">
      <label>Nama Lengkap</label>
      <input type="text" name="name" value="{{ old('name') }}" placeholder="Nama kamu" required autofocus>
      @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="field">
      <label>Email</label>
      <input type="email" name="email" value="{{ old('email') }}" placeholder="kamu@email.com" required>
      @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="grid grid-cols-2 gap-4">
      <div class="field">
        <label>Password</label>
        <input type="password" name="password" placeholder="Min. 8 karakter" required>
        @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
      </div>
      <div class="field">
        <label>Konfirmasi Password</label>
        <input type="password" name="password_confirmation" placeholder="Ulangi password" required>
      </div>
    </div>

    <button type="submit" class="btn-primary">
      <i class='bx bxs-user-plus mr-1'></i> Daftar Sekarang
    </button>

    <p class="text-center text-xs text-[#D99C79]">
      Sudah punya akun?
      <a href="{{ route('login') }}" class="text-[#A65005] font-semibold hover:text-[#592202] transition">
        Masuk di sini
      </a>
    </p>

  </form>
</div>
</x-guest-layout>