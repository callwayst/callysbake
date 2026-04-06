<x-guest-layout>
<div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm p-8">

  <div class="w-14 h-14 rounded-2xl flex items-center justify-center mb-5"
       style="background:linear-gradient(135deg,#A65005,#592202)">
    <i class='bx bx-key text-[#F2D4C2] text-2xl'></i>
  </div>

  <h1 class="playfair text-2xl font-bold text-[#592202] mb-1">Reset Password</h1>
  <p class="text-sm text-[#D99C79] mb-7">Buat password baru untuk akun kamu.</p>

  <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
    @csrf
    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <div class="field">
      <label>Email</label>
      <input type="email" name="email" value="{{ old('email', $request->email) }}" required>
      @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="field">
      <label>Password Baru</label>
      <input type="password" name="password" placeholder="Min. 8 karakter" required>
      @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
    </div>

    <div class="field">
      <label>Konfirmasi Password Baru</label>
      <input type="password" name="password_confirmation" placeholder="Ulangi password baru" required>
    </div>

    <button type="submit" class="btn-primary">
      <i class='bx bx-check mr-1'></i> Reset Password
    </button>
  </form>

</div>
</x-guest-layout>