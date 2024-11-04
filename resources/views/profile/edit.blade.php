<x-app-layout :title="__('components.navigation.profile')" class="w-full max-w-7xl m-auto">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('components.navigation.profile') }}
        </h2>
    </x-slot>

    <x-slot name="sidebar">
        <x-nav.profile/>
    </x-slot>

    <div class="px-3 py-5 space-y-6">
        @include('profile.partials.verify-email')
        @include('profile.partials.fill-profile')

        <div class="tab-wrapper" x-data="{ activeTab:  0 }">
            <div class="flex space-x-1 px-3">
                <x-buttons.button @click="activeTab = 0" class="rounded-b-none" x-bind:class="{ 'bg-indigo-700 hover:bg-indigo-500 text-white': activeTab === 0 }">
                    {{ __("profile.profile") }}
                </x-buttons.button>
                <x-buttons.button @click="activeTab = 1" class="rounded-b-none" x-bind:class="{ 'bg-indigo-700 hover:bg-indigo-500 text-white': activeTab === 1 }">
                    {{ __("profile.password") }}
                </x-buttons.button>
            </div>
        
            <div class="tab-panel space-y-4" :class="{ 'active': activeTab === 0 }" x-show.transition.in.opacity.duration.600="activeTab === 0">
                <x-ux.white-block>
                    @include('profile.partials.update-profile-information-form')
                </x-ux.white-block>
                <x-ux.white-block>
                    @include('profile.partials.update-telegram-information-form')
                </x-ux.white-block>
                <x-ux.white-block>
                    @include('profile.partials.logout-form')
                </x-ux.white-block>
            </div>
    
            <div class="tab-panel space-y-6" :class="{ 'active': activeTab === 1 }" x-show.transition.in.opacity.duration.600="activeTab === 1">
                <x-ux.white-block>
                    @include('profile.partials.update-password-form')
                </x-ux.white-block>
            </div>
        </div>
    </div>
    
</x-app-layout>