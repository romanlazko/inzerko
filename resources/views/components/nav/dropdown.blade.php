@props(['active' => false])

<div class="space-y-2 h-min" x-data="{ open: {{ $active ? 'true' : 'false' }} }">
    <x-nav.responsive-link @click="open = !open" class="cursor-pointer">
        {{ $trigger }}
    </x-nav.responsive-link>

    <div class="transition duration-200 ease-in-out space-y-2 pl-6" x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
    >
        {{ $slot }}
    </div>
</div>
