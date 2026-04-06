@extends('layouts.admin')

@section('content')

<div class="w-full max-w-6xl mx-auto px-4 sm:px-6 py-6 sm:py-10 space-y-8">

    {{-- ================= HEADER ================= --}}
    <h1 class="text-2xl sm:text-4xl font-bold text-[#A65005] flex items-center gap-2">
        <i class='bx bx-user'></i>
        Admin Profile
    </h1>


    {{-- ================= SUCCESS MESSAGE ================= --}}
    @if(session('status'))
        <div class="p-3 bg-green-100 text-green-700 rounded-xl text-sm">
            {{ session('status') }}
        </div>
    @endif


    {{-- ===================================================== --}}
    {{-- HERO CARD --}}
    {{-- ===================================================== --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm
                p-5 sm:p-7
                flex flex-col lg:flex-row lg:items-center lg:justify-between
                gap-6 hover:shadow-xl transition">

        {{-- Identity --}}
        <div class="flex items-center gap-4">

            <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-full bg-[#F2D4C2]
                        flex items-center justify-center
                        text-2xl sm:text-3xl font-bold text-[#A65005]">
                {{ strtoupper(substr(auth()->user()->name,0,1)) }}
            </div>

            <div>
                <h2 class="text-lg sm:text-xl font-bold text-[#260101]">
                    {{ auth()->user()->name }}
                </h2>

                <p class="text-xs sm:text-sm text-gray-500 break-all">
                    {{ auth()->user()->email }}
                </p>

                <div class="mt-2 flex gap-2 flex-wrap">

                    <span class="px-3 py-1 text-xs rounded-full bg-[#A65005] text-white">
                        {{ ucfirst(auth()->user()->role ?? 'Admin') }}
                    </span>

                    @if(auth()->user()->email_verified_at)
                        <span class="px-3 py-1 text-xs rounded-full bg-green-100 text-green-700">
                            Verified
                        </span>
                    @endif

                </div>
            </div>
        </div>


        {{-- Metadata --}}
        <div class="grid grid-cols-2 gap-x-6 gap-y-3 text-xs sm:text-sm text-gray-600">

            <div>
                <span class="text-gray-400 block text-xs">Joined</span>
                {{ auth()->user()->created_at->format('d M Y') }}
            </div>

            <div>
                <span class="text-gray-400 block text-xs">Status</span>
                <span class="text-green-600 font-medium">Active</span>
            </div>

            <div>
                <span class="text-gray-400 block text-xs">Role</span>
                {{ ucfirst(auth()->user()->role ?? 'Admin') }}
            </div>

            <div>
                <span class="text-gray-400 block text-xs">Verified</span>
                {{ auth()->user()->email_verified_at ? 'Yes' : 'No' }}
            </div>

        </div>
    </div>



    {{-- ===================================================== --}}
    {{-- FORMS GRID --}}
    {{-- ===================================================== --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Account Settings --}}
        <div class="bg-white rounded-2xl p-5 sm:p-7 border border-gray-100 shadow-sm hover:shadow-xl transition">
            <h3 class="text-base sm:text-lg font-semibold text-[#A65005] mb-5">
                Account Settings
            </h3>

            @include('admin.profile.partials.update-user-form')
        </div>


        {{-- Change Password --}}
        <div class="bg-white rounded-2xl p-5 sm:p-7 border border-gray-100 shadow-sm hover:shadow-xl transition">
            <h3 class="text-base sm:text-lg font-semibold text-[#A65005] mb-5">
                Change Password
            </h3>

            @include('admin.profile.partials.update-password-form')
        </div>


        {{-- Profile Details --}}
        <div class="md:col-span-2 bg-white rounded-2xl p-5 sm:p-7 border border-gray-100 shadow-sm hover:shadow-xl transition">
            <h3 class="text-base sm:text-lg font-semibold text-[#A65005] mb-5">
                Profile Details
            </h3>

            @include('admin.profile.partials.update-profile-form')
        </div>

    </div>

</div>

@endsection