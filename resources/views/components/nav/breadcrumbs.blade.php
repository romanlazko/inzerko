@props(['category' => null])

<div class="w-full overflow-auto">
    <nav class="text-sm">
        <ol class="flex flex-wrap items-center gap-1">
            <x-nav.breadcrumb-link :icon="false">
                <a href="{{ route('home') }}" aira-label="home" class="cursor-pointer text-blue-600" aria-label="Go to home page">
                    <x-heroicon-c-home class="size-5"/>	
                </a>
            </x-nav.breadcrumb-link>
            @forelse ($category?->parentsAndSelf?->reverse() ?? [] as $parent)
                <x-nav.breadcrumb-link>
                    <a href="{{ route('announcement.search', ['category' => $parent->slug]) }}" @class(['text-blue-600 cursor-pointer', 'text-gray-800 font-medium' => $loop->last, 'hover:underline ' => !$loop->last])>
                        {{ $parent->name }}
                    </a>
                </x-nav.breadcrumb-link>
            @empty
                <x-nav.breadcrumb-link>
                    <a href="{{ route('announcement.index') }}" @class(['text-gray-800 cursor-pointer hover:underline font-medium'])>
                        {{ __('components.navigation.all') }}
                    </a>
                </x-nav.breadcrumb-link>
            @endforelse
        </ol>
    </nav>
</div>