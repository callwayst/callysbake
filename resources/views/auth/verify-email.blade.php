<x-guest-layout>
<div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm p-8 text-center">

  <div class="w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-5"
       style="background:linear-gradient(135deg,#F2D4C2,#D99C79)">
    <i class='bx bx-envelope text-[#A65005] text-3xl'></i>
  </div>

  <h1 class="playfair text-2xl font-bold text-[#592202] mb-2">Verifikasi Email</h1>
  <p class="text-sm text-[#D99C79] leading-relaxed mb-6">
    Terima kasih sudah mendaftar! Cek email kamu dan klik link verifikasi yang sudah kami kirimkan.
  </p>

  @if(session('status') == 'verification-link-sent')
    <div class="mb-5 text-sm text-green-600 bg-green-50 border border-green-200 rounded-xl px-4 py-3">
      <i class='bx bx-check-circle mr-1'></i>
      Link verifikasi baru sudah dikirim ke email kamu.
    </div>
  @endif

  <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
    @csrf
    <button type="submit" class="btn-primary">
      <i class='bx bx-refresh mr-1'></i> Kirim Ulang Email Verifikasi
    </button>
  </form>

  <form method="POST" action="{{ route('logout') }}">
    @csrf
    <button type="submit"
            class="text-xs text-[#D99C79] hover:text-[#A65005] transition font-medium">
      <i class='bx bx-log-out mr-1'></i> Keluar dari akun
    </button>
  </form>

</div>
</x-guest-layout>