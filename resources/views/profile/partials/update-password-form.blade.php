<form method="POST"
      action="{{ route('profile.password.update') }}"
      class="space-y-4 sm:space-y-5 mt-4 sm:mt-6">

    @csrf
    @method('PUT')

    <div>
        <label class="block text-sm font-medium mb-1">Current Password</label>
        <input type="password"
               name="current_password"
               class="w-full border rounded-xl px-3 py-2 sm:py-3 focus:ring-2 focus:ring-[#A65005]">
        @error('current_password')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">New Password</label>
        <input type="password"
               name="password"
               class="w-full border rounded-xl px-3 py-2 sm:py-3 focus:ring-2 focus:ring-[#A65005]">
        @error('password')
            <p class="text-red-500 text-sm">{{ $message }}</p>
        @enderror
    </div>

    <div>
        <label class="block text-sm font-medium mb-1">Confirm New Password</label>
        <input type="password"
               name="password_confirmation"
               class="w-full border rounded-xl px-3 py-2 sm:py-3 focus:ring-2 focus:ring-[#A65005]">
    </div>

    <button type="submit"
        class="w-full sm:w-auto px-5 py-2.5 bg-[#A65005] text-white rounded-xl font-semibold hover:brightness-110">
        Update Password
    </button>
</form>