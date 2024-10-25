<form wire:submit="search" class="w-full h-full rounded-2xl">
    {{-- @dump($filters) --}}
    <div class="w-full rounded-2xl">
        {{ $this->form }}
    </div>
    <div class="bottom-0 sticky w-full rounded-2xl pt-4 my-4 lg:m-0 lg:py-4">
        <x-buttons.primary type="submit" @click="sidebarOpen = false" class="w-full justify-center rounded-2xl">
            {{ __('livewire.apply_filters') }}
        </x-buttons.primary>
    </div>
</form>
