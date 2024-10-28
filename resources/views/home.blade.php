<x-app-layout :title="__('home.title')">
    <x-slot name="header">
        <div class="w-full space-y-24 py-12">
            <div class="max-w-2xl m-auto space-y-12">
                <h1 class="text-4xl font-bold bg-gradient-to-r from-indigo-500 to-purple-800 text-transparent bg-clip-text">
                    {{ __("home.buy_sell_discover") }} <br> {{ __("home.everything_you_need_in_one_place") }}
                </h1>

                <x-search :location="$request->location"/>
            </div>
            
            <div class="space-y-6">
                <h2 class="text-xl text-gray-800 text-left font-semibold">
                    {{ __("home.view_our_range") }} <span class="text-indigo-700 font-bold">{{ __("home.categories")}}</span>:
                </h2>
                
                <x-categories :categories="$categories"/>
            </div>
        </div>
    </x-slot>

    <x-announcement.list :announcements="$announcements" :cols="5">
        <x-slot name="header">
            <h2 class="text-xl lg:text-3xl font-bold">
                {{ __("home.all_announcements") }} <span class="text-gray-500">{{ $announcements->total() }}</span>
            </h2>
        </x-slot>
    </x-announcement.list>
</x-app-layout>