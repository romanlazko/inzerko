@props(['value' => null, 'required' => false, 'disabled' => false])

<label {{ $attributes->merge(['class' => 'block font-bold text-sm ' . ($disabled ? ' text-gray-400' : ' text-gray-700')]) }}>
    @if ($value)
        @if ($required)
            <span class="text-red-600">*</span>
        @endif
        {{ $value }}
    @else 
        {{ $slot }}
    @endif
</label>
