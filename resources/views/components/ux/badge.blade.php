@props(['tag', 'color' => "gray", 'trigger'])

@if ((isset($trigger) AND $trigger == true) OR !isset($trigger))
    <span {{ $attributes->merge(['class' => "rounded-lg text-center items-center text-sm bg-".$color."-100 hover:bg-".$color."-200 text-".$color."-800 inline-block max-w-max p-2 items-center justify-center"]) }}>
        {{ $slot }}
    </span>
@endif
