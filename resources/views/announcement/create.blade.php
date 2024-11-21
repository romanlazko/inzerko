<x-app-layout class="w-full max-w-7xl m-auto pb-48">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('announcement.create') }}
        </h2>
    </x-slot>

    <x-slot name="sidebar">
        <x-nav.profile/>
    </x-slot>

    <livewire:components.announcement.create/>
</x-app-layout>