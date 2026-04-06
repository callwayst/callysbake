@if(session('status'))
    <div class="p-3 bg-green-100 text-green-700 rounded-xl text-sm">
        {{ session('status') }}
    </div>
@endif

<form method="POST"
      action="{{ route('profile.update') }}"
      class="space-y-4 sm:space-y-5">

    @csrf
    @method('PUT')

    <div>
        <label class="block text-sm font-medium mb-1">Name</label>
        <input type="text"
               name="name"
               value="{{ old('name', $user->name) }}"
               class="w-full border rounded-xl px-3 py-2 sm:py-3 focus:ring-2 focus:ring-[#A65005]">
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Email</label>
        <input type="email"
               name="email"
               value="{{ old('email', $user->email) }}"
               class="w-full border rounded-xl px-3 py-2 sm:py-3 focus:ring-2 focus:ring-[#A65005]">
    </div>

    <button
        class="w-full sm:w-auto px-5 py-2.5 bg-[#A65005] text-white rounded-xl font-semibold hover:brightness-110">
        Update Profile
    </button>
</form>