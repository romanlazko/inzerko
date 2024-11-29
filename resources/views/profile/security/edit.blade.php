<x-app-layout class="w-full max-w-7xl m-auto">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('components.navigation.profile') }}
        </h2>
    </x-slot>

    <x-slot name="sidebar">
        <x-nav.profile/>
    </x-slot>

    <div class="py-5 px-3 space-y-4">
        <x-ux.white-block>
            @include('profile.security.partials.update-password-form')
        </x-ux.white-block>
        
        <x-ux.white-block>
            @include('profile.security.partials.delete-user-form')
        </x-ux.white-block>
    </div>
</x-app-layout>