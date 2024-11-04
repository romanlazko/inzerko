@props(['meta' => null])

<x-body-layout :meta="$meta">
    <header class="w-full bg-gray-900 block h-12 px-3">
        <x-nav.header/>
    </header>

    <main id="main-block" class="w-full h-full flex-1 md:block">
        @if (isset($search))
            <div class="bg-white sticky top-0 z-30  w-full ">
                {{ $search }}
                <hr>
            </div>
        @endif

        @if (isset($header))
            <div class="flex w-full min-h-10 items-center space-x-2 bg-gray-100">
                <div class="w-full m-auto py-6 px-3 max-w-7xl">
                    {{ $header }}
                </div>
            </div>
        @endif

        <div {{ $attributes->merge(['class' => 'flex h-full m-auto w-full relative']) }}>
            @if (isset($sidebar))
                <aside x-cloak :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="bg-gray-50 fixed lg:absolute inset-y-0 left-0 z-50 lg:z-10 w-full lg:w-[20rem] xl:w-[24rem] transition duration-300 transform lg:translate-x-0 lg:inset-0" aria-label="Sidebar">
                    <x-nav.sidebar class="h-full lg:h-min">
                        {{ $sidebar }}
                    </x-nav.sidebar>
                </aside>
            @endif

            <div @class(['w-full h-full relative space-y-6', 'lg:pl-[20rem] xl:pl-[24rem]' => isset($sidebar)])>
                <div id="content" class="w-full space-y-4 flex-1 flex-col flex" >
                    {{ $slot }}
                </div>
            </div>
        </div>
    </main>

    @auth
        @livewire('actions.open-chat')
    @endauth

    <footer class="w-full bg-gray-900">
        <p class="w-full max-w-7xl m-auto p-3 text-white text-center">
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </p>
    </footer>

    <div class="w-full lg:hidden block sticky bottom-0 h-12 z-20 border-t ">
        <x-nav.footer/>
    </div>
</x-body-layout>