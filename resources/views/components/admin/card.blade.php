@props([
    'title' => '',
    'value' => '',
    'icon' => null
])

<div {{ $attributes->merge([
    'class' => 'bg-white rounded-2xl shadow p-6 flex justify-between items-center'
]) }}>

    <div>
        <p class="text-sm text-gray-500 mb-1">{{ $title }}</p>
        <h2 class="text-2xl font-bold">{{ $value }}</h2>
    </div>

    @if($icon)
        <div class="text-3xl opacity-40">
            {!! $icon !!}
        </div>
    @endif

</div>