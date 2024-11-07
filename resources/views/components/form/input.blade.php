@props(['disabled' => false, 'dropdown' => null])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-lg shadow-sm']) !!}">


