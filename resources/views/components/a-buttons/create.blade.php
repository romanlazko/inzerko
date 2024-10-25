<a 
    {{ 
        $attributes->merge([
            'class' => 'space-x-2 cursor-pointer inline-flex items-center px-1 py-1 pr-3 border border-transparent rounded-2xl text-md focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-25 whitespace-nowrap bg-indigo-600 hover:bg-indigo-700 text-white justify-center'
        ]) 
    }}
>
    <x-heroicon-c-plus-circle class="size-7"/>
    <p class="hidden md:flex">
        {{ $slot }}
    </p>
</a>