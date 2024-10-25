@props(['cols' => 1, 'layout' => 'sm', 'announcements' => [], 'paginator' => null])

<div {{ $attributes->merge(['class' => 'w-full space-y-4 lg:space-y-0 pb-4']) }}>
    @if (isset($header))
        <div class="w-full max-w-7xl m-auto py-4 px-3 border-b lg:border-none bg-white md:bg-inherit">
            {{ $header }}
        </div>
    @endif

    <div 
        @class([
            'w-full max-w-7xl m-auto px-3',
            'lg:gap-y-12 grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-x-2 lg:gap-x-4 gap-y-6',
            'lg:grid-cols-1' => $cols == 1, 
            'lg:grid-cols-2' => $cols == 2, 
            'lg:grid-cols-3' => $cols == 3, 
            'lg:grid-cols-4' => $cols == 4, 
            'lg:grid-cols-5' => $cols == 5
        ])
    >
        @foreach ($announcements as $index => $announcement)
            <x-announcement.card :announcement="$announcement" :layout="$layout" />
        @endforeach
    </div>

    @if ($paginator)
        <div class="p-3">
            {{ $announcements?->onEachSide(1)->links() }}
        </div>
    @endif
</div>

