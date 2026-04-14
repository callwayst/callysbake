@extends('layouts.admin')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-10 py-4 space-y-6">

    {{-- ===== HEADER ===== --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold text-[#260101] tracking-tight flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-11 h-11 rounded-2xl bg-gradient-to-br from-[#A65005] to-[#592202] shadow-lg mt-3">
                    <i class='bx bxs-user-detail text-white text-2xl'></i>
                </span>
                Detail User
            </h1>
            <p class="text-gray-400 text-sm mt-1 ml-14">Informasi lengkap pengguna</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('admin.users.index') }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl font-semibold text-sm border border-[#D99C79] text-[#592202] bg-[#F2D4C2] hover:bg-[#D99C79] transition-all duration-200">
                <i class="bx bx-arrow-back"></i> Kembali
            </a>
            <a href="{{ route('admin.users.edit', $user->id) }}"
               class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white font-semibold text-sm shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200"
               style="background: linear-gradient(135deg, #A65005 0%, #592202 100%)">
                <i class="bx bxs-edit"></i> Edit User
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        {{-- ===== KOLOM KIRI: PROFIL ===== --}}
        <div class="lg:col-span-1 space-y-4">

            {{-- Card Profil --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <div class="h-1.5 w-full {{ $user->status ? 'bg-gradient-to-r from-[#A65005] to-[#592202]' : 'bg-gray-200' }}"></div>
                <div class="p-6 flex flex-col items-center text-center">

                    {{-- Avatar --}}
                    <div class="w-24 h-24 rounded-full overflow-hidden bg-gradient-to-br from-[#F2D4C2] to-[#e8b898] flex items-center justify-center shadow-md mb-4">
                        @if($user->avatar)
                            <img src="{{ asset('storage/'.$user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-[#A65005] font-bold text-4xl">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                        @endif
                    </div>

                    <h2 class="text-xl font-bold text-[#260101]">{{ $user->name }}</h2>
                    <p class="text-sm text-gray-400 mb-4">{{ $user->email }}</p>

                    {{-- Badges --}}
                    <div class="flex flex-wrap justify-center gap-2 mb-5">
                        <span class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-full
                            {{ $user->role === 'admin' ? 'bg-[#800000]/10 text-[#800000]' : ($user->role === 'kasir' ? 'bg-blue-50 text-blue-700' : 'bg-[#A65005]/10 text-[#A65005]') }}">
                            <i class="bx {{ $user->role === 'admin' ? 'bxs-shield' : ($user->role === 'kasir' ? 'bxs-calculator' : 'bxs-user') }}"></i>
                            {{ ucfirst($user->role) }}
                        </span>
                        <span class="inline-flex items-center gap-1 text-xs font-semibold px-3 py-1.5 rounded-full
                            {{ $user->status ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-600' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $user->status ? 'bg-emerald-500' : 'bg-red-400' }}"></span>
                            {{ $user->status ? 'Active' : 'Inactive' }}
                        </span>
                    </div>

                    {{-- Toggle Status --}}
                    <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST" class="w-full">
                        @csrf @method('PATCH')
                        <button type="submit"
                            class="w-full py-2 rounded-xl text-xs font-bold tracking-wide uppercase transition-all duration-200
                            {{ $user->status
                                ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200'
                                : 'bg-red-50 text-red-600 hover:bg-red-100 border border-red-200' }}">
                            {{ $user->status ? '✓ Active — Klik untuk Nonaktifkan' : '✗ Inactive — Klik untuk Aktifkan' }}
                        </button>
                    </form>
                </div>
            </div>

            {{-- Card Info Kontak --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 p-5 space-y-3">
                <h3 class="text-xs font-bold uppercase tracking-widest text-[#A65005] flex items-center gap-2">
                    <i class="bx bxs-phone-call"></i> Kontak & Alamat
                </h3>

                <div class="flex items-center gap-3 p-3 rounded-xl bg-[#fdf8f5]">
                    <i class="bx bxs-envelope text-[#A65005] text-lg flex-shrink-0"></i>
                    <div class="min-w-0">
                        <p class="text-xs text-gray-400">Email</p>
                        <p class="text-sm font-medium text-[#260101] truncate">{{ $user->email }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 rounded-xl bg-[#fdf8f5]">
                    <i class="bx bxs-phone text-[#A65005] text-lg flex-shrink-0"></i>
                    <div class="min-w-0">
                        <p class="text-xs text-gray-400">No. Telepon</p>
                        <p class="text-sm font-medium text-[#260101]">{{ $user->phone ?? '-' }}</p>
                    </div>
                </div>

                <div class="flex items-start gap-3 p-3 rounded-xl bg-[#fdf8f5]">
                    <i class="bx bxs-map text-[#A65005] text-lg flex-shrink-0 mt-0.5"></i>
                    <div class="min-w-0">
                        <p class="text-xs text-gray-400">Alamat</p>
                        <p class="text-sm font-medium text-[#260101] leading-relaxed">{{ $user->address ?? '-' }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 rounded-xl bg-[#fdf8f5]">
                    <i class="bx bxs-calendar-check text-[#A65005] text-lg flex-shrink-0"></i>
                    <div>
                        <p class="text-xs text-gray-400">Bergabung</p>
                        <p class="text-sm font-medium text-[#260101]">{{ $user->created_at->format('d F Y') }}</p>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-3 rounded-xl bg-[#fdf8f5]">
                    <i class="bx bxs-time text-[#A65005] text-lg flex-shrink-0"></i>
                    <div>
                        <p class="text-xs text-gray-400">Update Terakhir</p>
                        <p class="text-sm font-medium text-[#260101]">{{ $user->updated_at->diffForHumans() }}</p>
                    </div>
                </div>
            </div>

        </div>

        {{-- ===== KOLOM KANAN: STATISTIK & ORDERS ===== --}}
        <div class="lg:col-span-2 space-y-5">

            {{-- Stat Cards --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                @php $orders = $user->orders; @endphp
                @foreach([
                    [
                        'label' => 'Total Orders',
                        'sub'   => 'Pesanan',
                        'value' => $orders->count(),
                        'icon'  => 'bxs-cart',
                        'color' => 'from-[#A65005] to-[#592202]',
                    ],
                    [
                        'label' => 'Total Belanja',
                        'sub'   => 'Pengeluaran',
                        'value' => 'Rp ' . number_format($orders->sum('final_price'), 0, ',', '.'),
                        'icon'  => 'bxs-wallet',
                        'color' => 'from-emerald-500 to-emerald-700',
                    ],
                    [
                        'label' => 'Status Akun',
                        'sub'   => $user->status ? 'Bisa Login' : 'Diblokir',
                        'value' => $user->status ? 'Aktif' : 'Off',
                        'icon'  => $user->status ? 'bxs-check-circle' : 'bxs-x-circle',
                        'color' => $user->status ? 'from-blue-500 to-blue-700' : 'from-gray-400 to-gray-600',
                    ],
                ] as $stat)
                <div class="rounded-2xl p-4 text-white bg-gradient-to-br {{ $stat['color'] }} shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-white/20 flex items-center justify-center flex-shrink-0">
                        <i class="bx {{ $stat['icon'] }} text-2xl"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="text-white/70 text-xs">{{ $stat['label'] }}</p>
                        <p class="font-bold text-lg leading-tight truncate">{{ $stat['value'] }}</p>
                        <p class="text-white/60 text-xs">{{ $stat['sub'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Riwayat Orders --}}
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-[#260101] flex items-center gap-2">
                        <i class="bx bx-history text-[#A65005] text-lg"></i>
                        Riwayat Orders Terakhir
                    </h3>
                    @if($orders->count() > 0)
                    <span class="text-xs text-gray-400">{{ $orders->count() }} total order</span>
                    @endif
                </div>

                {{-- Ada order --}}
                @if($orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="bg-[#fdf8f5] text-xs text-gray-400 uppercase tracking-wide">
                                <th class="px-5 py-3 text-left font-semibold">Kode Order</th>
                                <th class="px-5 py-3 text-left font-semibold">Tanggal</th>
                                <th class="px-5 py-3 text-left font-semibold">Total</th>
                                <th class="px-5 py-3 text-left font-semibold">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-50">
                            @foreach($orders->sortByDesc('created_at')->take(10) as $order)
                            <tr class="hover:bg-[#fdf8f5] transition-colors duration-150">
                                <td class="px-5 py-3.5 font-semibold text-[#A65005]">
                                    {{ $order->order_code ?? 'ORD-' . $order->id }}
                                </td>
                                <td class="px-5 py-3.5 text-gray-500 text-xs">
                                    {{ $order->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-5 py-3.5 font-semibold text-[#260101]">
                                    Rp {{ number_format($order->final_price ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="px-5 py-3.5">
                                @php
                                    $statusMap = [
                                        'pending'    => ['bg-amber-50 text-amber-700',    'bxs-time',           'Pending'],
                                        'paid'       => ['bg-blue-50 text-blue-700',       'bxs-credit-card',    'Paid'],
                                        'shipped'    => ['bg-purple-50 text-purple-700',   'bxs-package',        'Shipped'],
                                        'done'       => ['bg-emerald-50 text-emerald-700', 'bxs-check-circle',   'Done'],
                                        'dibatalkan' => ['bg-red-50 text-red-600',         'bxs-x-circle',       'Dibatalkan'],
                                        'cancelled'  => ['bg-red-50 text-red-600',         'bxs-x-circle',       'Dibatalkan'],
                                    ];
                                    $s = $statusMap[$order->status] ?? ['bg-gray-100 text-gray-500', 'bxs-help-circle', ucfirst($order->status ?? '-')];
                                @endphp
                                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full {{ $s[0] }}">
                                        <i class="bx {{ $s[1] }}"></i> {{ $s[2] }}
                                    </span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @if($orders->count() > 10)
                <div class="px-5 py-3 border-t border-gray-100 text-center">
                    <span class="text-xs text-gray-400">Menampilkan 10 dari {{ $orders->count() }} order</span>
                </div>
                @endif

                {{-- Tidak ada order --}}
                @else
                <div class="py-12 flex flex-col items-center justify-center gap-3">
                    <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-[#F2D4C2] to-[#e8b898] flex items-center justify-center">
                        <i class="bx bx-cart text-3xl text-[#A65005]"></i>
                    </div>
                    <p class="font-semibold text-gray-400">Belum ada riwayat order</p>
                    <p class="text-xs text-gray-300">User ini belum pernah melakukan pemesanan</p>
                </div>
                @endif

            </div>

        </div>
    </div>

</div>
@endsection