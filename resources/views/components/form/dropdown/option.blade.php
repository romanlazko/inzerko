@props(['value' => false])

<button {!! $attributes->merge(['class' => 'p-2 w-full hover:bg-gray-200 text-left dropdown-option']) !!} type="button" @click="dropdown = false" x-on:click="value = '{{ $value }}'" :class="value == '{{ $value }}' ? 'bg-gray-200' : ''">
    {{ $slot }}
</button>