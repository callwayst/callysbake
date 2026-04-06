@extends('layouts.admin')

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-10 py-4 space-y-8">

    {{-- ===== HEADER ===== --}}
    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <div>
            <h1 class="text-3xl sm:text-4xl font-bold text-[#260101] tracking-tight flex items-center gap-3">
                <span class="inline-flex items-center justify-center w-11 h-11 rounded-2xl bg-gradient-to-br from-[#A65005] to-[#592202] shadow-lg mt-3">
                    <i class='bx bxs-group text-white text-2xl'></i>
                </span>
                Manage Users
            </h1>
            <p class="text-gray-400 text-sm mt-1 ml-14">Kelola akun dan informasi pengguna</p>
        </div>
        <a href="{{ route('admin.users.create') }}"
           class="inline-flex items-center gap-2 px-5 py-2.5 rounded-xl text-white font-semibold shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200"
           style="background: linear-gradient(135deg, #A65005 0%, #592202 100%)">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
            </svg>
            Add User
        </a>
    </div>

    {{-- ===== STATS ROW ===== --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
        @php
            $totalUsers  = $allUsers->count();
            $activeUsers = $allUsers->where('status', 1)->count();
            $adminCount  = $allUsers->where('role', 'admin')->count();
            $userCount   = $allUsers->where('role', 'user')->count();
        @endphp
        @foreach([
            ['label'=>'Total Users',  'value'=>$totalUsers,  'icon'=>'bxs-group',      'color'=>'from-[#A65005] to-[#592202]'],
            ['label'=>'Active',       'value'=>$activeUsers, 'icon'=>'bxs-user-check', 'color'=>'from-emerald-500 to-emerald-700'],
            ['label'=>'Admins',       'value'=>$adminCount,  'icon'=>'bxs-shield',     'color'=>'from-[#800000] to-[#592202]'],
            ['label'=>'Regular Users','value'=>$userCount,   'icon'=>'bxs-user',       'color'=>'from-amber-500 to-orange-600'],
        ] as $stat)
        <div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br {{ $stat['color'] }} flex items-center justify-center flex-shrink-0">
                <i class="bx {{ $stat['icon'] }} text-white text-lg"></i>
            </div>
            <div>
                <p class="text-2xl font-bold text-[#260101]">{{ $stat['value'] }}</p>
                <p class="text-xs text-gray-400">{{ $stat['label'] }}</p>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ===== SEARCH & FILTER ===== --}}
    <form method="GET" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 flex flex-col md:flex-row gap-3 items-center">
        <div class="relative flex-1 w-full">
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama, email, atau nomor HP..."
                   class="w-full border border-gray-200 rounded-xl pl-10 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005] transition"
                   onkeydown="if(event.key==='Enter'){this.form.submit()}">
            <svg class="absolute left-3 top-3 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>
            </svg>
        </div>
        <select name="role" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm w-full md:w-44 focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]" onchange="this.form.submit()">
            <option value="">Semua Role</option>
            <option value="admin"    {{ request('role') === 'admin'    ? 'selected' : '' }}>Admin</option>
            <option value="kasir"    {{ request('role') === 'kasir'    ? 'selected' : '' }}>Kasir</option>
            <option value="customer" {{ request('role') === 'customer' ? 'selected' : '' }}>Customer</option>
        </select>
        <select name="status" class="border border-gray-200 rounded-xl px-4 py-2.5 text-sm w-full md:w-44 focus:outline-none focus:ring-2 focus:ring-[#A65005]/30 focus:border-[#A65005]" onchange="this.form.submit()">
            <option value="">Semua Status</option>
            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Active</option>
            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Inactive</option>
        </select>
        @if(request()->hasAny(['search','role','status']))
        <a href="{{ route('admin.users.index') }}" class="text-sm text-gray-400 hover:text-[#A65005] transition whitespace-nowrap px-2">
            × Reset
        </a>
        @endif
    </form>

    {{-- ===== DESKTOP: CARD GRID ===== --}}
    <div class="hidden md:grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-4 gap-5">
        @forelse($allUsers as $u)
        <div class="group bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-xl hover:-translate-y-1 transition-all duration-300 overflow-hidden flex flex-col">

            {{-- Top accent bar --}}
            <div class="h-1.5 w-full {{ $u->status ? 'bg-gradient-to-r from-[#A65005] to-[#592202]' : 'bg-gray-200' }}"></div>

            {{-- ← BARU: klik area atas buka halaman detail --}}
            <a href="{{ route('admin.users.show', $u->id) }}" class="p-5 flex flex-col gap-3 flex-1 hover:bg-[#fdf8f5] transition-colors">

                {{-- Avatar & Name --}}
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-full flex-shrink-0 overflow-hidden bg-gradient-to-br from-[#F2D4C2] to-[#e8b898] flex items-center justify-center shadow-sm">
                        @if($u->avatar)
                            <img src="{{ asset('storage/'.$u->avatar) }}" alt="{{ $u->name }}" class="w-full h-full object-cover">
                        @else
                            <span class="text-[#A65005] font-bold text-lg">{{ strtoupper(substr($u->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <h3 class="font-bold text-[#260101] truncate leading-tight group-hover:text-[#A65005] transition-colors">{{ $u->name }}</h3>
                        <p class="text-xs text-gray-400 truncate">{{ $u->email }}</p>
                    </div>
                </div>

                {{-- Badges --}}
                <div class="flex items-center gap-2 flex-wrap">
                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full
                        {{ $u->role === 'admin' ? 'bg-[#800000]/10 text-[#800000]' : ($u->role === 'kasir' ? 'bg-blue-50 text-blue-700' : 'bg-[#A65005]/10 text-[#A65005]') }}">
                        <i class="bx {{ $u->role === 'admin' ? 'bxs-shield' : ($u->role === 'kasir' ? 'bxs-calculator' : 'bxs-user') }} text-sm"></i>
                        {{ ucfirst($u->role) }}
                    </span>
                    <span class="inline-flex items-center gap-1 text-xs font-semibold px-2.5 py-1 rounded-full
                        {{ $u->status ? 'bg-emerald-50 text-emerald-700' : 'bg-red-50 text-red-600' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $u->status ? 'bg-emerald-500' : 'bg-red-400' }}"></span>
                        {{ $u->status ? 'Active' : 'Inactive' }}
                    </span>
                </div>

                {{-- Contact Info --}}
                <div class="space-y-1.5 text-sm">
                    @if($u->phone)
                    <div class="flex items-center gap-2 text-gray-600">
                        <i class="bx bxs-phone text-[#A65005] text-base flex-shrink-0"></i>
                        <span class="truncate">{{ $u->phone }}</span>
                    </div>
                    @else
                    <div class="flex items-center gap-2 text-gray-300">
                        <i class="bx bxs-phone text-base flex-shrink-0"></i>
                        <span class="italic text-xs">No phone</span>
                    </div>
                    @endif

                    @if($u->address)
                    <div class="flex items-start gap-2 text-gray-600">
                        <i class="bx bxs-map text-[#A65005] text-base flex-shrink-0 mt-0.5"></i>
                        <span class="line-clamp-2 text-xs leading-relaxed">{{ $u->address }}</span>
                    </div>
                    @else
                    <div class="flex items-center gap-2 text-gray-300">
                        <i class="bx bxs-map text-base flex-shrink-0"></i>
                        <span class="italic text-xs">No address</span>
                    </div>
                    @endif
                </div>

            </a>{{-- end link --}}

            {{-- Actions --}}
            <div class="px-5 pb-5 flex flex-col gap-2">
                {{-- Toggle Status --}}
                <form action="{{ route('admin.users.toggle', $u->id) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit"
                        class="w-full py-2 rounded-xl text-xs font-bold tracking-wide uppercase transition-all duration-200
                        {{ $u->status
                            ? 'bg-emerald-50 text-emerald-700 hover:bg-emerald-100 border border-emerald-200'
                            : 'bg-red-50 text-red-600 hover:bg-red-100 border border-red-200' }}">
                        {{ $u->status ? '✓ Active — Click to Deactivate' : '✗ Inactive — Click to Activate' }}
                    </button>
                </form>
                {{-- Detail, Edit & Delete --}}
                <div class="flex gap-2">
                    <a href="{{ route('admin.users.show', $u->id) }}"
                       class="flex-1 py-2 text-center text-xs font-semibold rounded-xl bg-[#F2D4C2] text-[#592202] hover:bg-[#D99C79] transition-colors">
                        <i class="bx bxs-show mr-1"></i>Detail
                    </a>
                    <a href="{{ route('admin.users.edit', $u->id) }}"
                       class="flex-1 py-2 text-center text-xs font-semibold rounded-xl bg-[#592202] text-[#F2D4C2] hover:bg-[#A65005] transition-colors">
                        <i class="bx bxs-edit mr-1"></i>Edit
                    </a>
                    <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST"
                          onsubmit="return confirm('Yakin hapus user {{ $u->name }}?')">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="px-3 py-2 text-xs font-semibold rounded-xl bg-red-50 text-red-600 hover:bg-red-600 hover:text-white border border-red-200 transition-colors">
                            <i class="bx bxs-trash"></i>
                        </button>
                    </form>
                </div>
            </div>

        </div>
        @empty
        <div class="col-span-full text-center py-20 text-gray-300">
            <i class="bx bxs-user-x text-6xl block mb-3"></i>
            <p class="text-lg font-medium text-gray-400">Tidak ada user ditemukan</p>
            <p class="text-sm">Coba ubah filter pencarian kamu</p>
        </div>
        @endforelse
    </div>

    {{-- ===== MOBILE: LIST ===== --}}
    <div class="block md:hidden space-y-4">
        @foreach($users as $u)
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="h-1 {{ $u->status ? 'bg-gradient-to-r from-[#A65005] to-[#592202]' : 'bg-gray-200' }}"></div>
            <div class="p-4">
                {{-- ← BARU: nama + avatar bisa diklik --}}
                <a href="{{ route('admin.users.show', $u->id) }}" class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#F2D4C2] to-[#e8b898] flex items-center justify-center flex-shrink-0">
                        @if($u->avatar)
                            <img src="{{ asset('storage/'.$u->avatar) }}" alt="{{ $u->name }}" class="w-full h-full object-cover rounded-full">
                        @else
                            <span class="text-[#A65005] font-bold">{{ strtoupper(substr($u->name, 0, 1)) }}</span>
                        @endif
                    </div>
                    <div class="min-w-0 flex-1">
                        <h3 class="font-bold text-[#260101] truncate hover:text-[#A65005] transition-colors">{{ $u->name }}</h3>
                        <p class="text-xs text-gray-400 truncate">{{ $u->email }}</p>
                    </div>
                    <span class="text-xs font-semibold px-2 py-0.5 rounded-full flex-shrink-0
                        {{ $u->role === 'admin' ? 'bg-[#800000]/10 text-[#800000]' : ($u->role === 'kasir' ? 'bg-blue-50 text-blue-700' : 'bg-[#A65005]/10 text-[#A65005]') }}">
                        {{ ucfirst($u->role) }}
                    </span>
                </a>

                @if($u->phone)
                <div class="flex items-center gap-2 text-xs text-gray-500 mb-1">
                    <i class="bx bxs-phone text-[#A65005]"></i> {{ $u->phone }}
                </div>
                @endif
                @if($u->address)
                <div class="flex items-start gap-2 text-xs text-gray-500 mb-3">
                    <i class="bx bxs-map text-[#A65005] mt-0.5"></i>
                    <span class="line-clamp-1">{{ $u->address }}</span>
                </div>
                @endif

                <div class="flex gap-2 mt-2">
                    <form action="{{ route('admin.users.toggle', $u->id) }}" method="POST" class="flex-1">
                        @csrf @method('PATCH')
                        <button class="w-full py-1.5 rounded-lg text-xs font-semibold
                            {{ $u->status ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-red-50 text-red-600 border border-red-200' }}">
                            {{ $u->status ? '✓ Active' : '✗ Inactive' }}
                        </button>
                    </form>
                    <a href="{{ route('admin.users.show', $u->id) }}"
                       class="flex-1 py-1.5 text-center text-xs font-semibold rounded-lg bg-[#F2D4C2] text-[#592202]">
                        Detail
                    </a>
                    <a href="{{ route('admin.users.edit', $u->id) }}"
                       class="flex-1 py-1.5 text-center text-xs font-semibold rounded-lg bg-[#592202] text-[#F2D4C2]">
                        Edit
                    </a>
                    <form action="{{ route('admin.users.destroy', $u->id) }}" method="POST"
                          onsubmit="return confirm('Hapus user ini?')">
                        @csrf @method('DELETE')
                        <button class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-50 text-red-600 border border-red-200">
                            <i class="bx bxs-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach

        <div class="mt-4">{{ $users->links() }}</div>
    </div>

</div>
@endsection