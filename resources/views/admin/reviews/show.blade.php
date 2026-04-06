@extends('layouts.admin')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-10 py-4">

    {{-- Breadcrumb --}}
    <nav class="flex items-center gap-2 text-sm mb-6">
        <a href="{{ route('admin.reviews.index') }}"
           class="text-[#D99C79] hover:text-[#A65005] transition">Reviews</a>
        <span class="text-[#D99C79]">/</span>
        <span class="text-[#A65005] font-semibold">Detail</span>
    </nav>

    <div class="max-w-2xl mx-auto">

        {{-- Card Header --}}
        <div class="rounded-t-2xl px-6 py-5 flex items-center justify-between"
             style="background:linear-gradient(135deg,#A65005,#592202)">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-white/20 flex items-center justify-center">
                    <i class="bx bxs-star text-white text-xl"></i>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white">Detail Review</h2>
                    <p class="text-white/60 text-xs">Informasi lengkap ulasan</p>
                </div>
            </div>
            <a href="{{ route('admin.reviews.index') }}"
               class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/10
                      hover:bg-white/20 text-white text-sm font-medium transition">
                ← Kembali
            </a>
        </div>

        {{-- Card Body --}}
        <div class="bg-white rounded-b-2xl shadow-lg border border-[#F2D4C2] border-t-0 p-6 space-y-6">

            {{-- User & Product Info --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                @foreach([
                    ['label'=>'User',     'value'=> $review->user->name,             'icon'=>'bxs-user'],
                    ['label'=>'Email',    'value'=> $review->user->email,            'icon'=>'bxs-envelope'],
                    ['label'=>'Produk',   'value'=> $review->product->name,          'icon'=>'bxs-store'],
                    ['label'=>'Kategori', 'value'=> $review->product->category->name ?? 'N/A', 'icon'=>'bxs-tag'],
                    ['label'=>'Tanggal',  'value'=> $review->created_at->format('d M Y H:i'), 'icon'=>'bxs-calendar'],
                    ['label'=>'Approved', 'value'=> $review->approved ? 'Yes' : 'No','icon'=>'bxs-check-circle'],
                ] as $info)
                <div class="flex items-start gap-3 bg-[#F2D4C2]/30 rounded-xl px-4 py-3 border border-[#F2D4C2]">
                    <i class="bx {{ $info['icon'] }} text-[#A65005] text-lg mt-0.5 flex-shrink-0"></i>
                    <div>
                        <p class="text-xs text-[#D99C79]">{{ $info['label'] }}</p>
                        <p class="text-sm font-semibold text-[#260101]">{{ $info['value'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Rating & Status --}}
            <div class="flex items-center justify-between flex-wrap gap-4
                        bg-[#F2D4C2]/30 rounded-xl px-5 py-4 border border-[#F2D4C2]">
                <div>
                    <p class="text-xs text-[#D99C79] mb-1">Rating</p>
                    <div class="flex gap-1">
                        @for($i=1;$i<=5;$i++)
                            <span class="text-2xl {{ $i <= $review->rating ? 'text-amber-400' : 'text-[#F2D4C2]' }}">★</span>
                        @endfor
                        <span class="text-sm font-bold text-[#A65005] self-center ml-2">
                            {{ $review->rating }}/5
                        </span>
                    </div>
                </div>
                <span class="inline-flex items-center gap-1.5 text-sm font-bold px-4 py-2 rounded-full
                    {{ $review->status=='hidden'
                        ? 'bg-gray-100 text-gray-500 border border-gray-200'
                        : 'bg-emerald-100 text-emerald-700 border border-emerald-200' }}">
                    <span class="w-2 h-2 rounded-full {{ $review->status=='hidden' ? 'bg-gray-400' : 'bg-emerald-500' }}"></span>
                    {{ ucfirst($review->status) }}
                </span>
            </div>

            {{-- Comment --}}
            <div class="space-y-2">
                <p class="text-xs font-bold uppercase tracking-widest text-[#A65005] flex items-center gap-2">
                    <i class="bx bxs-comment-detail"></i> Komentar
                </p>
                <div class="bg-[#F2D4C2]/30 rounded-xl p-4 border-l-4 border-[#A65005]">
                    <p class="text-sm text-gray-700 leading-relaxed italic">
                        "{{ $review->comment }}"
                    </p>
                </div>
            </div>

            <div class="border-t border-[#F2D4C2]"></div>

            {{-- Actions --}}
            <div class="flex flex-col sm:flex-row gap-3">
                <form action="{{ route('admin.reviews.toggle', $review->id) }}" method="POST" class="flex-1">
                    @csrf @method('PATCH')
                    <button class="w-full py-2.5 rounded-xl text-sm font-bold transition
                        {{ $review->status=='active'
                            ? 'bg-amber-100 text-amber-600 hover:bg-amber-500 hover:text-white border border-amber-200'
                            : 'bg-emerald-100 text-emerald-700 hover:bg-emerald-500 hover:text-white border border-emerald-200' }}">
                        <i class='bx {{ $review->status=="active" ? "bx-hide" : "bx-show" }} mr-1.5'></i>
                        {{ $review->status=='active' ? 'Hide Review' : 'Unhide Review' }}
                    </button>
                </form>
                <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="flex-1"
                      onsubmit="return confirm('Yakin hapus review ini?')">
                    @csrf @method('DELETE')
                    <button class="w-full py-2.5 rounded-xl text-sm font-bold
                                   bg-red-50 text-red-600 hover:bg-red-600 hover:text-white
                                   border border-red-200 transition">
                        <i class='bx bxs-trash mr-1.5'></i> Hapus Review
                    </button>
                </form>
            </div>

        </div>
    </div>
</div>
@endsection