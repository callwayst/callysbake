@extends('layouts.admin')

@section('content')
<div class="w-full max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-4 space-y-8">

    {{-- HEADER --}}
    <div>
        <h1 class="text-3xl sm:text-3xl font-bold text-[#260101] flex items-center gap-3">
            <span class="inline-flex items-center justify-center w-10 h-10 rounded-xl"
                  style="background:linear-gradient(135deg,#A65005,#592202)">
                <i class='bx bxs-user text-white text-xl'></i>
            </span>
            Admin Profile
        </h1>
        <p class="text-sm text-[#D99C79] mt-1 ml-13">Kelola informasi akun kamu</p>
    </div>

    {{-- HERO CARD --}}
    <section class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm overflow-hidden">
        {{-- Top bar --}}
        <div class="h-2 w-full" style="background:linear-gradient(90deg,#A65005,#592202,#800000)"></div>

        <div class="p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-6">
            {{-- Avatar + Name --}}
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 rounded-2xl flex-shrink-0 flex items-center justify-center
                            text-2xl font-bold text-[#A65005] shadow-sm"
                     style="background:linear-gradient(135deg,#F2D4C2,#D99C79)">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div>
                    <h2 class="text-lg font-bold text-[#260101]">{{ auth()->user()->name }}</h2>
                    <p class="text-sm text-[#D99C79] break-all">{{ auth()->user()->email }}</p>
                    <span class="inline-flex items-center gap-1 mt-1 text-xs font-semibold px-2.5 py-0.5 rounded-full bg-[#F2D4C2] text-[#A65005]">
                        <i class="bx bxs-shield text-xs"></i>
                        {{ ucfirst(auth()->user()->role ?? 'Admin') }}
                    </span>
                </div>
            </div>

            {{-- Status badge --}}
            <div class="flex sm:flex-col items-center sm:items-end gap-3 sm:gap-1">
                <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Active
                </span>
                <span class="text-xs text-[#D99C79]">
                    Verified: {{ auth()->user()->email_verified_at ? 'Yes' : 'No' }}
                </span>
            </div>
        </div>

        {{-- Metadata grid --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 border-t border-[#F2D4C2]">
            @foreach([
                ['label'=>'Member Since', 'value'=> auth()->user()->created_at->format('d M Y'), 'icon'=>'bx-calendar'],
                ['label'=>'Role',         'value'=> ucfirst(auth()->user()->role ?? 'Admin'),     'icon'=>'bxs-shield'],
                ['label'=>'Phone',        'value'=> auth()->user()->phone ?? '—',                 'icon'=>'bxs-phone'],
                ['label'=>'Status',       'value'=> 'Active',                                     'icon'=>'bxs-circle'],
            ] as $i => $meta)
            <div class="px-5 py-4 {{ $i < 3 ? 'border-r border-[#F2D4C2]' : '' }} {{ $i >= 2 ? 'border-t border-[#F2D4C2] sm:border-t-0' : '' }}">
                <p class="text-xs text-[#D99C79] flex items-center gap-1 mb-1">
                    <i class="bx {{ $meta['icon'] }} text-[#A65005]"></i> {{ $meta['label'] }}
                </p>
                <p class="text-sm font-semibold text-[#260101]">{{ $meta['value'] }}</p>
            </div>
            @endforeach
        </div>
    </section>

    {{-- SUCCESS / ERROR ALERTS --}}
    @if(session('success'))
    <div class="flex items-center gap-3 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl px-4 py-3 text-sm">
        <i class="bx bxs-check-circle text-lg"></i> {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="flex items-center gap-3 bg-red-50 border border-red-200 text-red-600 rounded-xl px-4 py-3 text-sm">
        <i class="bx bxs-error-circle text-lg"></i> {{ session('error') }}
    </div>
    @endif

    {{-- ACCOUNT SETTINGS --}}
    <section class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-[#F2D4C2] flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                 style="background:linear-gradient(135deg,#A65005,#592202)">
                <i class="bx bxs-cog text-white text-sm"></i>
            </div>
            <div>
                <h3 class="font-bold text-[#260101] text-base">Account Settings</h3>
                <p class="text-xs text-[#D99C79]">Ubah nama dan email akun</p>
            </div>
        </div>
        <div class="p-6">
            @include('admin.profile.partials.update-user-form')
        </div>
    </section>

    {{-- CHANGE PASSWORD --}}
    <section class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-[#F2D4C2] flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                 style="background:linear-gradient(135deg,#800000,#260101)">
                <i class="bx bxs-lock-alt text-white text-sm"></i>
            </div>
            <div>
                <h3 class="font-bold text-[#260101] text-base">Change Password</h3>
                <p class="text-xs text-[#D99C79]">Pastikan password kamu kuat dan aman</p>
            </div>
        </div>
        <div class="p-6">
            @include('admin.profile.partials.update-password-form')
        </div>
    </section>

    {{-- PROFILE DETAILS --}}
    <section class="bg-white rounded-2xl border border-[#F2D4C2] shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-[#F2D4C2] flex items-center gap-3">
            <div class="w-8 h-8 rounded-lg flex items-center justify-center"
                 style="background:linear-gradient(135deg,#D99C79,#A65005)">
                <i class="bx bxs-id-card text-white text-sm"></i>
            </div>
            <div>
                <h3 class="font-bold text-[#260101] text-base">Profile Details</h3>
                <p class="text-xs text-[#D99C79]">Nomor HP, alamat, dan bio</p>
            </div>
        </div>
        <div class="p-6">
            @include('admin.profile.partials.update-profile-form')
        </div>
    </section>

</div>
@endsection