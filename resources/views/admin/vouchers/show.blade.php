@extends('layouts.admin')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-10 py-4">

    <nav class="flex items-center gap-2 text-sm mb-6">
        <a href="{{ route('admin.vouchers.index') }}"
           class="text-[#D99C79] hover:text-[#A65005] transition">Vouchers</a>
        <span class="text-[#D99C79]">/</span>
        <span class="text-[#A65005] font-semibold">Detail</span>
    </nav>

    <div class="max-w-2xl mx-auto">
        {{-- Card Header --}}
        <div class="rounded-t-2xl px-6 py-5 flex items-center justify-between"
             style="background:linear-gradient(135deg,#A65005,#592202)">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="bx bxs-coupon text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white">Detail Voucher</h2>
                    <p class="text-white/60 text-xs">Informasi lengkap voucher</p>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-bold px-3 py-1.5 rounded-full
                    {{ $voucher->is_active ? 'bg-emerald-400/30 text-emerald-100' : 'bg-white/20 text-white/60' }}">
                    {{ $voucher->is_active ? 'Active' : 'Inactive' }}
                </span>
                <a href="{{ route('admin.vouchers.index') }}"
                   class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/10
                          hover:bg-white/20 text-white text-sm font-medium transition">
                    ← Kembali
                </a>
            </div>
        </div>

        {{-- Body --}}
        <div class="bg-white rounded-b-2xl shadow-lg border border-[#F2D4C2] border-t-0 p-6 space-y-6">

            {{-- Code highlight --}}
            <div class="text-center bg-[#F2D4C2]/40 rounded-2xl py-5 border border-[#F2D4C2]">
                <p class="text-xs text-[#D99C79] uppercase tracking-widest mb-1">Kode Voucher</p>
                <p class="text-3xl font-bold text-[#A65005] tracking-widest">{{ $voucher->code }}</p>
            </div>

            {{-- Info grid --}}
            <div class="grid grid-cols-2 gap-3">
                @foreach([
                    ['Tipe',           ucfirst($voucher->type),                                          'bxs-tag'],
                    ['Nilai',          $voucher->type==='percent' ? $voucher->value.'%' : 'Rp '.number_format($voucher->value,0,',','.'), 'bxs-discount'],
                    ['Min. Pembelian', $voucher->min_purchase ? 'Rp '.number_format($voucher->min_purchase,0,',','.') : '—', 'bxs-cart'],
                    ['Max Diskon',     $voucher->max_discount  ? 'Rp '.number_format($voucher->max_discount,0,',','.') : '—',  'bxs-wallet'],
                    ['Usage Limit',    $voucher->usage_limit   ?? '—',                                  'bxs-user-check'],
                    ['Terpakai',       $voucher->used_count,                                             'bxs-check-circle'],
                    ['Kedaluwarsa',    $voucher->expired_at->format('d M Y'),                           'bxs-calendar'],
                    ['Dibuat',         $voucher->created_at->format('d M Y'),                           'bxs-time'],
                ] as [$label, $value, $icon])
                <div class="bg-[#F2D4C2]/30 rounded-xl px-4 py-3 border border-[#F2D4C2] flex items-start gap-3">
                    <i class="bx {{ $icon }} text-[#A65005] text-lg mt-0.5 flex-shrink-0"></i>
                    <div>
                        <p class="text-xs text-[#D99C79]">{{ $label }}</p>
                        <p class="text-sm font-bold text-[#260101]">{{ $value }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="border-t border-[#F2D4C2]"></div>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row gap-3">
                <a href="{{ route('admin.vouchers.edit', $voucher->id) }}"
                   class="flex-1 py-2.5 text-center rounded-xl text-sm font-bold text-white
                          shadow hover:shadow-md hover:-translate-y-0.5 transition-all"
                   style="background:linear-gradient(135deg,#A65005,#592202)">
                    <i class="bx bxs-edit mr-1.5"></i> Edit Voucher
                </a>
                <form action="{{ route('admin.vouchers.destroy', $voucher->id) }}" method="POST" class="flex-1"
                      onsubmit="return confirm('Hapus voucher {{ $voucher->code }}?')">
                    @csrf @method('DELETE')
                    <button class="w-full py-2.5 rounded-xl text-sm font-bold
                                   bg-red-50 text-red-600 hover:bg-red-600 hover:text-white
                                   border border-red-200 transition">
                        <i class="bx bxs-trash mr-1.5"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection