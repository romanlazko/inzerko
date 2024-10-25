@props(['active', 'text' => null])

@php
    $activeClasses = ($active ?? false)
        ? ' text-indigo-700'
        : ' focus:outline-none focus:text-gray-800 focus:border-gray-300 hover:text-indigo-200';
@endphp

<a {{ 
    $attributes->merge([
        'class' => 'text-center transition duration-150 ease-in-out cursor-pointer' . $activeClasses
    ])
}}>
    <div class="grid m-auto w-min space-y-1 whitespace-nowrap">
        {{ $slot }}

        <small class="text-[9px]">{{ $text }}</small>
    </div>
</a>
