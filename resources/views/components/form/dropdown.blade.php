@props(['dropdown' => 'dropdown'])


<div x-data="{ {{$dropdown}}: false }" class="relative dropdown">

    
    <div x-cloak x-show="{{$dropdown}}" @click="{{$dropdown}} = false" class="fixed inset-0 z-10 w-full h-full"></div>

    <div x-cloak x-show="{{$dropdown}}" class="absolute left-0 z-10 mt-2 bg-white rounded-md shadow-xl border max-h-[200px] overflow-auto" id="{{$dropdown}}">
        {{ $slot }}
    </div>
</div>

