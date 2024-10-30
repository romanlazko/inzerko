@props(['active', 'tag' => 'a'])

@php
    $classes = ($active ?? false)
                ? 'text-indigo-500 '
                : 'text-white';
@endphp

<{{ $tag }} {{ $attributes->merge(['class' => $classes. ' flex hover:text-indigo-400 whitespace-nowrap items-center space-x-3 text-sm cursor-pointer']) }}>
    {{ $slot }}
</{{ $tag }}>