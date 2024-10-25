@props(['disabled' => false, 'dropdown' => null])

@if ($dropdown) 
    <div x-data="{ {{$dropdown}}: false }" class="relative dropdown {{$dropdown}}" {{ $disabled ? 'disabled' : '' }}>
        
        <div @click="{{$dropdown}} = ! {{$dropdown}}">
            <input {!! $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) !!} {{ $disabled ? 'disabled' : '' }}>
        </div>
        

        <div x-cloak :class="{{$dropdown}} ? 'block' : 'hidden'" @click="{{$dropdown}} = false" class="fixed inset-0 z-10 w-full h-full"></div>

        <div x-cloak :class="{{$dropdown}} ? 'block' : 'hidden'" class="absolute left-0 z-10 mt-2 bg-white rounded-md shadow-xl border max-h-[200px] overflow-auto dropdown-block {{$dropdown}}">
            {{ $slot ?? null }}
        </div>
    </div>

    <script type="module">
        $('.dropdown').on('click', '.dropdown-option', function() {
            $(this).closest('.dropdown').find('input').val($(this).val());
        });
    </script> 
@else 
    <input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm']) !!}">
@endif


