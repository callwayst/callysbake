@if($reviews->count())
  <div class="space-y-4 mb-6">
    @foreach($reviews as $review)
      <div class="flex gap-3 pb-4 border-b border-[#F2D4C2] last:border-0">
        {{-- Avatar --}}
        <div class="w-9 h-9 rounded-full bg-gradient-to-br from-[#F2D4C2] to-[#D99C79] flex items-center justify-center text-[#A65005] font-bold text-sm flex-shrink-0">
          {{ strtoupper(substr($review->user->name ?? 'G', 0, 1)) }}
        </div>
        <div class="flex-1 min-w-0">
          <div class="flex items-center justify-between gap-2 flex-wrap">
            <p class="font-semibold text-sm text-[#260101]">{{ $review->user->name ?? 'Guest' }}</p>
            <div class="flex gap-0.5">
              @for($i=1; $i<=5; $i++)
                <i class="bx {{ $i <= $review->rating ? 'bxs-star text-amber-400' : 'bx-star text-gray-200' }} text-xs"></i>
              @endfor
            </div>
          </div>
          <p class="text-sm text-[#592202] mt-1">{{ $review->comment ?? '-' }}</p>
          <p class="text-[0.65rem] text-[#D99C79] mt-1">{{ $review->created_at->diffForHumans() }}</p>
        </div>
      </div>
    @endforeach
  </div>
@else
  <div class="text-center py-8 mb-6">
    <div class="text-4xl mb-2">⭐</div>
    <p class="text-sm text-[#D99C79]">Belum ada ulasan. Jadilah yang pertama!</p>
  </div>
@endif