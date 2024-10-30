@props(['count'])

@if ($count > 0)
    <span {{ $attributes->merge(['class' => 'absolute text-[8px] text-white w-3 h-3 rounded-full bg-red-500 top-3 text-center content-center items-center']) }}>
        {{ $count }}
    </span>
@endif