@extends('layouts.admin')

@section('content')
<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 py-4 space-y-8">

    {{-- HEADER --}}
    <div>
        <h1 class="text-3xl font-bold text-[#260101] flex items-center gap-3">
            <span class="inline-flex items-center justify-center w-11 h-11 rounded-2xl mt-3"
                  style="background:linear-gradient(135deg,#A65005,#592202)">
                <i class='bx bx-home text-white text-2xl'></i>
            </span>
            Admin Dashboard
        </h1>
        <p class="text-sm text-[#D99C79] mt-1 ml-14">Selamat datang kembali!</p>
    </div>

    {{-- STAT CARDS --}}
    @php
    $cards = [
        ['label'=>'Total Sales',      'value'=>'Rp '.number_format($totalSales),                                  'icon'=>'bxs-wallet',       'grad'=>'from-[#A65005] to-[#592202]'],
        ['label'=>'Total Orders',     'value'=>$totalOrders,                                                       'icon'=>'bxs-cart',         'grad'=>'from-amber-500 to-amber-700'],
        ['label'=>'Total Users',      'value'=>$totalUsers,                                                        'icon'=>'bxs-group',        'grad'=>'from-sky-500 to-sky-700'],
        ['label'=>'Total Products',   'value'=>$totalProducts,                                                     'icon'=>'bxs-store',        'grad'=>'from-emerald-500 to-emerald-700'],
        ['label'=>'Low Stock',        'value'=>$lowStock,                                                          'icon'=>'bxs-error',        'grad'=>'from-[#800000] to-[#260101]'],
        ['label'=>'Avg Order Value',  'value'=>'Rp '.number_format($totalOrders ? $totalSales/$totalOrders : 0),  'icon'=>'bxs-bar-chart-alt-2','grad'=>'from-[#D99C79] to-[#A65005]'],
    ];
    @endphp

    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-4">
        @foreach($cards as $c)
        <div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm
                    hover:shadow-xl hover:-translate-y-1 transition-all duration-300 p-4 flex flex-col gap-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br {{ $c['grad'] }}
                        flex items-center justify-center flex-shrink-0">
                <i class="bx {{ $c['icon'] }} text-white text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-[#D99C79] uppercase tracking-wide">{{ $c['label'] }}</p>
                <h2 class="text-lg sm:text-xl font-bold text-[#260101] break-words leading-tight mt-0.5">
                    {{ $c['value'] }}
                </h2>
            </div>
        </div>
        @endforeach
    </div>

    {{-- CHART --}}
    <div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm p-5 sm:p-6">
        <div class="flex items-center gap-3 mb-5">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                 style="background:linear-gradient(135deg,#A65005,#592202)">
                <i class="bx bxs-bar-chart-alt-2 text-white text-sm"></i>
            </div>
            <div>
                <h2 class="font-bold text-[#260101]">Statistik Penjualan</h2>
                <p class="text-xs text-[#D99C79]">Revenue per bulan</p>
            </div>
        </div>
        <div class="relative w-full h-56 sm:h-72">
            <canvas id="salesChart"></canvas>
        </div>
    </div>

    {{-- PRODUK + RINGKASAN PESANAN --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Top Products --}}
        <div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-[#F2D4C2] flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                     style="background:linear-gradient(135deg,#A65005,#592202)">
                    <i class="bx bxs-store text-white text-sm"></i>
                </div>
                <div>
                    <h2 class="font-bold text-[#260101] text-sm">Produk Terlaris & Stok</h2>
                    <p class="text-xs text-[#D99C79]">Stok variant saat ini</p>
                </div>
            </div>
            <div class="overflow-auto max-h-72 p-4">
                <ul class="space-y-2">
                    @foreach($topProducts as $p)
                    @php $stockLeft = $p->variants->sum('stock'); @endphp
                    <li class="flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl
                                hover:bg-[#F2D4C2]/50 transition">
                        <span class="text-sm text-[#260101] truncate">{{ $p->name }}</span>
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full flex-shrink-0
                            {{ $stockLeft < 10
                                ? 'bg-red-100 text-red-600'
                                : 'bg-emerald-100 text-emerald-700' }}">
                            {{ $stockLeft }} pcs
                        </span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Ringkasan Pesanan --}}
        <div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-[#F2D4C2] flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                     style="background:linear-gradient(135deg,#D99C79,#A65005)">
                    <i class="bx bxs-cart text-white text-sm"></i>
                </div>
                <div>
                    <h2 class="font-bold text-[#260101] text-sm">Ringkasan Pesanan</h2>
                    <p class="text-xs text-[#D99C79]">Status semua pesanan</p>
                </div>
            </div>
            <div class="p-4">
                @php
                $statusConfig = [
                    'pending'   => ['bg-[#F2D4C2]',  'text-[#A65005]', 'bxs-time'],
                    'paid'      => ['bg-sky-100',     'text-sky-700',   'bxs-check-circle'],
                    'shipped'   => ['bg-purple-100',  'text-purple-700','bxs-package'],
                    'done'      => ['bg-emerald-100', 'text-emerald-700','bxs-badge-check'],
                    'cancelled' => ['bg-red-100',     'text-red-600',   'bxs-x-circle'],
                ];
                @endphp
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($orderSummary as $status => $count)
                    @php $cfg = $statusConfig[$status] ?? ['bg-gray-100','text-gray-600','bxs-circle']; @endphp
                    <div class="rounded-2xl {{ $cfg[0] }} px-4 py-4 flex flex-col items-center justify-center
                                hover:-translate-y-0.5 transition-all duration-200 border border-white/50">
                        <i class="bx {{ $cfg[2] }} {{ $cfg[1] }} text-2xl mb-1"></i>
                        <p class="text-xs uppercase font-bold {{ $cfg[1] }} tracking-wide">{{ $status }}</p>
                        <p class="text-2xl font-bold {{ $cfg[1] }}">{{ $count }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- REVIEW + VOUCHER --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Review Terbaru --}}
        <div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-[#F2D4C2] flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                     style="background:linear-gradient(135deg,#800000,#260101)">
                    <i class="bx bxs-star text-white text-sm"></i>
                </div>
                <div>
                    <h2 class="font-bold text-[#260101] text-sm">Review Terbaru</h2>
                    <p class="text-xs text-[#D99C79]">Komentar customer</p>
                </div>
            </div>
            <div class="overflow-auto max-h-72 p-4">
                <ul class="space-y-2">
                    @foreach($recentReviews as $review)
                    <li class="flex items-start justify-between gap-3 px-3 py-2.5 rounded-xl
                                hover:bg-[#F2D4C2]/50 transition">
                        <div class="min-w-0">
                            <p class="text-xs font-semibold text-[#260101]">{{ $review->user->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ $review->comment }}</p>
                        </div>
                        <span class="text-xs font-bold px-2 py-1 rounded-full flex-shrink-0
                            {{ $review->rating <= 2 ? 'bg-red-100 text-red-600' :
                               ($review->rating == 3 ? 'bg-amber-100 text-amber-600' : 'bg-emerald-100 text-emerald-700') }}">
                            {{ $review->rating }}★
                        </span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>

        {{-- Voucher --}}
        <div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-[#F2D4C2] flex items-center gap-3">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                    style="background:linear-gradient(135deg,#D99C79,#592202)">
                    <i class="bx bxs-purchase-tag text-white text-sm"></i>
                </div>
                <div>
                    <h2 class="font-bold text-[#260101] text-sm">Voucher & Promo</h2>
                    <p class="text-xs text-[#D99C79]">Status voucher aktif</p>
                </div>
            </div>
            <div class="overflow-auto max-h-72 p-4">
                <ul class="space-y-2">
                    @foreach($vouchers as $v)
                    <li class="flex items-center justify-between gap-3 px-3 py-2.5 rounded-xl hover:bg-[#F2D4C2]/50 transition">
                        <div class="min-w-0">
                            <p class="text-sm font-bold text-[#260101] truncate">{{ $v->code }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                {{ $v->type === 'percent' ? 'Diskon '.$v->value.'%' : 'Potongan Rp '.number_format($v->value, 0, ',', '.') }}
                                @if($v->min_purchase > 0)
                                    &bull; Min. Rp {{ number_format($v->min_purchase, 0, ',', '.') }}
                                @endif
                            </p>
                            <p class="text-xs text-gray-500">
                                {{ $v->expired_at ? 'Exp: '.\Carbon\Carbon::parse($v->expired_at)->format('d M Y') : 'Tanpa expired' }}
                            </p>
                        </div>
                        <span class="text-xs font-bold px-2.5 py-1 rounded-full flex-shrink-0
                            {{ $v->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-red-100 text-red-600' }}">
                            {{ $v->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>

    {{-- QUICK LINKS --}}
    <div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm p-5">
        <h2 class="font-bold text-[#260101] mb-4 flex items-center gap-2">
            <i class="bx bxs-zap text-[#A65005]"></i> Quick Links
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            @foreach([
                ['admin.products.index', 'bxs-store',        'Produk',   'from-emerald-500 to-emerald-700'],
                ['admin.orders.index',   'bxs-cart',         'Pesanan',  'from-amber-500 to-amber-700'],
                ['admin.users.index',    'bxs-group',        'Users',    'from-sky-500 to-sky-700'],
                ['admin.vouchers.index', 'bxs-purchase-tag', 'Voucher',  'from-[#A65005] to-[#592202]'],
            ] as [$route, $icon, $label, $grad])
            <a href="{{ route($route) }}"
               class="flex items-center justify-center gap-2 py-3.5 rounded-2xl text-white text-sm font-bold
                      bg-gradient-to-br {{ $grad }} shadow hover:shadow-lg hover:-translate-y-0.5 transition-all">
                <i class="bx {{ $icon }} text-lg"></i> {{ $label }}
            </a>
            @endforeach
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('salesChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($salesChartLabels),
        datasets: [{
            label: 'Sales (Rp)',
            data: @json($salesChartData),
            borderColor: '#A65005',
            backgroundColor: 'rgba(166,80,5,0.08)',
            pointBackgroundColor: '#A65005',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointRadius: 5,
            fill: true,
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                callbacks: {
                    label: ctx => 'Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                }
            }
        },
        scales: {
            x: { grid: { color: '#F2D4C2' } },
            y: {
                grid: { color: '#F2D4C2' },
                ticks: { callback: v => 'Rp ' + (v/1000).toFixed(0) + 'K' }
            }
        }
    }
});
</script>
@endpush

@endsection