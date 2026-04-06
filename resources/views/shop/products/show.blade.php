@extends('layouts.app')

@section('content')

{{-- TOAST --}}
@if(session('success'))
  <div id="toast"
       class="fixed top-6 right-6 z-50 flex items-center gap-3 px-5 py-4 rounded-2xl shadow-2xl text-white text-sm transition-all duration-500"
       style="background:linear-gradient(135deg,#A65005,#592202);border:1px solid rgba(242,212,194,0.3)">
    <div class="w-8 h-8 rounded-full bg-white/20 flex items-center justify-center flex-shrink-0">
      <i class='bx bx-check text-[#F2D4C2] text-lg'></i>
    </div>
    <div>
      <p class="text-[#F2D4C2] font-bold leading-tight">Berhasil Ditambahkan!</p>
      <p class="text-[#D99C79] text-xs font-normal mt-0.5">Produk berhasil masuk keranjang</p>
    </div>
    <button onclick="this.closest('#toast').remove()" class="ml-2 text-[#D99C79] hover:text-white">
      <i class='bx bx-x text-lg'></i>
    </button>
  </div>
  <script>
    setTimeout(() => {
      const t = document.getElementById('toast');
      if (t) { t.style.opacity='0'; t.style.transform='translateX(20px)'; setTimeout(()=>t.remove(),500); }
    }, 3500);
  </script>
@endif

<div class="max-w-5xl mx-auto space-y-6">

  {{-- BREADCRUMB --}}
  <div class="flex items-center gap-2 text-xs text-[#D99C79]">
    <a href="{{ route('dashboard') }}" class="hover:text-[#A65005] transition">Home</a>
    <i class='bx bx-chevron-right'></i>
    <a href="{{ route('products.index') }}" class="hover:text-[#A65005] transition">Produk</a>
    <i class='bx bx-chevron-right'></i>
    <span class="text-[#A65005] font-medium truncate max-w-[160px]">{{ $product->name }}</span>
  </div>

  {{-- TOP SECTION --}}
  <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- GALLERY --}}
    <div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm p-5">
      <div class="aspect-square rounded-xl overflow-hidden bg-[#F9EDE3] mb-4 border border-[#F2D4C2]">
        <img id="mainImg" src="{{ asset('storage/'.$product->image) }}" alt="{{ $product->name }}"
             class="w-full h-full object-cover transition duration-300">
      </div>
      {{-- Thumbnails (satu untuk sekarang, siap expand) --}}
      <div class="flex gap-2">
        <div class="w-16 h-16 rounded-xl overflow-hidden border-2 border-[#A65005] cursor-pointer">
          <img src="{{ asset('storage/'.$product->image) }}"
               class="w-full h-full object-cover"
               onclick="document.getElementById('mainImg').src=this.src">
        </div>
      </div>
    </div>

    {{-- INFO --}}
    <div class="flex flex-col gap-4">

      {{-- Name & Rating --}}
      <div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm p-5">
        <span class="text-xs font-semibold text-[#A65005] bg-[#FDF3EC] px-2.5 py-1 rounded-full">
          {{ $product->category->name ?? 'Uncategorized' }}
        </span>
        <h1 class="text-2xl font-black text-[#592202] mt-3 mb-2 leading-tight">{{ $product->name }}</h1>
        <div class="flex items-center gap-2 mb-3">
          <div class="flex gap-0.5">
            @for($i=1; $i<=5; $i++)
              <i class="bx {{ $i <= round($avgRating) ? 'bxs-star text-amber-400' : 'bx-star text-gray-200' }} text-sm"></i>
            @endfor
          </div>
          <span class="text-xs text-[#D99C79]">{{ number_format($avgRating,1) }}/5 • {{ $reviewCount }} reviews</span>
        </div>
        <p class="text-3xl font-black text-[#A65005]">
          Rp {{ number_format($product->price, 0, ',', '.') }}
        </p>
      </div>

      {{-- Stock & Delivery --}}
      <div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm p-4 flex items-center gap-4">
        <div class="flex items-center gap-2 text-sm text-[#260101]">
          <i class='bx bx-box text-[#A65005]'></i>
          Stok: <span class="font-bold">{{ $product->stock }}</span>
        </div>
        <div class="h-4 w-px bg-[#F2D4C2]"></div>
        <div class="flex items-center gap-1.5 bg-green-50 border border-green-200 px-3 py-1.5 rounded-xl text-xs text-green-700 font-semibold">
          <i class='bx bxs-truck'></i> Est. 1–3 hari
        </div>
      </div>

      {{-- Add to Cart --}}
      <div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm p-5">
        @if($variant)
          <form method="POST" action="{{ route('cart.store') }}" class="flex gap-3 items-center">
            @csrf
            <input type="hidden" name="variant_id" value="{{ $variant->id }}">
            <input type="number" name="qty" value="1" min="1" max="{{ $variant->stock }}"
                   class="border-2 border-[#F2D4C2] focus:border-[#A65005] outline-none rounded-xl px-3 py-2.5 w-24 text-center font-semibold text-[#260101] transition">
            <button type="submit"
                    class="flex-1 flex items-center justify-center gap-2 text-white font-bold py-2.5 px-5 rounded-xl transition hover:opacity-90 hover:-translate-y-0.5 shadow-md"
                    style="background:linear-gradient(135deg,#A65005,#592202)">
              <i class='bx bx-cart-add text-lg'></i> Tambah ke Keranjang
            </button>
          </form>
        @else
          <p class="text-red-500 text-sm font-medium flex items-center gap-2">
            <i class='bx bx-error-circle'></i> Variant tidak tersedia
          </p>
        @endif
        <a href="{{ route('products.index') }}"
           class="mt-3 flex items-center justify-center gap-1.5 text-xs font-medium text-[#D99C79] hover:text-[#A65005] transition">
          <i class='bx bx-arrow-back'></i> Kembali ke produk
        </a>
      </div>

      {{-- Product Info Grid --}}
      <div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm p-5">
        <h2 class="text-xs font-black text-[#800000] uppercase tracking-widest mb-4 flex items-center gap-2">
          <i class='bx bx-info-circle text-[#A65005]'></i> Informasi Produk
        </h2>
        <div class="grid grid-cols-2 gap-2 text-xs">
          @foreach([
            ['label'=>'Kategori',  'val'=>$product->category->name ?? '-'],
            ['label'=>'Tipe',      'val'=>$product->type ?? '-'],
            ['label'=>'Berat',     'val'=>($product->weight ?? '-').' kg'],
            ['label'=>'Promo',     'val'=>$product->promo ?? 'Tidak ada'],
            ['label'=>'Pengiriman','val'=>'1-3 hari (JNE/TIKI)'],
            ['label'=>'Retur',     'val'=>'7 hari setelah terima'],
          ] as $info)
            <div class="bg-[#FDF8F4] rounded-xl p-3 border border-[#F2D4C2]">
              <p class="text-[#D99C79] uppercase tracking-wide font-semibold mb-1">{{ $info['label'] }}</p>
              <p class="font-bold text-[#260101]">{{ $info['val'] }}</p>
            </div>
          @endforeach
        </div>
      </div>

    </div>
  </div>

  {{-- TABS --}}
  <div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm p-6">
    <div class="flex gap-1 border-b-2 border-[#F2D4C2] mb-6">
      <button class="tab-btn py-2 px-5 text-sm font-bold text-[#A65005] border-b-2 border-[#A65005] -mb-0.5 rounded-t-lg transition focus:outline-none"
              data-tab="desc">
        Deskripsi
      </button>
      <button class="tab-btn py-2 px-5 text-sm font-medium text-[#D99C79] hover:text-[#A65005] -mb-0.5 rounded-t-lg transition focus:outline-none"
              data-tab="reviews">
        Ulasan ({{ $reviewCount }})
      </button>
    </div>

    <div id="desc" class="tab-pane text-[#260101] text-sm leading-relaxed">
      {{ $product->description ?? 'Tidak ada deskripsi.' }}
    </div>
    <div id="reviews" class="tab-pane hidden">
      @include('shop.products.partials.review-list', ['reviews' => $product->reviews])
      @include('shop.products.partials.review-form', ['product' => $product])
    </div>
  </div>

</div>

<script>
document.querySelectorAll('.tab-btn').forEach(btn => {
  btn.addEventListener('click', () => {
    document.querySelectorAll('.tab-btn').forEach(b => {
      b.classList.remove('text-[#A65005]','border-b-2','border-[#A65005]','font-bold');
      b.classList.add('text-[#D99C79]','font-medium');
    });
    document.querySelectorAll('.tab-pane').forEach(p => p.classList.add('hidden'));
    btn.classList.add('text-[#A65005]','border-b-2','border-[#A65005]','font-bold');
    btn.classList.remove('text-[#D99C79]','font-medium');
    document.getElementById(btn.dataset.tab).classList.remove('hidden');
  });
});
</script>

@endsection