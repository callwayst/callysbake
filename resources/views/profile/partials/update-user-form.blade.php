<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- ================= FORM ================= --}}
    <form method="POST"
          action="{{ route('profile.update') }}"
          class="space-y-4 order-1">

        @csrf
        @method('PUT')

        <div>
            <label class="block text-sm font-medium mb-1">Phone</label>
            <input type="text"
                   name="phone"
                   value="{{ old('phone', $user->phone ?? '') }}"
                   class="w-full border rounded-xl px-3 py-2 sm:py-3 focus:ring-2 focus:ring-[#A65005]">
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Default Address</label>
            <textarea rows="3"
                      name="address"
                      class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-[#A65005]">{{ old('address', $user->address ?? '') }}</textarea>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Bio</label>
            <textarea rows="3"
                      name="bio"
                      class="w-full border rounded-xl px-3 py-2 focus:ring-2 focus:ring-[#A65005]">{{ old('bio', $user->bio ?? '') }}</textarea>
        </div>

        <button
            class="w-full sm:w-auto bg-[#A65005] text-white px-5 py-2.5 rounded-xl font-semibold hover:brightness-110">
            Save Changes
        </button>
    </form>



    {{-- ================= INFO CARD ================= --}}
    <div class="order-2 bg-[#F7E3D8] rounded-xl p-5 text-sm shadow-inner space-y-3">

        <h3 class="font-semibold text-[#A65005] mb-3">Account Info</h3>

        <div class="flex justify-between">
            <span class="text-gray-500">Role</span>
            <span class="font-medium capitalize">{{ $user->role }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-500">Status</span>
            <span class="font-medium">{{ $user->status ? 'Active' : 'Inactive' }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-500">Joined</span>
            <span class="font-medium">{{ $user->created_at->format('d M Y') }}</span>
        </div>

        <div class="flex justify-between">
            <span class="text-gray-500">Email Verified</span>
            <span class="font-medium">{{ $user->email_verified_at ? 'Yes' : 'No' }}</span>
        </div>

    </div>
</div>