<x-filament::section>
    <x-slot name="heading">
        {{ __('profile.delete_user_form.title') }}
    </x-slot>
    <x-slot name="description">
        {{ __('profile.delete_user_form.description') }}
    </x-slot>
    
    {{ $this->deleteProfileAction }}

    <x-filament-actions::modals />
</x-filament::section>
