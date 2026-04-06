@php $currentRoute = Route::currentRouteName(); @endphp

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>{{ config('app.name') }}</title>
<link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&family=DM+Sans:wght@400;500;600&display=swap" rel="stylesheet">
<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
@vite(['resources/css/app.css','resources/js/app.js'])
<style>
  body { font-family: 'DM Sans', sans-serif; }
  .dancing { font-family: 'Dancing Script', cursive; }
  .dd-item:hover { background:#A65005 !important; color:#fff !important; }
  .dd-item:hover i { color:#F2D4C2 !important; }
  .dd-logout:hover { background:#800000 !important; }
  @keyframes dropIn {
    from { opacity:0; transform:translateY(-6px); }
    to   { opacity:1; transform:translateY(0); }
  }
  .drop-anim { animation: dropIn 0.18s ease; }
</style>
</head>
<body class="bg-[#F9EDE3] min-h-screen flex flex-col text-[#260101]">

{{-- ══ NAVBAR ══ --}}
<nav class="bg-[#A65005] sticky top-0 z-50 shadow-lg">
  <div class="max-w-6xl mx-auto px-6 h-16 flex items-center gap-3">

    {{-- Logo --}}
    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 shrink-0">
      <div class="w-9 h-9 rounded-xl bg-[#F2D4C2] flex items-center justify-center shadow-sm">
        <i class='bx bxs-cake text-[#A65005] text-xl'></i>
      </div>
      <span class="dancing text-[#F2D4C2] text-2xl">CallysBake</span>
    </a>

    {{-- Desktop --}}
    <div class="hidden md:flex items-center gap-1 ml-auto">

      <a href="{{ route('dashboard') }}"
         class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-medium transition
                {{ request()->routeIs('dashboard') ? 'bg-white/20 text-white' : 'text-[#F2D4C2]/85 hover:bg-white/15 hover:text-white' }}">
        <i class='bx bx-home'></i> Home
      </a>

      <a href="{{ route('products.index') }}"
         class="flex items-center gap-1.5 px-3 py-2 rounded-xl text-sm font-medium transition
                {{ request()->routeIs('products.*') ? 'bg-white/20 text-white' : 'text-[#F2D4C2]/85 hover:bg-white/15 hover:text-white' }}">
        <i class='bx bx-store'></i> Products
      </a>

      <a href="{{ route('cart.index') }}"
         class="w-10 h-10 flex items-center justify-center rounded-xl transition
                {{ request()->routeIs('cart.*') ? 'bg-white/20 text-white' : 'text-[#F2D4C2]/85 hover:bg-white/15 hover:text-white' }}">
        <i class='bx bx-cart text-xl'></i>
      </a>

      <button id="openSearch"
              class="w-10 h-10 flex items-center justify-center rounded-xl text-[#F2D4C2]/85 hover:bg-white/15 hover:text-white transition">
        <i class='bx bx-search text-xl'></i>
      </button>

      {{-- Profile Dropdown --}}
      <div class="relative" id="profileWrap">
        <button id="profileBtn"
                class="w-10 h-10 flex items-center justify-center rounded-xl hover:bg-white/15 transition">
          <div class="w-7 h-7 rounded-full bg-gradient-to-br from-[#F2D4C2] to-[#D99C79] flex items-center justify-center text-[#A65005] text-sm font-bold overflow-hidden">
            @if(auth()->user()?->avatar)
              <img src="{{ asset('storage/'.auth()->user()->avatar) }}" class="w-full h-full object-cover">
            @else
              {{ strtoupper(substr(auth()->user()?->name ?? 'U', 0, 1)) }}
            @endif
          </div>
        </button>

        <div id="profileDropdown"
             class="hidden drop-anim absolute right-0 top-[calc(100%+8px)] w-52 bg-white rounded-2xl shadow-xl border border-[#F2D4C2] overflow-hidden z-50">
          <div class="px-4 py-3 bg-gradient-to-br from-[#fdf8f4] to-[#F2D4C2] border-b border-[#F2D4C2]">
            <p class="text-sm font-bold text-[#592202]">{{ auth()->user()?->name }}</p>
            <p class="text-xs text-[#D99C79] truncate">{{ auth()->user()?->email }}</p>
          </div>
          <a href="{{ route('shop.profile.edit') }}" class="dd-item flex items-center gap-2.5 px-4 py-2.5 text-sm text-[#260101] transition">
            <i class='bx bx-user text-[#D99C79]'></i> My Account
          </a>
          <a href="{{ route('orders.index') }}" class="dd-item flex items-center gap-2.5 px-4 py-2.5 text-sm text-[#260101] transition">
            <i class='bx bx-package text-[#D99C79]'></i> Orders
          </a>
          <a href="{{ route('user.vouchers.index') }}" class="dd-item flex items-center gap-2.5 px-4 py-2.5 text-sm text-[#260101] transition">
            <i class='bx bx-purchase-tag text-[#D99C79]'></i> Vouchers
          </a>
          <div class="h-px bg-[#F2D4C2] mx-3 my-1"></div>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="dd-item dd-logout w-full flex items-center gap-2.5 px-4 py-2.5 text-sm text-[#260101] transition">
              <i class='bx bx-log-out text-[#D99C79]'></i> Logout
            </button>
          </form>
        </div>
      </div>

    </div>

    {{-- Mobile icons --}}
    <div class="flex md:hidden items-center gap-1 ml-auto">
      <button id="openSearchMob" class="w-10 h-10 flex items-center justify-center text-[#F2D4C2] text-xl">
        <i class='bx bx-search'></i>
      </button>
      <a href="{{ route('cart.index') }}" class="w-10 h-10 flex items-center justify-center text-[#F2D4C2] text-xl">
        <i class='bx bx-cart'></i>
      </a>
    </div>

  </div>
</nav>

{{-- ══ SEARCH OVERLAY ══ --}}
<div id="searchOverlay"
     class="hidden fixed inset-0 bg-[#260101]/60 backdrop-blur-sm z-[60] flex items-start justify-center pt-24 px-4">
  <form action="{{ route('products.index') }}" method="GET"
        class="flex items-center gap-2 bg-white rounded-2xl shadow-2xl w-full max-w-lg px-5 py-3">
    <i class='bx bx-search text-[#D99C79] text-xl'></i>
    <input name="q" placeholder="Cari produk baking..."
           class="flex-1 outline-none text-sm text-[#260101] placeholder-[#D99C79] bg-transparent">
    <button class="bg-[#A65005] text-white w-9 h-9 rounded-xl flex items-center justify-center">
      <i class='bx bx-right-arrow-alt text-lg'></i>
    </button>
  </form>
</div>

{{-- ══ FLASH ══ --}}
@if(session('success') || session('error') || $errors->any())
  <div class="max-w-6xl mx-auto w-full px-6 pt-5">
    @if(session('success'))
      <div class="flex items-center gap-2 bg-green-50 border border-green-200 text-green-700 rounded-xl px-4 py-3 text-sm">
        <i class='bx bx-check-circle text-lg'></i> {{ session('success') }}
      </div>
    @endif
    @if(session('error') || $errors->any())
      <div class="flex items-center gap-2 bg-red-50 border border-red-200 text-red-700 rounded-xl px-4 py-3 text-sm">
        <i class='bx bx-error-circle text-lg'></i> {{ session('error') ?? $errors->first() }}
      </div>
    @endif
  </div>
@endif

{{-- ══ CONTENT ══ --}}
<main class="flex-1 max-w-6xl mx-auto w-full px-3 py-4 md:py-10 pb-20 md:pb-10">
  @yield('content')
</main>

{{-- ══ FOOTER ══ --}}
<footer class="hidden md:block bg-[#592202] border-t-2 border-[#A65005] text-center py-5 text-sm text-[#D99C79]">
  © {{ date('Y') }} <span class="dancing text-[#F2D4C2] text-base">CallysBake</span> — Premium Baking Ingredients & Tools 🍰
</footer>

{{-- ══ MOBILE BOTTOM NAV ══ --}}
<nav class="md:hidden fixed bottom-0 inset-x-0 bg-[#A65005] border-t border-white/10 shadow-xl z-50">
  <div class="flex">
    <a href="{{ route('dashboard') }}"
       class="flex-1 flex flex-col items-center py-2.5 gap-0.5 text-[0.6rem] transition
              {{ request()->routeIs('dashboard') ? 'text-white bg-white/10' : 'text-[#F2D4C2]/75 hover:text-white' }}">
      <i class="bx bx-home text-[1.3rem]"></i> Home
    </a>
    <a href="{{ route('products.index') }}"
       class="flex-1 flex flex-col items-center py-2.5 gap-0.5 text-[0.6rem] transition
              {{ request()->routeIs('products.*') ? 'text-white bg-white/10' : 'text-[#F2D4C2]/75 hover:text-white' }}">
      <i class="bx bx-store text-[1.3rem]"></i> Products
    </a>
    <a href="{{ route('cart.index') }}"
       class="flex-1 flex flex-col items-center py-2.5 gap-0.5 text-[0.6rem] transition
              {{ request()->routeIs('cart.*') ? 'text-white bg-white/10' : 'text-[#F2D4C2]/75 hover:text-white' }}">
      <i class="bx bx-cart text-[1.3rem]"></i> Cart
    </a>
    <a href="{{ route('shop.profile.edit') }}"
       class="flex-1 flex flex-col items-center py-2.5 gap-0.5 text-[0.6rem] transition
              {{ request()->routeIs('shop.profile.*') ? 'text-white bg-white/10' : 'text-[#F2D4C2]/75 hover:text-white' }}">
      <i class="bx bx-user text-[1.3rem]"></i> Account
    </a>
  </div>
</nav>

{{-- ══ SCRIPTS ══ --}}
<script>
const dd = document.getElementById('profileDropdown');

document.getElementById('profileBtn')?.addEventListener('click', e => {
  e.stopPropagation();
  dd.classList.toggle('hidden');
});
document.addEventListener('click', e => {
  if (!e.target.closest('#profileWrap')) dd?.classList.add('hidden');
});

const overlay = document.getElementById('searchOverlay');
['openSearch','openSearchMob'].forEach(id => {
  document.getElementById(id)?.addEventListener('click', () => {
    overlay.classList.remove('hidden');
    setTimeout(() => overlay.querySelector('input')?.focus(), 50);
  });
});
overlay.addEventListener('click', e => {
  if (e.target === overlay) overlay.classList.add('hidden');
});
document.addEventListener('keydown', e => {
  if (e.key === 'Escape') {
    overlay.classList.add('hidden');
    dd?.classList.add('hidden');
  }
});
</script>

@stack('scripts')
</body>
</html>