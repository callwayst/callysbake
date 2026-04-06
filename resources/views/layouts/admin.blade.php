@php
    $currentRoute = Route::currentRouteName();
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Dancing+Script:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body class="flex min-h-screen font-sans bg-[#F2D4C2]">

    <!-- ================= SIDEBAR ================= -->
    <aside class="flex flex-col flex-shrink-0
                  w-14 sm:w-16 md:w-64
                  bg-[#A65005] shadow-xl
                  transition-all duration-300">

        <!-- LOGO -->
        <div class="flex items-center justify-center md:justify-start gap-3 px-2 md:px-6 py-5 pb-4">
            <div class="w-9 h-9 rounded-2xl bg-[#F2D4C2] flex items-center justify-center flex-shrink-0 shadow-sm">
                <i class='bx bxs-cake text-[#A65005] text-xl'></i>
            </div>
            <div class="hidden md:block">
                <p class="text-2xl font-bold text-[#F2D4C2] leading-none"
                   style="font-family:'Dancing Script',cursive;">
                    CallysBake
                </p>
                <p class="text-[10px] text-[#D99C79] tracking-widest uppercase mt-0.5">Admin Panel</p>
            </div>
        </div>

        <!-- divider dekoratif -->
        <div class="mx-3 md:mx-5 mb-4 flex items-center gap-2">
            <div class="flex-1 h-px bg-[#D99C79]/40"></div>
            <i class="bx bxs-star text-[#D99C79]/60 text-xs hidden md:block"></i>
            <div class="flex-1 h-px bg-[#D99C79]/40 hidden md:block"></div>
        </div>

       <!-- NAVIGATION -->
        <nav class="flex-1 px-1 md:px-4 space-y-2 text-sm md:text-base">
        @php
            $navItems = [
                ['route'=>'admin.dashboard',        'icon'=>'bxs-dashboard',        'label'=>'Dashboard',           'match'=>'admin.dashboard'],
                ['route'=>'admin.products.index',   'icon'=>'bxs-store',            'label'=>'Product',             'match'=>'admin.products'],
                ['route'=>'admin.categories.index', 'icon'=>'bxs-category',         'label'=>'Category',            'match'=>'admin.categories'],
                ['route'=>'admin.users.index',      'icon'=>'bxs-group',            'label'=>'User',                'match'=>'admin.users'],
                ['route'=>'admin.orders.index',     'icon'=>'bxs-cart',             'label'=>'Order',               'match'=>'admin.orders'],
                ['route'=>'admin.reviews.index',    'icon'=>'bxs-star',             'label'=>'Review & Rating',     'match'=>'admin.reviews'],
                ['route'=>'admin.vouchers.index',   'icon'=>'bxs-coupon',           'label'=>'Voucher',             'match'=>'admin.vouchers'],
                ['route'=>'admin.reports.index',    'icon'=>'bxs-bar-chart-alt-2',  'label'=>'Statistics & Report', 'match'=>'admin.reports'],
                ['route'=>'admin.profile.edit',     'icon'=>'bxs-user-circle',      'label'=>'Profile',             'match'=>'admin.profile'],
            ];
            @endphp
            @foreach($navItems as $item)
            @php $isActive = request()->routeIs($item['match'].'*'); @endphp
            <a href="{{ route($item['route']) }}"
            class="flex items-center justify-center md:justify-start gap-0 md:gap-3
                    px-1 md:px-3 py-3 md:py-2 rounded transition-all duration-200
                    {{ $isActive
                        ? 'bg-[#F2D4C2] text-[#592202] border-l-4 border-[#592202] shadow-md'
                        : 'text-white hover:bg-[#D99C79]/40 hover:text-[#F2D4C2] hover:border-l-4 hover:border-[#F2D4C2]' }}">
                <i class="bx {{ $item['icon'] }} text-2xl md:text-lg flex-shrink-0
                        {{ $isActive ? 'text-[#A65005]' : '' }}"></i>
                <span class="hidden md:inline {{ $isActive ? 'font-bold' : '' }}">
                    {{ $item['label'] }}
                </span>
            </a>
            @endforeach

        </nav>

        <!-- USER + LOGOUT -->
        <div class="px-1 md:px-3 py-4 mt-2">
            <div class="mx-1 md:mx-0 h-px bg-[#D99C79]/40 mb-4"></div>

            {{-- User info desktop --}}
            <div class="hidden md:flex items-center gap-3 px-3 py-2.5 mb-2 rounded-xl bg-[#D99C79]/20">
                <div class="w-8 h-8 rounded-full bg-[#F2D4C2] flex items-center justify-center
                            text-sm font-bold text-[#A65005] flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
                <div class="min-w-0">
                    <p class="text-xs font-bold text-[#F2D4C2] truncate">{{ auth()->user()->name ?? 'Admin' }}</p>
                    <p class="text-[10px] text-[#D99C79] truncate capitalize">{{ auth()->user()->role ?? 'admin' }}</p>
                </div>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" title="Logout"
                        class="w-full flex items-center justify-center md:justify-start gap-0 md:gap-3
                               px-1 md:px-3 py-3 md:py-2.5 rounded-xl transition-all duration-200 group
                               hover:bg-[#800000]/60">
                    <i class='bx bx-log-out text-2xl md:text-base text-[#F2D4C2]/70
                               group-hover:text-[#F2D4C2] transition-colors flex-shrink-0'></i>
                    <span class="hidden md:inline text-sm font-medium text-[#F2D4C2]/70
                                 group-hover:text-[#F2D4C2] transition-colors">Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- ================= MAIN WRAPPER ================= -->
    <div class="flex-1 min-w-0 flex flex-col">

        <!-- TOP BAR -->
        <header class="flex items-center justify-between px-4 sm:px-6 py-3
                       bg-white border-b border-[#F2D4C2] shadow-sm">
            <div class="flex items-center gap-3">
                {{-- Page icon --}}
                <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0"
                     style="background:linear-gradient(135deg,#A65005,#592202)">
                    @php
                        $pageIcons = [
                            'admin.dashboard'   => 'bxs-dashboard',
                            'admin.products'    => 'bxs-store',
                            'admin.categories'  => 'bxs-category',
                            'admin.users'       => 'bxs-group',
                            'admin.orders'      => 'bxs-cart',
                            'admin.reviews'     => 'bxs-star',
                            'admin.vouchers'    => 'bxs-coupon',
                            'admin.reports'     => 'bxs-bar-chart-alt-2',
                            'admin.profile'     => 'bxs-user-circle',
                        ];
                        $pageIcon = 'bxs-dashboard';
                        foreach($pageIcons as $prefix => $icon) {
                            if(request()->routeIs($prefix.'*')) { $pageIcon = $icon; break; }
                        }
                        $pageLabels = [
                            'admin.dashboard'   => 'Dashboard',
                            'admin.products'    => 'Products',
                            'admin.categories'  => 'Categories',
                            'admin.users'       => 'Users',
                            'admin.orders'      => 'Orders',
                            'admin.reviews'     => 'Reviews',
                            'admin.vouchers'    => 'Vouchers',
                            'admin.reports'     => 'Reports',
                            'admin.profile'     => 'Profile',
                        ];
                        $pageLabel = 'Dashboard';
                        foreach($pageLabels as $prefix => $label) {
                            if(request()->routeIs($prefix.'*')) { $pageLabel = $label; break; }
                        }
                    @endphp
                    <i class="bx {{ $pageIcon }} text-white text-sm"></i>
                </div>
                <div>
                    <p class="text-[10px] text-[#D99C79] uppercase tracking-widest leading-none">CallysBake</p>
                    <h2 class="text-sm font-bold text-[#260101] leading-tight">{{ $pageLabel }}</h2>
                </div>
            </div>

            <div class="flex items-center gap-2 sm:gap-3">
                {{-- Online badge --}}
                <span class="hidden sm:inline-flex items-center gap-1.5 text-xs font-semibold
                             px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600 border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Online
                </span>
                {{-- Avatar --}}
                <div class="w-8 h-8 rounded-full flex items-center justify-center
                            text-sm font-bold text-[#A65005] shadow-sm flex-shrink-0"
                     style="background:linear-gradient(135deg,#F2D4C2,#D99C79)">
                    {{ strtoupper(substr(auth()->user()->name ?? 'A', 0, 1)) }}
                </div>
            </div>
        </header>

        <!-- PAGE CONTENT -->
        <main class="flex-1 p-3 sm:p-4">
            @yield('content')
        </main>

        <!-- FOOTER -->
        <footer class="text-center text-xs py-3 bg-white border-t border-[#F2D4C2]">
            <span class="text-[#D99C79]">© {{ date('Y') }}</span>
            <span class="font-bold text-[#A65005]" style="font-family:'Dancing Script',cursive;font-size:14px">
                CallysBake
            </span>
            <span class="text-[#D99C79]">Admin Panel 🍰</span>
        </footer>

    </div>

    @stack('scripts')

</body>
</html>