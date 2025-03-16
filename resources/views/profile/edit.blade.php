<x-app-layout :title="__('components.navigation.profile')" class="w-full max-w-7xl m-auto">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('components.navigation.profile') }}
        </h2>
    </x-slot>

    <x-slot name="sidebar">
        <x-nav.profile/>
    </x-slot>

    <div class="px-3 py-5 space-y-6 profile">
        @include('profile.partials.verify-email')

        <livewire:components.profile.update-profile-information-form />

        @include('profile.partials.update-telegram-information-form')

        @include('profile.partials.fill-profile')

        <livewire:components.profile.contact-form />
        <livewire:components.profile.update-languages-information-form />

        @include('profile.partials.logout-form')
    </div>
    
</x-app-layout>