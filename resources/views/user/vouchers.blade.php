@extends('layouts.app')

@section('content')

{{-- HEADER --}}
<div class="rounded-2xl overflow-hidden mb-8"
     style="background:linear-gradient(135deg,#592202 0%,#A65005 60%,#D99C79 100%)">
  <div class="relative px-7 py-8">
    <div class="absolute right-6 bottom-0 text-[80px] opacity-10 leading-none select-none">🎟️</div>
    <p class="text-[#F2D4C2]/80 text-sm mb-1">Koleksi kamu</p>
    <h1 class="text-white text-2xl font-bold" style="font-family:'Dancing Script',cursive">
      Voucher Saya
    </h1>
  </div>
</div>

@if($vouchers->isEmpty())
  <div class="bg-white rounded-2xl border border-[#F2D4C2] p-16 text-center">
    <div class="text-6xl mb-4">🎟️</div>
    <p class="text-[#D99C79] font-medium">Belum ada voucher tersedia.</p>
  </div>
@else
  <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-5">
    @foreach($vouchers as $voucher)
      @php $owned = (bool)$voucher->user_id; @endphp
      <div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-200 overflow-hidden flex flex-col">

        {{-- TOP STRIP --}}
        <div class="px-5 pt-5 pb-4" style="background:linear-gradient(135deg,#fdf8f4,#F2D4C2)">
          <div class="flex items-start justify-between mb-2">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center"
                 style="background:linear-gradient(135deg,#A65005,#592202)">
              <i class='bx bxs-coupon text-[#F2D4C2] text-lg'></i>
            </div>
            <span class="text-[0.65rem] font-bold px-2.5 py-1 rounded-full
                         {{ $owned ? 'bg-green-100 text-green-700' : 'bg-[#A65005]/10 text-[#A65005]' }}">
              {{ $owned ? 'Dimiliki' : 'Tersedia' }}
            </span>
          </div>
          <p class="font-black text-[#592202] text-lg tracking-widest mt-1">{{ $voucher->code }}</p>
          <p class="text-[#A65005] font-bold text-xl mt-0.5">
            {{ $voucher->type == 'percent' ? $voucher->value.'%' : 'Rp '.number_format($voucher->value, 0, ',', '.') }}
            <span class="text-xs font-normal text-[#D99C79]">diskon</span>
          </p>
        </div>

        {{-- DIVIDER ZIGZAG --}}
        <div class="relative h-4 overflow-hidden">
          <svg viewBox="0 0 400 16" class="w-full h-full" preserveAspectRatio="none">
            <path d="M0,0 L20,16 L40,0 L60,16 L80,0 L100,16 L120,0 L140,16 L160,0 L180,16 L200,0 L220,16 L240,0 L260,16 L280,0 L300,16 L320,0 L340,16 L360,0 L380,16 L400,0 L400,16 L0,16 Z"
                  fill="#F2D4C2"/>
          </svg>
        </div>

        {{-- DETAIL --}}
        <div class="px-5 py-4 flex flex-col gap-2 flex-1">
          <div class="flex items-center gap-2 text-xs text-[#592202]">
            <i class='bx bx-shopping-bag text-[#D99C79]'></i>
            Min. belanja Rp {{ number_format($voucher->min_purchase, 0, ',', '.') }}
          </div>
          <div class="flex items-center gap-2 text-xs text-[#592202]">
            <i class='bx bx-calendar text-[#D99C79]'></i>
            Exp: {{ $voucher->expired_at->format('d M Y') }}
          </div>

          <div class="mt-auto pt-3">
            @if(!$owned)
              <form action="{{ route('user.vouchers.claim', $voucher->id) }}" method="POST">
                @csrf
                <button class="w-full py-2.5 rounded-xl text-sm font-bold text-white transition hover:opacity-90 hover:-translate-y-0.5"
                        style="background:linear-gradient(135deg,#A65005,#592202)">
                  Klaim Voucher
                </button>
              </form>
            @else
              <div class="w-full py-2.5 rounded-xl text-sm font-medium text-center
                          bg-green-50 text-green-600 border border-green-200">
                <i class='bx bx-check-circle mr-1'></i> Sudah Diklaim
              </div>
            @endif
          </div>
        </div>

      </div>
    @endforeach
  </div>
@endif

@endsection