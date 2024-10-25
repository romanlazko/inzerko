@props(['active'])

@php
    $classes = ($active ?? false)
                ? 'text-indigo-500 '
                : 'text-gray-700';
@endphp

<a {{ $attributes->merge(['class' => $classes. ' flex hover:text-indigo-400 whitespace-nowrap items-center space-x-3 cursor-pointer font-medium text-sm lg:text-base']) }}>
    {{ $slot }}
</a>
