@props([
    'announcement' => null
])

<div {{ $attributes->merge(['class' => "w-full transition ease-in-out duration-150 group space-y-1 block w-full announcement-card cursor-pointer col-span-full rounded-2xl bg-white border hover:border-gray-400 p-4 h-full"]) }}>
    <a href="{{ route('announcement.show', $announcement) }}" class="space-y-2 flex flex-col h-full w-full">
        <div class="flex items-center space-x-2 z-30 w-full">
            <p class="text-xs text-gray-600 w-full">
                {{ $announcement?->geo?->name }}
            </p>
            <livewire:actions.like-dislike :announcement="$announcement"/>
        </div>

        <p class="text-lg font-semibold text-blue-600 line-clamp-1 group-hover:underline">
            {{ $announcement?->title }}
        </p>

        <p class="font-semibold text-lg w-full">
            {{ $announcement?->price }}
        </p>

        <div class="text-sm text-gray-900 space-y-2 html flex-1">
            {!! $announcement->description->limit(300) !!}
        </div>

        <p class="text-xs text-gray-600 w-full">
            {{ $announcement?->created_at?->diffForHumans() }}
        </p>
    </a>
</div>
