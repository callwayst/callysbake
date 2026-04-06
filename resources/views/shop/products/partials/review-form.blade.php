<div class="border-t-2 border-[#F2D4C2] pt-5">
  <h3 class="font-bold text-sm text-[#A65005] mb-4 flex items-center gap-2">
    <i class='bx bx-edit'></i> Tulis Ulasan
  </h3>

  <form method="POST" action="{{ route('reviews.store') }}" class="space-y-4">
    @csrf
    <input type="hidden" name="product_id" value="{{ $product->id }}">

    {{-- STAR RATING --}}
    <div>
      <label class="text-xs font-semibold text-[#D99C79] uppercase tracking-wide block mb-2">Rating</label>
      <div class="flex gap-1" id="starRating">
        @for($i=1; $i<=5; $i++)
          <button type="button" data-val="{{ $i }}"
                  class="star-btn text-3xl text-gray-200 hover:text-amber-400 transition-colors">
            <i class='bx bxs-star'></i>
          </button>
        @endfor
      </div>
      <input type="hidden" name="rating" id="ratingInput" value="0">
    </div>

    {{-- COMMENT --}}
    <div>
      <label class="text-xs font-semibold text-[#D99C79] uppercase tracking-wide block mb-2">Komentar</label>
      <textarea name="content" rows="3" placeholder="Bagaimana pengalaman kamu dengan produk ini?"
                class="w-full border-2 border-[#F2D4C2] focus:border-[#D99C79] outline-none rounded-xl px-4 py-3 text-sm text-[#260101] bg-[#fdfaf8] resize-none transition placeholder-[#D99C79]"></textarea>
    </div>

    <button type="submit"
            class="px-6 py-2.5 rounded-xl text-sm font-bold text-white transition hover:opacity-90 hover:-translate-y-0.5"
            style="background:linear-gradient(135deg,#A65005,#592202)">
      <i class='bx bx-send mr-1'></i> Kirim Ulasan
    </button>
  </form>
</div>

<script>
(function(){
  const btns = document.querySelectorAll('.star-btn');
  const input = document.getElementById('ratingInput');
  btns.forEach(btn => {
    btn.addEventListener('mouseenter', () => {
      btns.forEach(b => {
        b.classList.toggle('text-amber-400', b.dataset.val <= btn.dataset.val);
        b.classList.toggle('text-gray-200',  b.dataset.val >  btn.dataset.val);
      });
    });
    btn.addEventListener('click', () => {
      input.value = btn.dataset.val;
      btns.forEach(b => {
        b.classList.toggle('text-amber-400', b.dataset.val <= btn.dataset.val);
        b.classList.toggle('text-gray-200',  b.dataset.val >  btn.dataset.val);
      });
    });
  });
  document.getElementById('starRating').addEventListener('mouseleave', () => {
    const val = input.value;
    btns.forEach(b => {
      b.classList.toggle('text-amber-400', val > 0 && b.dataset.val <= val);
      b.classList.toggle('text-gray-200',  val == 0 || b.dataset.val > val);
    });
  });
})();
</script>