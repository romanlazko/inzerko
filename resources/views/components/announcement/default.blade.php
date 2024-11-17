@props([
    'layout' => 'sm', 
    'announcement' => null
])

<div {{ $attributes->merge(['class' => "w-full transition ease-in-out duration-150 group space-y-1 block w-full announcement-card cursor-pointer"]) }}>
    <a href="{{ route('announcement.show', $announcement) }}" class="space-y-1">
        <div class="w-full rounded-2xl overflow-hidden h-min border group-hover:border-gray-400 aspect-square items-center flex bg-white">
            @if ($announcement?->media?->isNotEmpty())
                <x-ux.slider :medias="$announcement?->getMedia('announcements')" conversion="medium" :withDots="true"/>
            @else
                <div class="text-gray-900 space-y-2 p-3 my-auto h-min w-full text-center justify-center">
                    <div class="items-center justify-center flex w-full">
                        <img src="{{ $announcement->category?->getFirstMediaUrl('categories', 'thumb') }}" alt="" class="float-right w-12 h-12 min-h-12 min-w-12">
                    </div>
                    <p class="w-full text-indigo-600 group-hover:text-indigo-500 text-xl font-extrabold">{{ $announcement->category?->name }}</p>
                    <p class="dynamic-text w-full">{{ str($announcement->title)->limit(50) }}</p>
                </div>
            @endif
        </div>
        
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

@pushOnce('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const textElements = document.querySelectorAll('.dynamic-text');

            textElements.forEach(textElement => {
                const textLength = textElement.textContent.length;

                if (textLength < 20) {
                    textElement.classList.add('text-xl');
                } else if (textLength < 50) {
                    textElement.classList.add('text-lg');
                } else {
                    textElement.classList.add('text-base');
                }
            });
        });
    </script>
@endPushOnce
