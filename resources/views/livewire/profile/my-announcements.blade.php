
<div>
    <x-app-layout class="w-full max-w-7xl m-auto">
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('My announcements') }}
            </h2>
        </x-slot>
        <x-slot name="sidebar">
            <x-nav.profile/>
        </x-slot>
        <div class="py-5 px-3">
            {{ $this->table }}
        </div>
    </x-app-layout>
</div>