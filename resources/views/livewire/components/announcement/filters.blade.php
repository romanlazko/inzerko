<form wire:submit="search" class="w-full h-full rounded-2xl">
    <div class="w-full rounded-2xl">
        {{ $this->form }}
    </div>
    <div class="bottom-0 sticky w-full rounded-2xl pt-4 my-4 lg:m-0 lg:py-4">
        {{-- <x-buttons.primary type="submit" @click="sidebarOpen = false" class="w-full justify-center rounded-2xl">
            
        </x-buttons.primary> --}}

        <x-filament::button
            icon="heroicon-o-magnifying-glass"
            type="submit"
            @click="sidebarOpen = false"
            class="w-full hover:bg-gray-600"
            color="dark"
        >
            {{ __('livewire.apply_filters') }}
        </x-filament::button>
    </div>
</form>
