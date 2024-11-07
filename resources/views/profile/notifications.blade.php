<x-app-layout :title="__('components.navigation.notifications')" class="w-full max-w-7xl m-auto">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('components.navigation.notifications') }}
        </h2>
    </x-slot>

    <x-slot name="sidebar">
        <x-nav.profile/>
    </x-slot>

    <div class="py-5 px-3 space-y-4">
        <form action="{{ route('profile.update-notifications') }}" method="post">
            @csrf
            @method('patch')

            <div class="w-full space-y-6">
                <x-ux.white-block>
                    @include('profile.partials.new-message-notifications-form')
                </x-ux.white-block>
                
                <div class="flex items-center gap-4">
                    <x-buttons.primary>{{ __('profile.save') }}</x-buttons.primary>
        
                    @if (session('status') === 'password-updated')
                        <p
                            x-data="{ show: true }"
                            x-show="show"
                            x-transition
                            x-init="setTimeout(() => show = false, 2000)"
                            class="text-sm text-gray-600"
                        >{{ __('profile.saved.') }}</p>
                    @endif
                </div>
            </div>
        </form>
    </div>
</x-app-layout>