@props(['confirm' => null])

<a 
    {{ 
        $attributes->merge([
            'class' => 'cursor-pointer inline-flex items-center px-4 py-2 space-x-1 bg-red-600 border border-transparent rounded-2xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-25'
        ]) 
    }} 
    @if($confirm) onclick="return confirm('{{ $confirm }}')" @endif
>
    <x-heroicon-s-trash class="size-4"/>
    <p>
        {{ $slot }}
    </p>
</a>