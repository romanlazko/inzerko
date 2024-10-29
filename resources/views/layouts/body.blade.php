<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="cleartype" content="on">
        <meta data-rh="true" property="og:type" content="website">
        <meta data-rh="true" property="og:site_name" content="{{ config('app.name') }}">
        <meta data-rh="true" property="og:locale" content="{{ app()->getLocale() }}">

        @if (isset($meta))
            {{ $meta }}
        @else
            {!! seo() !!}
        @endif

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet" />

        @stack('headerScripts')
        @livewireStyles
        @filamentStyles
        @vite(['resources/css/app.css'])
    </head>
    
    <body class="font-roboto bg-gray-50 min-h-dvh w-full flex flex-col" x-data="{ sidebarOpen: false}" :class="sidebarOpen ? 'overflow-hidden' : ''">
        <livewire:components.empty-component/>

        <div x-cloak :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false" class="fixed inset-0 z-30 transition-opacity  bg-black opacity-50 lg:hidden"></div>

        {{ $slot }}
        
        @if (session('ok') === true)
            <x-notifications.small class="bg-green-600 z-50" :title="session('description')"/>
        @elseif (session('ok') === false)
            <x-notifications.small class="bg-red-600 z-50" :title="session('description')"/>
        @endif
    </body>

    @livewireScripts
    @filamentScripts
    @vite(['resources/js/app.js'])
    @stack('scripts')
</html>