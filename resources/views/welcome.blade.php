<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CallysBake — Premium Baking Ingredients & Tools</title>
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=DM+Sans:wght@300;400;500;600&family=Playfair+Display:wght@500;700&display=swap" rel="stylesheet">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  @vite(['resources/css/app.css','resources/js/app.js'])
  <style>
    body { font-family: 'DM Sans', sans-serif; }
    .dancing { font-family: 'Dancing Script', cursive; }
    .playfair { font-family: 'Playfair Display', serif; }
    @keyframes float {
      0%, 100% { transform: translateY(0px); }
      50% { transform: translateY(-12px); }
    }
    @keyframes fadeUp {
      from { opacity: 0; transform: translateY(24px); }
      to   { opacity: 1; transform: translateY(0); }
    }
    .float { animation: float 4s ease-in-out infinite; }
    .fade-up { animation: fadeUp 0.7s ease forwards; opacity: 0; }
    .fade-up-1 { animation-delay: 0.1s; }
    .fade-up-2 { animation-delay: 0.25s; }
    .fade-up-3 { animation-delay: 0.4s; }
    .fade-up-4 { animation-delay: 0.55s; }
  </style>
</head>
<body class="bg-[#F9EDE3] text-[#260101] min-h-screen">

  {{-- NAVBAR --}}
  <nav class="bg-[#A65005] sticky top-0 z-50 shadow-lg">
    <div class="max-w-6xl mx-auto px-6 h-16 flex items-center justify-between">
      <div class="flex items-center gap-2">
        <div class="w-9 h-9 rounded-xl bg-[#F2D4C2] flex items-center justify-center shadow-sm">
          <i class='bx bxs-cake text-[#A65005] text-xl'></i>
        </div>
        <span class="dancing text-[#F2D4C2] text-2xl">CallysBake</span>
      </div>
      <div class="flex items-center gap-2">
        @auth
          <a href="{{ route('dashboard') }}"
             class="px-4 py-2 rounded-xl text-sm font-semibold text-white bg-white/20 hover:bg-white/30 transition">
            Dashboard
          </a>
        @else
          <a href="{{ route('login') }}"
             class="px-4 py-2 rounded-xl text-sm font-medium text-[#F2D4C2]/90 hover:text-white hover:bg-white/15 transition">
            Masuk
          </a>
          @if(Route::has('register'))
            <a href="{{ route('register') }}"
               class="px-4 py-2 rounded-xl text-sm font-semibold text-[#A65005] bg-[#F2D4C2] hover:bg-white transition">
              Daftar
            </a>
          @endif
        @endauth
      </div>
    </div>
  </nav>

  {{-- HERO --}}
  <section class="relative overflow-hidden min-h-[90vh] flex items-center"
           style="background:linear-gradient(135deg,#260101 0%,#592202 40%,#A65005 75%,#D99C79 100%)">

    {{-- Dekorasi lingkaran --}}
    <div class="absolute top-10 right-10 w-72 h-72 rounded-full opacity-10"
         style="background:radial-gradient(circle,#F2D4C2,transparent)"></div>
    <div class="absolute bottom-0 left-0 w-96 h-96 rounded-full opacity-5"
         style="background:radial-gradient(circle,#F2D4C2,transparent)"></div>

    <div class="max-w-6xl mx-auto px-6 py-20 grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

      {{-- TEKS --}}
      <div>
        <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm border border-white/20 rounded-full px-4 py-1.5 text-xs text-[#F2D4C2] font-medium mb-6 fade-up fade-up-1">
          <i class='bx bxs-star text-[#D99C79]'></i> Premium Baking Ingredients & Tools
        </div>
        <h1 class="playfair text-4xl md:text-5xl lg:text-6xl font-bold text-white leading-tight mb-4 fade-up fade-up-2">
          Wujudkan<br>
          <span class="dancing text-[#D99C79]" style="font-size:1.2em">Kue Impianmu</span>
        </h1>
        <p class="text-[#F2D4C2]/80 text-lg leading-relaxed mb-8 max-w-md fade-up fade-up-3">
          Temukan bahan-bahan baking premium dan peralatan dapur terbaik untuk setiap kreasi kamu.
        </p>
        <div class="flex flex-wrap gap-3 fade-up fade-up-4">
          <a href="{{ route('register') }}"
             class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-[#592202] bg-[#F2D4C2] hover:bg-white transition hover:-translate-y-0.5 shadow-lg">
            <i class='bx bxs-cake'></i> Mulai Belanja
          </a>
          <a href="{{ route('login') }}"
             class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-semibold text-white bg-white/15 border border-white/25 hover:bg-white/25 transition">
            Masuk Akun <i class='bx bx-right-arrow-alt'></i>
          </a>
        </div>

        {{-- STATS --}}
        <div class="flex gap-6 mt-10 fade-up fade-up-4">
          @foreach([['500+','Produk'],['10rb+','Pelanggan'],['4.9','Rating']] as $s)
            <div>
              <p class="playfair text-2xl font-bold text-white">{{ $s[0] }}</p>
              <p class="text-[#D99C79] text-xs">{{ $s[1] }}</p>
            </div>
          @endforeach
        </div>
      </div>

      {{-- ILUSTRASI --}}
      <div class="hidden lg:flex justify-center items-center">
        <div class="relative">
          <div class="w-72 h-72 rounded-full float"
               style="background:radial-gradient(circle at 40% 40%,#F2D4C2 0%,#D99C79 50%,#A65005 100%);box-shadow:0 20px 60px rgba(0,0,0,0.3)">
          </div>
          <div class="absolute inset-0 flex items-center justify-center text-[120px] float" style="animation-delay:-1s">
            🎂
          </div>
          {{-- floating icons --}}
          <div class="absolute -top-4 -right-4 w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-xl text-2xl float" style="animation-delay:-0.5s">🧁</div>
          <div class="absolute -bottom-4 -left-4 w-12 h-12 bg-[#592202] rounded-2xl flex items-center justify-center shadow-xl text-xl float" style="animation-delay:-1.5s">🍪</div>
          <div class="absolute top-1/2 -left-10 w-10 h-10 bg-[#D99C79] rounded-xl flex items-center justify-center shadow-xl text-lg float" style="animation-delay:-2s">🥐</div>
        </div>
      </div>

    </div>

    {{-- Wave --}}
    <div class="absolute bottom-0 left-0 right-0">
      <svg viewBox="0 0 1440 80" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M0,40 C360,80 1080,0 1440,40 L1440,80 L0,80 Z" fill="#F9EDE3"/>
      </svg>
    </div>
  </section>

  {{-- FEATURES --}}
  <section class="max-w-6xl mx-auto px-6 py-20">
    <div class="text-center mb-12">
      <p class="text-[#A65005] text-sm font-semibold uppercase tracking-widest mb-2">Kenapa CallysBake?</p>
      <h2 class="playfair text-3xl font-bold text-[#260101]">Semua yang Kamu Butuhkan</h2>
    </div>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
      @foreach([
        ['icon'=>'bxs-like','title'=>'Kualitas Premium','desc'=>'Bahan pilihan langsung dari supplier terpercaya','color'=>'bg-[#FDF3EC]','ic'=>'text-[#A65005]'],
        ['icon'=>'bxs-truck','title'=>'Pengiriman Cepat','desc'=>'Estimasi 1–3 hari ke seluruh Indonesia','color'=>'bg-[#FDF3EC]','ic'=>'text-[#A65005]'],
        ['icon'=>'bxs-coupon','title'=>'Promo & Voucher','desc'=>'Diskon spesial setiap minggu untuk member','color'=>'bg-[#FDF3EC]','ic'=>'text-[#A65005]'],
        ['icon'=>'bxs-shield-alt-2','title'=>'Transaksi Aman','desc'=>'Pembayaran terproteksi & garansi retur 7 hari','color'=>'bg-[#FDF3EC]','ic'=>'text-[#A65005]'],
      ] as $f)
        <div class="bg-white rounded-2xl border border-[#F2D4C2] p-6 shadow-sm hover:shadow-md hover:-translate-y-1 transition-all">
          <div class="w-12 h-12 rounded-xl {{ $f['color'] }} flex items-center justify-center mb-4">
            <i class="bx {{ $f['icon'] }} text-2xl {{ $f['ic'] }}"></i>
          </div>
          <h3 class="playfair font-bold text-[#260101] mb-2">{{ $f['title'] }}</h3>
          <p class="text-sm text-[#D99C79] leading-relaxed">{{ $f['desc'] }}</p>
        </div>
      @endforeach
    </div>
  </section>

  {{-- KATEGORI --}}
  <section class="bg-white py-20">
    <div class="max-w-6xl mx-auto px-6">
      <div class="text-center mb-12">
        <p class="text-[#A65005] text-sm font-semibold uppercase tracking-widest mb-2">Koleksi Kami</p>
        <h2 class="playfair text-3xl font-bold text-[#260101]">Belanja Berdasarkan Kategori</h2>
      </div>
      <div class="grid grid-cols-2 md:grid-cols-4 gap-5">
        @foreach([
          ['emoji'=>'🌾','label'=>'Tepung & Bahan Dasar','bg'=>'from-[#FDF3EC] to-[#F2D4C2]'],
          ['emoji'=>'🧈','label'=>'Lemak & Minyak','bg'=>'from-[#FFF8F0] to-[#F2D4C2]'],
          ['emoji'=>'🍫','label'=>'Cokelat & Topping','bg'=>'from-[#FDF3EC] to-[#D99C79]'],
          ['emoji'=>'🔧','label'=>'Peralatan Baking','bg'=>'from-[#FFF0E8] to-[#F2D4C2]'],
        ] as $cat)
          <a href="{{ route('products.index') }}"
             class="group bg-gradient-to-br {{ $cat['bg'] }} rounded-2xl border border-[#F2D4C2] p-6 text-center hover:shadow-md hover:-translate-y-1 transition-all">
            <div class="text-5xl mb-3 group-hover:scale-110 transition-transform">{{ $cat['emoji'] }}</div>
            <p class="text-sm font-semibold text-[#592202]">{{ $cat['label'] }}</p>
          </a>
        @endforeach
      </div>
    </div>
  </section>

  {{-- CTA --}}
  <section class="max-w-6xl mx-auto px-6 py-20">
    <div class="rounded-3xl overflow-hidden relative"
         style="background:linear-gradient(135deg,#592202,#A65005,#D99C79)">
      <div class="absolute right-8 bottom-0 text-[120px] opacity-10 select-none">🍰</div>
      <div class="relative px-8 py-14 md:flex items-center justify-between gap-8">
        <div>
          <p class="text-[#F2D4C2]/80 text-sm mb-2">Bergabung sekarang</p>
          <h2 class="playfair text-2xl md:text-3xl font-bold text-white mb-3">
            Daftarkan dirimu &<br>dapatkan voucher selamat datang!
          </h2>
          <p class="text-[#F2D4C2]/80 text-sm">Belanja lebih mudah, lebih hemat, lebih menyenangkan.</p>
        </div>
        <div class="mt-6 md:mt-0 flex gap-3 flex-shrink-0">
          <a href="{{ route('register') }}"
             class="inline-flex items-center gap-2 px-6 py-3 rounded-xl font-bold text-[#592202] bg-[#F2D4C2] hover:bg-white transition whitespace-nowrap">
            <i class='bx bxs-user-plus'></i> Daftar Gratis
          </a>
        </div>
      </div>
    </div>
  </section>

  {{-- FOOTER --}}
  <footer class="bg-[#260101] text-[#D99C79] py-10">
    <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-4">
      <div class="flex items-center gap-2">
        <div class="w-8 h-8 rounded-xl bg-[#F2D4C2] flex items-center justify-center">
          <i class='bx bxs-cake text-[#A65005]'></i>
        </div>
        <span class="dancing text-[#F2D4C2] text-xl">CallysBake</span>
      </div>
      <p class="text-sm text-center">© {{ date('Y') }} CallysBake — Premium Baking Ingredients & Tools 🍰</p>
      <div class="flex gap-4">
        @if(Route::has('login'))
          <a href="{{ route('login') }}" class="text-sm hover:text-[#F2D4C2] transition">Masuk</a>
        @endif
        @if(Route::has('register'))
          <a href="{{ route('register') }}" class="text-sm hover:text-[#F2D4C2] transition">Daftar</a>
        @endif
      </div>
    </div>
  </footer>

</body>
</html>