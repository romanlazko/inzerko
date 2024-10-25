@props(['disabled' => false, 'value'])

@php
    $classes = explode(" ", $attributes->merge([
            'class' => 'row-start-1 col-start-1 row-end-2 col-end-2 hover:border-indigo-600 p-2 border border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm p-2'
    ])['class']);

    $divClasses = [];

    foreach ($classes as $class) {
        $divClasses[] = 'after:'.$class;
    }
@endphp

<div class="grid after:whitespace-pre-wrap after:invisible after:content-[attr(data-replicated-value)'_'] {{ implode(' ', $divClasses) }}">
    <textarea
        onInput="this.parentNode.dataset.replicatedValue = this.value"
        class="resize-none overflow-hidden {{ implode(' ', $classes) }}""
        {!! $attributes !!}
    >{{ $value ?? null }}</textarea>
</div>


{{-- <textarea {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'p-3 mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) !!}>{{ $value ?? null }}</textarea> --}}

{{-- <span {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border p-3 w-full border-gray-300 focus:border-indigo-500 rounded-md shadow-sm']) !!} role="textbox" contenteditable>{{ $value ?? null }}</span> --}}