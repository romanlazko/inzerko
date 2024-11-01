<div>
    <form wire:submit="save" class="w-full max-w-2xl mx-auto p-1">
        {{ $this->form }}
    </form>

    <x-filament-actions::modals />
</div>
