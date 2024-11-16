@props(['cols' => 1, 'layout' => 'default', 'announcements' => [], 'paginator' => null])

<div {{ $attributes->merge(['class' => 'w-full space-y-4 lg:space-y-0 pb-4']) }}>
    @if (isset($header))
        <div class="w-full max-w-7xl m-auto py-4 px-3 border-b lg:border-none bg-white lg:bg-inherit">
            {{ $header }}
        </div>
    @endif
    <div class="w-full max-w-7xl m-auto px-3 space-y-6">
        @if($announcements->isEmpty())
            <div class="fi-ta-empty-state-content grid justify-items-center text-center p-12 w-full bg-white ring-1 ring-gray-950/5 rounded-xl">
                <div class="fi-ta-empty-state-icon-ctn mb-4 rounded-full bg-gray-100 p-3 dark:bg-gray-500/20">
                    <svg class="fi-ta-empty-state-icon h-6 w-6 text-gray-500 dark:text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true" data-slot="icon">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"></path>
                    </svg>
                </div>

                <h4 class="fi-ta-empty-state-heading text-base font-semibold leading-6 text-gray-950 dark:text-white">
                    {{ __('pagination.nothing') }}
                </h4>
            </div>
        @else  
            <div 
                @class([
                    'grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-x-2 gap-y-4 lg:gap-4',
                    'lg:grid-cols-1' => $cols == 1, 
                    'lg:grid-cols-2' => $cols == 2, 
                    'lg:grid-cols-3' => $cols == 3, 
                    'lg:grid-cols-4' => $cols == 4, 
                    'lg:grid-cols-5' => $cols == 5
                ])
            >
                @foreach ($announcements as $index => $announcement)
                    <x-announcement.card :layout="$announcement->category?->card_layout->name" :announcement="$announcement" />
                @endforeach
            </div>

            @if ($paginator)
                {{ $announcements?->onEachSide(1)->links() }}
            @endif
        @endif
    </div>
</div>

