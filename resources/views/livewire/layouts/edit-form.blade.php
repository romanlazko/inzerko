<div>
    <form wire:submit="update">
        {{ $this->form }}
        
        <x-filament::button wire:click="update">
            Update
        </x-filament::button>
    </form>
</div>