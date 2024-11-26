@props(['id' => 'deleteButton', 'action' => ''])

<form method="POST" id="{{ $id }}" class="" action="{{$action}}">
    @csrf
    @method('delete')

    <button 
        {{ 
            $attributes->merge([
                'type' => 'submit', 
                'class' => 'inline-flex items-center px-4 py-2 space-x-1 bg-red-600 border border-transparent rounded-2xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-25'
            ]) 
        }}
        
        onclick="return confirm('{{ __('profile.are_you_sure')}}')"
    >
        <x-heroicon-s-trash class="size-4"/>
        <p>
            {{ $slot }}
        </p>
    </button>
</form>