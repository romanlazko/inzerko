@props(['value' => null, 'required' => false])

<label {{ $attributes->merge(['class' => 'block font-bold text-sm text-gray-700']) }}>
    @if ($value)
        {{ $value }}
        @if ($required)
            <span class="text-red-600">*</span>
        @endif
    @else 
        {{ $slot }}
    @endif
</label>
