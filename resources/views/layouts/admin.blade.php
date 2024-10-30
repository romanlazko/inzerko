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

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet" />
        @livewireStyles
        @filamentStyles
        @vite(['resources/css/app.css'])
    </head>
    
    <body class="font-roboto bg-white min-h-dvh w-full flex flex-col" x-data="{ sidebarOpen: false}" :class="sidebarOpen ? 'overflow-hidden' : ''">
        <livewire:components.empty-component/>

        <div x-cloak :class="sidebarOpen ? 'block' : 'hidden'" @click="sidebarOpen = false" class="fixed inset-0 z-30 transition-opacity  bg-black opacity-50 lg:hidden"></div>
    
        <main class="w-full h-full flex-1 md:block">
            <div {{ $attributes->merge(['class' => 'flex h-full m-auto w-full relative']) }}>
                <aside x-cloak :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="bg-gray-50 fixed lg:absolute inset-y-0 left-0 z-50 lg:z-10 w-full lg:w-[20rem] xl:w-[24rem] transition duration-300 transform lg:translate-x-0 lg:inset-0 min-h-dvh" aria-label="Sidebar">
                    <x-nav.sidebar class="h-full lg:h-min">
                        <x-nav.admin/>
                    </x-nav.sidebar>
                </aside>
    
                <div @class(['w-full h-full relative', 'lg:pl-[20rem] xl:pl-[24rem]' => true])>
                    <div class="flex items-center space-x-2 bg-gray-100 sticky top-0 z-40 w-full justify-between p-3">
                        <h2 class="w-full font-semibold text-xl">
                            {{ $title ?? "Admin" }}
                        </h2>
                        <button @click="sidebarOpen = true" type="button" class="lg:hidden">
                            <x-heroicon-c-bars-3-bottom-right class="size-5"/>
                        </button>
                    </div>

                    <div id="content" class="w-full space-y-4 flex-1 flex-col flex p-3" >
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </main>
    </body>

    @livewireScripts
    @filamentScripts
    @vite(['resources/js/app.js'])
    @stack('scripts')
</html>