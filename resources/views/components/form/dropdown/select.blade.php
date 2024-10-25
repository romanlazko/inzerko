@props(['disabled' => false, 'default_value' => null])

<div x-data="{ dropdown: false, value: '{{ $default_value }}' }" class="relative" {{ $disabled ? 'disabled' : '' }}>
    
    <div @click="dropdown = ! dropdown">
        <input {!! $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) !!} {{ $disabled ? 'disabled' : '' }}  x-model="value">
    </div>

    <div x-cloak :class="dropdown ? 'block' : 'hidden'" @click="dropdown = false" class="fixed inset-0 z-10 w-full h-full"></div>

    <div x-cloak :class="dropdown ? 'block' : 'hidden'" class="absolute left-0 z-10 mt-2 bg-white rounded-md shadow-xl border max-h-[200px] overflow-auto dropdown-block">
        {{ $slot ?? null }}
    </div>
</div>