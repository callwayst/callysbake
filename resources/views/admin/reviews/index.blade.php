@extends('layouts.admin')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-10 py-4 space-y-8">

    {{-- HEADER --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h1 class="text-3xl font-bold text-[#260101] flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-11 h-11 rounded-2xl mt-4"
                      style="background:linear-gradient(135deg,#A65005,#592202)">
                    <i class='bx bxs-star text-white text-2xl'></i>
                </span>
                Manage Reviews
            </h1>
            <p class="text-sm text-[#D99C79] mt-1 ml-14">Moderasi ulasan dari pelanggan</p>
        </div>
    </div>

    {{-- FILTER --}}
    <form method="GET"
          class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm p-4
                 flex flex-col md:flex-row gap-3 items-center flex-wrap">

        {{-- Product --}}
        <select name="product_id"
                class="border border-[#D99C79] rounded-xl px-4 py-2.5 text-sm w-full md:w-52
                       focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]"
                onchange="this.form.submit()">
            <option value="">Semua Produk</option>
            @foreach($products as $p)
                <option value="{{ $p->id }}" {{ request('product_id')==$p->id?'selected':'' }}>
                    {{ $p->name }}
                </option>
            @endforeach
        </select>

        {{-- Status --}}
        <select name="status"
                class="border border-[#D99C79] rounded-xl px-4 py-2.5 text-sm w-full md:w-40
                       focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]"
                onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="active" {{ request('status')=='active'?'selected':'' }}>Active</option>
            <option value="hidden" {{ request('status')=='hidden'?'selected':'' }}>Hidden</option>
        </select>

        {{-- Rating --}}
        <select name="rating"
                class="border border-[#D99C79] rounded-xl px-4 py-2.5 text-sm w-full md:w-36
                       focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]"
                onchange="this.form.submit()">
            <option value="">Semua Rating</option>
            @for($i=1;$i<=5;$i++)
                <option value="{{ $i }}" {{ request('rating')==$i?'selected':'' }}>{{ $i }} ★</option>
            @endfor
        </select>

        {{-- Search --}}
        <div class="relative flex-1 w-full">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari user atau komentar..."
                   class="w-full border border-[#D99C79] rounded-xl pl-10 pr-4 py-2.5 text-sm
                          focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005] transition"
                   onkeydown="if(event.key==='Enter'){this.form.submit()}">
            <svg class="absolute left-3 top-3 w-4 h-4 text-[#D99C79]"
                 fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
            </svg>
        </div>

        <div class="flex gap-2 w-full md:w-auto">
            <button type="submit"
                    class="flex-1 md:flex-none px-5 py-2.5 rounded-xl text-white text-sm font-semibold shadow transition"
                    style="background:#A65005">
                Filter
            </button>
            @if(request()->hasAny(['product_id','status','rating','search']))
            <a href="{{ route('admin.reviews.index') }}"
               class="flex-1 md:flex-none px-5 py-2.5 rounded-xl text-sm font-semibold text-center
                      border border-[#D99C79] text-[#A65005] hover:bg-[#F2D4C2] transition">
                Reset
            </a>
            @endif
        </div>
    </form>

    {{-- DESKTOP TABLE --}}
    <div class="hidden md:block bg-white rounded-2xl border border-[#F2D4C2] shadow-sm overflow-x-auto">
        <table class="min-w-full divide-y divide-[#F2D4C2]">
            <thead>
                <tr style="background:linear-gradient(135deg,#A65005,#592202)">
                    @foreach(['User','Produk','Rating','Komentar','Tanggal','Status','Aksi'] as $h)
                    <th class="px-4 py-3.5 text-left text-xs font-bold uppercase tracking-wider text-white whitespace-nowrap">
                        {{ $h }}
                    </th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-[#F2D4C2]">
                @forelse($reviews as $review)
                <tr class="hover:bg-[#F2D4C2]/30 transition">
                    <td class="px-4 py-3">
                        <p class="text-sm font-semibold text-[#260101]">{{ $review->user->name }}</p>
                        <p class="text-xs text-[#D99C79]">{{ $review->user->email }}</p>
                    </td>
                    <td class="px-4 py-3 text-sm text-[#260101]">{{ $review->product->name }}</td>
                    <td class="px-4 py-3">
                        <div class="flex gap-0.5">
                            @for($i=1;$i<=5;$i++)
                                <span class="text-sm {{ $i <= $review->rating ? 'text-amber-400' : 'text-[#F2D4C2]' }}">★</span>
                            @endfor
                        </div>
                    </td>
                    <td class="px-4 py-3 max-w-xs">
                        <p class="text-sm text-gray-600 truncate">{{ $review->comment }}</p>
                    </td>
                    <td class="px-4 py-3 text-xs text-[#D99C79] whitespace-nowrap">
                        {{ $review->created_at->format('d M Y') }}
                    </td>
                    <td class="px-4 py-3">
                        <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full
                            {{ $review->status=='hidden'
                                ? 'bg-gray-100 text-gray-500'
                                : 'bg-emerald-100 text-emerald-700' }}">
                            <span class="w-1.5 h-1.5 rounded-full
                                {{ $review->status=='hidden' ? 'bg-gray-400' : 'bg-emerald-500' }}"></span>
                            {{ ucfirst($review->status) }}
                        </span>
                    </td>
                    <td class="px-4 py-3 min-w-[120px]">
                        <div class="flex gap-2">
                            <a href="{{ route('admin.reviews.show', $review->id) }}"
                               class="w-8 h-8 flex items-center justify-center rounded-lg
                                      bg-[#F2D4C2] text-[#A65005] hover:bg-[#D99C79] transition"
                               title="Detail">
                                <i class='bx bx-show text-base'></i>
                            </a>
                            <form action="{{ route('admin.reviews.toggle', $review->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg transition
                                               {{ $review->status=='active'
                                                   ? 'bg-amber-100 text-amber-600 hover:bg-amber-500 hover:text-white'
                                                   : 'bg-emerald-100 text-emerald-600 hover:bg-emerald-500 hover:text-white' }}"
                                        title="{{ $review->status=='active' ? 'Hide' : 'Unhide' }}">
                                    <i class='bx {{ $review->status=="active" ? "bx-hide" : "bx-show" }} text-base'></i>
                                </button>
                            </form>
                            <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST"
                                  onsubmit="return confirm('Hapus review ini?')">
                                @csrf @method('DELETE')
                                <button type="submit"
                                        class="w-8 h-8 flex items-center justify-center rounded-lg
                                               bg-red-50 text-red-500 hover:bg-red-500 hover:text-white
                                               border border-red-200 transition"
                                        title="Hapus">
                                    <i class='bx bx-trash text-base'></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="py-16 text-center">
                        <i class="bx bxs-star text-5xl text-[#F2D4C2] block mb-2"></i>
                        <p class="text-[#D99C79]">Tidak ada review ditemukan</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- MOBILE CARDS --}}
    <div class="block md:hidden space-y-4">
        @forelse($reviews as $review)
        <div class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm overflow-hidden">
            <div class="h-1 {{ $review->status=='hidden' ? 'bg-gray-200' : '' }}"
                 style="{{ $review->status!='hidden' ? 'background:linear-gradient(90deg,#A65005,#592202)' : '' }}">
            </div>
            <div class="p-4">
                <div class="flex items-start justify-between gap-3 mb-2">
                    <div>
                        <p class="font-bold text-[#260101] text-sm">{{ $review->user->name }}</p>
                        <p class="text-xs text-[#D99C79]">{{ $review->product->name }}</p>
                    </div>
                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full flex-shrink-0
                        {{ $review->status=='hidden' ? 'bg-gray-100 text-gray-500' : 'bg-emerald-100 text-emerald-700' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $review->status=='hidden' ? 'bg-gray-400' : 'bg-emerald-500' }}"></span>
                        {{ ucfirst($review->status) }}
                    </span>
                </div>

                <div class="flex gap-0.5 mb-2">
                    @for($i=1;$i<=5;$i++)
                        <span class="text-sm {{ $i <= $review->rating ? 'text-amber-400' : 'text-[#F2D4C2]' }}">★</span>
                    @endfor
                    <span class="text-xs text-[#D99C79] ml-2 self-center">
                        {{ $review->created_at->format('d M Y') }}
                    </span>
                </div>

                <p class="text-sm text-gray-600 line-clamp-2 mb-3">{{ $review->comment }}</p>

                <div class="flex gap-2">
                    <a href="{{ route('admin.reviews.show', $review->id) }}"
                       class="flex-1 py-2 text-center text-xs font-semibold rounded-xl
                              bg-[#F2D4C2] text-[#A65005] hover:bg-[#D99C79] transition">
                        <i class='bx bx-show mr-1'></i>Detail
                    </a>
                    <form action="{{ route('admin.reviews.toggle', $review->id) }}" method="POST" class="flex-1">
                        @csrf @method('PATCH')
                        <button class="w-full py-2 text-xs font-semibold rounded-xl transition
                            {{ $review->status=='active'
                                ? 'bg-amber-100 text-amber-600 hover:bg-amber-500 hover:text-white'
                                : 'bg-emerald-100 text-emerald-600 hover:bg-emerald-500 hover:text-white' }}">
                            <i class='bx {{ $review->status=="active" ? "bx-hide" : "bx-show" }} mr-1'></i>
                            {{ $review->status=='active' ? 'Hide' : 'Unhide' }}
                        </button>
                    </form>
                    <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" class="flex-1"
                          onsubmit="return confirm('Hapus review ini?')">
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
            <i class="bx bxs-star text-5xl text-[#F2D4C2] block mb-2"></i>
            <p class="text-[#D99C79]">Tidak ada review ditemukan</p>
        </div>
        @endforelse
    </div>

    {{-- Pagination --}}
    <div>{{ $reviews->links() }}</div>

</div>
@endsection