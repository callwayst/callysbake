@extends('layouts.admin')

@section('content')
<div class="w-full max-w-7xl mx-auto px-4 sm:px-6 py-4 space-y-8">

    {{-- HEADER --}}
    <div>
        <h1 class="text-3xl font-bold text-[#260101] flex items-center gap-3">
            <span class="inline-flex items-center justify-center w-11 h-11 rounded-2xl mt-3"
                  style="background:linear-gradient(135deg,#A65005,#592202)">
                <i class='bx bx-bar-chart text-white text-2xl'></i>
            </span>
            Statistics & Reports
        </h1>
        <p class="text-sm text-[#D99C79] mt-1 ml-14">Analisis performa toko kamu</p>
    </div>

    {{-- TOOLBAR --}}
    <form method="GET" id="reportForm"
          class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm p-5">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-[1fr_1fr_auto_auto_auto] gap-3 items-end">

            {{-- Date From --}}
            <div class="space-y-1">
                <label class="block text-xs font-bold uppercase tracking-wide text-[#A65005]">Dari</label>
                <input type="date" name="from" value="{{ request('from', $from->format('Y-m-d')) }}"
                       class="w-full border border-[#D99C79] rounded-xl px-4 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005] transition">
            </div>

            {{-- Date To --}}
            <div class="space-y-1">
                <label class="block text-xs font-bold uppercase tracking-wide text-[#A65005]">Sampai</label>
                <input type="date" name="to" value="{{ request('to', $to->format('Y-m-d')) }}"
                       class="w-full border border-[#D99C79] rounded-xl px-4 py-2.5 text-sm
                              focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005] transition">
            </div>

            {{-- Sections dropdown --}}
            <div class="space-y-1" x-data="{ open: false }">
                <label class="block text-xs font-bold uppercase tracking-wide text-[#A65005]">Sections</label>
                <div class="relative">
                    <button type="button" @click="open=!open"
                            class="w-full flex items-center justify-between gap-2 border border-[#D99C79]
                                   rounded-xl px-4 py-2.5 text-sm bg-white hover:border-[#A65005] transition">
                        <span class="text-[#260101]">Pilih Sections</span>
                        <i class="bx bx-chevron-down text-[#D99C79]"></i>
                    </button>
                    <div x-show="open" @click.outside="open=false"
                         class="absolute top-full left-0 mt-2 w-48 bg-white border border-[#F2D4C2]
                                rounded-2xl shadow-xl p-3 z-50 space-y-2">
                        {{-- Checkbox "Semua" --}}
                        <label class="flex items-center gap-2 text-sm text-[#260101] cursor-pointer py-1 px-2 rounded-lg hover:bg-[#F2D4C2]/50 transition">
                            <input type="checkbox" name="sections[]" value="all"
                                   class="accent-[#A65005]"
                                   {{ in_array('all', request('sections', [])) ? 'checked' : '' }}
                                   @click="document.querySelectorAll('.section-cb').forEach(cb => cb.checked = $event.target.checked)">
                            <span class="font-semibold">Semua</span>
                        </label>
                        <div class="border-t border-[#F2D4C2]"></div>
                        @foreach(['summary','analytics','inventory'] as $s)
                        <label class="flex items-center gap-2 text-sm text-[#260101] cursor-pointer py-1 px-2 rounded-lg hover:bg-[#F2D4C2]/50 transition">
                            <input type="checkbox" name="sections[]" value="{{ $s }}"
                                   class="accent-[#A65005] section-cb"
                                   {{ in_array($s, request('sections', [])) || in_array('all', request('sections', [])) ? 'checked' : '' }}>
                            {{ ucfirst($s) }}
                        </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Apply --}}
            <div class="space-y-1">
                <label class="block text-xs font-bold uppercase tracking-wide text-transparent select-none">A</label>
                <button type="submit"
                        class="w-full px-5 py-2.5 rounded-xl text-sm font-bold text-white shadow
                               hover:shadow-md hover:-translate-y-0.5 transition-all"
                        style="background:linear-gradient(135deg,#A65005,#592202)">
                    Apply
                </button>
            </div>

            {{-- Export --}}
            <div class="space-y-1" x-data="{ open: false }">
                <label class="block text-xs font-bold uppercase tracking-wide text-transparent select-none">E</label>
                <div class="relative">
                    <button type="button" @click="open=!open"
                            class="w-full flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl
                                   text-sm font-bold text-[#F2D4C2] shadow hover:shadow-md hover:-translate-y-0.5 transition-all"
                            style="background:linear-gradient(135deg,#592202,#260101)">
                        <i class="bx bx-export"></i> Export
                    </button>
                    <div x-show="open" @click.outside="open=false"
                         class="absolute right-0 top-full mt-2 w-40 bg-white border border-[#F2D4C2]
                                rounded-2xl shadow-xl overflow-hidden z-50">
                        @foreach(['excel'=>'bxs-spreadsheet','pdf'=>'bxs-file-pdf','csv'=>'bxs-file'] as $fmt => $icon)
                        {{-- Submit button pakai formaction agar sections[] ikut terkirim --}}
                        <button type="submit"
                                form="reportForm"
                                formaction="{{ route('admin.reports.export', $fmt) }}"
                                formmethod="GET"
                                class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-[#260101]
                                       hover:bg-[#F2D4C2] transition text-left">
                            <i class="bx {{ $icon }} text-[#A65005]"></i>
                            {{ strtoupper($fmt) }}
                        </button>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </form>

    {{-- STAT CARDS --}}
    @php
    $cards = [
        ['label'=>'Revenue',  'value'=>'Rp '.number_format($sales),  'icon'=>'bxs-wallet',    'grad'=>'from-[#A65005] to-[#592202]'],
        ['label'=>'Profit',   'value'=>'Rp '.number_format($profit), 'icon'=>'bx-trending-up','grad'=>'from-[#592202] to-[#260101]'],
        ['label'=>'Orders',   'value'=>$orders,                       'icon'=>'bxs-cart',      'grad'=>'from-[#D99C79] to-[#A65005]'],
        ['label'=>'Users',    'value'=>$users,                        'icon'=>'bxs-group',     'grad'=>'from-[#800000] to-[#260101]'],
    ];
    @endphp
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($cards as $c)
        <div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm
                    hover:shadow-xl hover:-translate-y-1 transition-all duration-300 p-5 flex flex-col gap-3">
            <div class="w-9 h-9 rounded-xl bg-gradient-to-br {{ $c['grad'] }} flex items-center justify-center">
                <i class="bx {{ $c['icon'] }} text-white text-lg"></i>
            </div>
            <div>
                <p class="text-xs text-[#D99C79] uppercase tracking-wide">{{ $c['label'] }}</p>
                <h2 class="text-xl sm:text-2xl font-bold text-[#260101] mt-0.5 break-words leading-tight">
                    {{ $c['value'] }}
                </h2>
            </div>
        </div>
        @endforeach
    </div>

    {{-- CHARTS --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- Daily Sales & Orders --}}
        <div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm p-5">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                     style="background:linear-gradient(135deg,#A65005,#592202)">
                    <i class="bx bx-line-chart text-white text-sm"></i>
                </div>
                <div>
                    <h2 class="font-bold text-[#260101] text-sm">Daily Sales & Orders</h2>
                    <p class="text-xs text-[#D99C79]">Performa harian</p>
                </div>
            </div>
            <div class="h-64 sm:h-72">
                <canvas id="dailyChart"></canvas>
            </div>
        </div>

        {{-- Top Products --}}
        <div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm p-5">
            <div class="flex items-center gap-3 mb-5">
                <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                     style="background:linear-gradient(135deg,#D99C79,#A65005)">
                    <i class="bx bxs-store text-white text-sm"></i>
                </div>
                <div>
                    <h2 class="font-bold text-[#260101] text-sm">Top Products</h2>
                    <p class="text-xs text-[#D99C79]">Produk paling laku</p>
                </div>
            </div>
            <div class="h-64 sm:h-72">
                <canvas id="productChart"></canvas>
            </div>
        </div>
    </div>

</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="//unpkg.com/alpinejs" defer></script>
<script>
const gridColor = '#F2D4C2';

new Chart(document.getElementById('dailyChart'), {
    type: 'line',
    data: {
        labels: @json($dailyLabels),
        datasets: [
            {
                label: 'Sales (Rp)',
                data: @json($dailySales),
                borderColor: '#A65005',
                backgroundColor: 'rgba(166,80,5,0.08)',
                tension: 0.4, fill: true,
                pointBackgroundColor: '#A65005',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
            },
            {
                label: 'Orders',
                data: @json($dailyOrders),
                borderColor: '#D99C79',
                backgroundColor: 'rgba(217,156,121,0.08)',
                tension: 0.4, fill: true,
                pointBackgroundColor: '#800000',
                pointBorderColor: '#fff',
                pointBorderWidth: 2,
                pointRadius: 4,
            }
        ]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { labels: { color: '#260101', font: { size: 12 } } } },
        scales: {
            x: { grid: { color: gridColor }, ticks: { color: '#D99C79' } },
            y: { grid: { color: gridColor }, ticks: { color: '#D99C79' } }
        }
    }
});

new Chart(document.getElementById('productChart'), {
    type: 'bar',
    data: {
        labels: @json($productLabels),
        datasets: [{
            data: @json($productTotals),
            backgroundColor: [
                '#A65005','#592202','#D99C79','#800000','#F2D4C2',
            ],
            borderRadius: 8,
        }]
    },
    options: {
        responsive: true, maintainAspectRatio: false,
        plugins: { legend: { display: false } },
        scales: {
            x: { grid: { color: gridColor }, ticks: { color: '#D99C79' } },
            y: { grid: { color: gridColor }, ticks: { color: '#D99C79' } }
        }
    }
});
</script>
@endpush

@endsection