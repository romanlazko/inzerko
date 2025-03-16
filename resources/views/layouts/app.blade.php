@props(['meta' => null])

<x-body-layout :meta="$meta">
    <header class="w-full bg-gray-900 block h-12 px-3">
        <x-nav.header/>
    </header>

    <main id="main-block" class="w-full h-full flex-1 md:block pb-6">
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
                <aside x-cloak :class="sidebarOpen ? 'translate-x-0 ease-out' : '-translate-x-full ease-in'" class="h-full bg-gray-50 fixed lg:relative inset-y-0 left-0 z-50 lg:z-10 w-full sm:w-[26rem] transition duration-300 transform lg:translate-x-0 lg:inset-0" aria-label="Sidebar">
                    <x-nav.sidebar class="h-full lg:h-min">
                        {{ $sidebar }}
                    </x-nav.sidebar>
                </aside>
            @endif

            <div @class(['w-full h-full relative space-y-6', 'lg:w-[54rem]' => isset($sidebar)])>
                <div id="content" class="w-full space-y-4 flex-1 flex-col flex" >
                    {{ $slot }}
                </div>
            </div>
        </div>


    </main>

    @livewire('actions.open-chat')

    <footer class="w-full text-gray-600 bg-gray-100 ">
        <div class="max-w-7xl m-auto p-3 lg:flex lg:items-center justify-center lg:space-x-5 text-xs text-center space-y-4 lg:space-y-0">
            <p>
                © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
            @foreach ($footer_pages as $page)
                <a href="{{ route('page', $page->slug) }}" class="hover:text-indigo-700 block">{{ $page->name }}</a>
            @endforeach
        </div>
    </footer>

    <div class="w-full lg:hidden block sticky bottom-0 h-12 z-20 border-t ">
        <x-nav.footer/>
    </div>
</x-body-layout>