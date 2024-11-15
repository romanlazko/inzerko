@props([
    'layout' => 'sm', 
    'announcement' => null
])

<div {{ $attributes->merge(['class' => "w-full transition ease-in-out duration-150 group space-y-1 block w-full announcement-card cursor-pointer"]) }}>
    <a href="{{ route('announcement.show', $announcement) }}" class="space-y-1">
        @if ($announcement?->media?->isNotEmpty())
            <div class="w-full rounded-2xl overflow-hidden h-min border group-hover:border-gray-300">
                <x-ux.slider :medias="$announcement?->getMedia('announcements')" conversion="medium" :withDots="true"/>
            </div>
        @else
            <div class="w-full rounded-2xl overflow-hidden h-min border group-hover:border-gray-300 aspect-square">
                <div class="text-sm text-gray-900 space-y-2 html flex-1 p-3">
                    {!! $announcement->description->limit(300) !!}
                </div>
            </div>
        @endif
        
        <p class="text-lg font-semibold text-gray-900 line-clamp-1 group-hover:underline">
            {{ $announcement?->title }}
        </p>

        <p class="text-xs text-gray-600">
            {{ $announcement?->geo?->name }}
        </p>

        <p class="text-xs text-blue-600">
            {{ $announcement?->created_at?->diffForHumans() }}
        </p>
        <div class="flex items-center space-x-2 z-30">
            <p class="font-bold text-lg w-full ">
                {{ $announcement?->price }}
            </p>
            <livewire:actions.like-dislike :announcement="$announcement"/>
        </div>
    </a>
</div>