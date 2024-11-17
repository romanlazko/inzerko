@props(['disabled' => false, 'dropdown' => null])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 focus:invalid:border-red-500 focus:invalid:ring-red-500 invalid:border-red-500 invalid:ring-red-500 rounded-lg shadow-sm']) !!}">


