<x-filament::section
    class="empty-section-with-header"
>
    <x-slot name="heading">
        <div class="flex space-x-4 items-center">
            <div class="relative">
                <img src="{{ auth()->user()?->getFirstMediaUrl('avatar', 'thumb') }}" alt="" class="rounded-full w-14 h-14 min-h-14 min-w-14 object-cover aspect-square border">
            </div>
            <div class="w-full space-y-2">
                <span class="block font-medium leading-none text-start">
                    {{ auth()->user()?->name }}
                </span>
                <span class="block font-extralight leading-none text-start text-sm">
                    {{ auth()->user()?->email }}
                </span>
            </div>
        </div>
    </x-slot>

    <x-slot name="headerActions">
        {{ $this->editProfileAction }}
    </x-slot>

    <x-filament-actions::modals />
</x-filament::section>

