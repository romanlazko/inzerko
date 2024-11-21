<x-app-layout :title="__('components.navigation.profile')" class="w-full max-w-7xl m-auto">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('components.navigation.profile') }}
        </h2>
    </x-slot>

    <x-slot name="sidebar">
        <x-nav.profile/>
    </x-slot>

    <div class="px-3 py-5 space-y-4">
        @include('profile.partials.verify-email')

        <x-ux.white-block>
            @include('profile.partials.update-profile-information-form')
        </x-ux.white-block>

        @include('profile.partials.fill-profile')
        <x-ux.white-block>
            @include('profile.partials.update-communication-information-form')
        </x-ux.white-block>
        
        <x-ux.white-block>
            @include('profile.partials.logout-form')
        </x-ux.white-block>
    </div>
    
</x-app-layout>