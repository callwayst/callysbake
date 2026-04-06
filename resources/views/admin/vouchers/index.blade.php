@extends('layouts.admin')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-10 py-4 space-y-8">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-[#260101] flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-11 h-11 rounded-2xl mt-3"
                      style="background:linear-gradient(135deg,#A65005,#592202)">
                    <i class='bx bxs-coupon text-white text-2xl'></i>
                </span>
                Manage Vouchers
            </h1>
            <p class="text-sm text-[#D99C79] mt-1 ml-14">Kelola kode promo dan diskon</p>
        </div>
        <a href="{{ route('admin.vouchers.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white font-semibold
                  shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200"
           style="background:linear-gradient(135deg,#A65005,#592202)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add Voucher
        </a>
    </div>

    {{-- DESKTOP TABLE --}}
    <div class="hidden md:block bg-white rounded-2xl border border-[#F2D4C2] shadow-sm overflow-hidden">
        <table class="min-w-full divide-y divide-[#F2D4C2]">
            <thead>
                <tr style="background:linear-gradient(135deg,#A65005,#592202)">
                    @foreach(['Kode','Tipe','Nilai','Min. Pembelian','Kedaluwarsa','Status','Aksi'] as $h)
                    <th class="px-4 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-white">
                        {{ $h }}
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-[#F2D4C2]">
                @forelse($vouchers as $v)
                <tr class="hover:bg-[#F2D4C2]/30 transition">
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center gap-1.5 font-bold text-sm text-[#260101]
                                     bg-[#F2D4C2] px-3 py-1 rounded-full">
                            <i class="bx bxs-purchase-tag text-[#A65005] text-xs"></i>
                            {{ $v->code }}
                        </span>
                    </td>
                    <td class="px-4 py-3 text-sm text-[#260101]">{{ ucfirst($v->type) }}</td>
                    <td class="px-4 py-3 text-sm font-bold text-[#A65005]">
                        {{ $v->type === 'percent' ? $v->value.'%' : 'Rp '.number_format($v->value,0,',','.') }}
                    </td>
                    <td class="px-4 py-3 text-sm text-[#260101]">
                        {{ $v->min_purchase ? 'Rp '.number_format($v->min_purchase,0,',','.') : '—' }}
                    </td>
                    <td class="px-4 py-3 text-sm text-[#D99C79] whitespace-nowrap">
                        {{ $v->expired_at->format('d M Y') }}
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full
                            {{ $v->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                            <span class="w-1.5 h-1.5 rounded-full {{ $v->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                            {{ $v->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td class="px-4 py-3">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.vouchers.show', $v->id) }}"
                               class="w-8 h-8 flex items-center justify-center rounded-lg
                                      bg-[#F2D4C2] text-[#A65005] hover:bg-[#D99C79] transition"
                               title="Detail">
                                <i class='bx bx-show text-base'></i>
                            </a>
                            <a href="{{ route('admin.vouchers.edit', $v->id) }}"
                               class="w-8 h-8 flex items-center justify-center rounded-lg
                                      text-[#F2D4C2] hover:opacity-80 transition"
                               style="background:#592202"
                               title="Edit">
                                <i class='bx bx-edit text-base'></i>
                            </a>
                            <form action="{{ route('admin.vouchers.destroy', $v->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus voucher {{ $v->code }}?')">
                                @csrf @method('DELETE')
                                <button class="w-8 h-8 flex items-center justify-center rounded-lg
                                               bg-red-50 text-red-500 hover:bg-red-500 hover:text-white
                                               border border-red-200 transition" title="Hapus">
                                    <i class='bx bx-trash text-base'></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-16 text-center">
                        <i class="bx bxs-coupon text-5xl text-[#F2D4C2] block mb-2"></i>
                        <p class="text-[#D99C79]">Tidak ada voucher</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MOBILE CARDS --}}
    <div class="block md:hidden space-y-4">
        @forelse($vouchers as $v)
        <div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm overflow-hidden">
            <div class="h-1" style="background:linear-gradient(90deg,#A65005,#592202)"></div>
            <div class="p-4">
                <div class="flex items-start justify-between gap-3 mb-3">
                    <span class="inline-flex items-center gap-1.5 font-bold text-sm text-[#260101]
                                 bg-[#F2D4C2] px-3 py-1.5 rounded-full">
                        <i class="bx bxs-purchase-tag text-[#A65005] text-xs"></i>
                        {{ $v->code }}
                    </span>
                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full flex-shrink-0
                        {{ $v->is_active ? 'bg-emerald-100 text-emerald-700' : 'bg-gray-100 text-gray-500' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $v->is_active ? 'bg-emerald-500' : 'bg-gray-400' }}"></span>
                        {{ $v->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                <div class="grid grid-cols-2 gap-2 text-xs mb-3">
                    <div>
                        <p class="text-[#D99C79]">Tipe</p>
                        <p class="font-semibold text-[#260101]">{{ ucfirst($v->type) }}</p>
                    </div>
                    <div>
                        <p class="text-[#D99C79]">Nilai</p>
                        <p class="font-bold text-[#A65005]">
                            {{ $v->type === 'percent' ? $v->value.'%' : 'Rp '.number_format($v->value,0,',','.') }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[#D99C79]">Min. Pembelian</p>
                        <p class="font-semibold text-[#260101]">
                            {{ $v->min_purchase ? 'Rp '.number_format($v->min_purchase,0,',','.') : '—' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-[#D99C79]">Kedaluwarsa</p>
                        <p class="font-semibold text-[#260101]">{{ $v->expired_at->format('d M Y') }}</p>
                    </div>
                </div>

                <div class="flex gap-2">
                    <a href="{{ route('admin.vouchers.show', $v->id) }}"
                       class="flex-1 py-2 text-center text-xs font-semibold rounded-xl
                              bg-[#F2D4C2] text-[#A65005] hover:bg-[#D99C79] transition">
                        <i class='bx bx-show mr-1'></i>Detail
                    </a>
                    <a href="{{ route('admin.vouchers.edit', $v->id) }}"
                       class="flex-1 py-2 text-center text-xs font-semibold rounded-xl text-[#F2D4C2] transition"
                       style="background:#592202">
                        <i class='bx bx-edit mr-1'></i>Edit
                    </a>
                    <form action="{{ route('admin.vouchers.destroy', $v->id) }}" method="POST" class="flex-1"
                          onsubmit="return confirm('Hapus voucher ini?')">
                        @csrf @method('DELETE')
                        <button class="w-full py-2 text-xs font-semibold rounded-xl
                                       bg-red-50 text-red-500 border border-red-200
                                       hover:bg-red-500 hover:text-white transition">
                            <i class='bx bx-trash mr-1'></i>Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-16">
            <i class="bx bxs-coupon text-5xl text-[#F2D4C2] block mb-2"></i>
            <p class="text-[#D99C79]">Tidak ada voucher</p>
        </div>
        @endforelse
    </div>

    <div>{{ $vouchers->links() }}</div>

</div>
@endsection