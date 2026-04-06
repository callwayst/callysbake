@extends('layouts.admin')

@section('content')
<div class="max-w-4xl mx-auto my-6 sm:my-12 p-6 sm:p-10 bg-white rounded-2xl shadow-lg">

    @php $isEdit = isset($user) && $user; @endphp
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
        <h2 class="text-2xl sm:text-3xl font-bold text-[#A65005]">{{ $isEdit ? 'Edit User' : 'Create User' }}</h2>
        <a href="{{ route('admin.users.index') }}" 
           class="px-4 py-2 bg-[#592202] text-[#F2D4C2] rounded-lg hover:bg-[#800000] transition flex items-center">
            &larr; Back
        </a>
    </div>

    @include('admin.users._form')
</div>
@endsection