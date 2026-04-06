<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'CallysBake') }}</title>
  <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=DM+Sans:wght@300;400;500;600&family=Playfair+Display:wght@500;700&display=swap" rel="stylesheet">
  <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
  @vite(['resources/css/app.css','resources/js/app.js'])
  <style>
    body { font-family: 'DM Sans', sans-serif; }
    .dancing  { font-family: 'Dancing Script', cursive; }
    .playfair { font-family: 'Playfair Display', serif; }
    .field input, .field textarea {
      width: 100%; border: 1.5px solid #F2D4C2; border-radius: 12px;
      padding: 11px 16px; font-family: 'DM Sans', sans-serif;
      font-size: 0.88rem; color: #260101; background: #fdfaf8;
      transition: border-color 0.2s, box-shadow 0.2s; outline: none;
    }
    .field input:focus {
      border-color: #D99C79;
      box-shadow: 0 0 0 3px rgba(217,156,121,0.2);
      background: #fff;
    }
    .field label {
      display: block; font-size: 0.7rem; font-weight: 600;
      text-transform: uppercase; letter-spacing: 0.07em;
      color: #D99C79; margin-bottom: 5px;
    }
    .btn-primary {
      background: linear-gradient(135deg,#A65005,#592202);
      color: #fff; border: none; border-radius: 12px;
      padding: 12px 24px; font-family: 'DM Sans', sans-serif;
      font-size: 0.88rem; font-weight: 600; cursor: pointer;
      transition: opacity 0.2s, transform 0.15s; width: 100%;
    }
    .btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }
    @keyframes float {
      0%,100% { transform:translateY(0); }
      50%      { transform:translateY(-10px); }
    }
    .float { animation: float 4s ease-in-out infinite; }
  </style>
</head>
<body class="bg-[#F9EDE3] min-h-screen flex">

  {{-- PANEL KIRI — dekorasi --}}
  <div class="hidden lg:flex w-[420px] flex-shrink-0 flex-col items-center justify-center relative overflow-hidden"
       style="background:linear-gradient(160deg,#260101 0%,#592202 45%,#A65005 80%,#D99C79 100%)">

    <div class="absolute top-0 left-0 right-0 bottom-0 opacity-5"
         style="background-image:repeating-linear-gradient(45deg,#fff 0,#fff 1px,transparent 0,transparent 50%);background-size:20px 20px;"></div>

    <div class="relative z-10 text-center px-10">
      {{-- Logo --}}
      <div class="flex items-center justify-center gap-3 mb-8">
        <div class="w-12 h-12 rounded-2xl bg-[#F2D4C2] flex items-center justify-center shadow-lg">
          <i class='bx bxs-cake text-[#A65005] text-2xl'></i>
        </div>
        <span class="dancing text-[#F2D4C2] text-3xl">CallysBake</span>
      </div>

      {{-- Ilustrasi --}}
      <div class="relative inline-block mb-8">
        <div class="w-48 h-48 rounded-full float"
             style="background:radial-gradient(circle at 35% 35%,#F2D4C2,#D99C79 50%,#A65005);box-shadow:0 20px 50px rgba(0,0,0,0.3)">
        </div>
        <div class="absolute inset-0 flex items-center justify-center text-7xl float" style="animation-delay:-1s">🎂</div>
        <div class="absolute -top-2 -right-2 w-10 h-10 bg-white rounded-xl flex items-center justify-center shadow-lg text-xl float" style="animation-delay:-0.5s">🧁</div>
        <div class="absolute -bottom-2 -left-2 w-9 h-9 bg-[#592202] rounded-xl flex items-center justify-center shadow-lg text-lg float" style="animation-delay:-1.5s">🍪</div>
      </div>

      <h2 class="playfair text-2xl font-bold text-white mb-3">Wujudkan Kue Impianmu</h2>
      <p class="text-[#F2D4C2]/75 text-sm leading-relaxed">
        Bahan premium & peralatan baking terlengkap siap mendukung setiap kreasi kamu.
      </p>

      {{-- Mini stats --}}
      <div class="flex justify-center gap-6 mt-8">
        @foreach([['500+','Produk'],['10rb+','Member'],['4.9★','Rating']] as $s)
          <div class="text-center">
            <p class="playfair text-lg font-bold text-white">{{ $s[0] }}</p>
            <p class="text-[#D99C79] text-xs">{{ $s[1] }}</p>
          </div>
        @endforeach
      </div>
    </div>

    {{-- Back to home --}}
    <a href="{{ url('/') }}"
       class="absolute bottom-6 left-0 right-0 flex items-center justify-center gap-1.5 text-xs text-[#D99C79] hover:text-white transition">
      <i class='bx bx-arrow-back'></i> Kembali ke halaman utama
    </a>
  </div>

  {{-- PANEL KANAN — form --}}
  <div class="flex-1 flex flex-col items-center justify-center px-6 py-12">

    {{-- Mobile logo --}}
    <div class="lg:hidden flex items-center gap-2 mb-8">
      <div class="w-9 h-9 rounded-xl bg-[#A65005] flex items-center justify-center">
        <i class='bx bxs-cake text-[#F2D4C2] text-xl'></i>
      </div>
      <span class="dancing text-[#A65005] text-2xl">CallysBake</span>
    </div>

    <div class="w-full max-w-md">
      {{ $slot }}
    </div>

  </div>

</body>
</html>