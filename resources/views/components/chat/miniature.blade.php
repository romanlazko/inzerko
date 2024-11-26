@props(['user' => null, 'announcement' => null])

<div class="flex space-x-4 items-center">
    <a href="{{ route('announcement.show', $announcement) }}" target="_blank" class="relative cursor-pointer">
        <img src="{{ $announcement?->getFirstMediaUrl('announcements', 'thumb') }}" alt="" class="border-2 border-inherit hover:border-indigo-600 rounded-full w-14 h-14 min-h-14 min-w-14 object-cover aspect-square">
    </a>
    <div class="w-full space-y-2">
        <span class="block font-medium leading-none text-start line-clamp-1">
            {{ str($announcement?->title)->limit(50) }}
        </span>
        <a href="{{ route('profile.show', $user) }}" target="_blank" class="text-gray-500 flex text-xs items-center space-x-1 hover:underline cursor-pointer">
            <img src="{{ $user?->getFirstMediaUrl('avatar', 'thumb') }}" alt="" class="rounded-full w-5 h-5 min-h-5 min-w-5 object-cover aspect-square">
            <span class="text-xs text-gray-900">
                {{ $user?->name }}
            </span>
        </a>
        @if ($user?->languages)
            <label class="text-gray-500 flex text-xs items-center space-x-1">
                <x-heroicon-o-language class="size-4"/>
                <span>
                    {{ __('components.user.languages') }}
                </span>
                @foreach ($user?->languages ?? [] as $language)
                    <span class="text-xs text-gray-900 uppercase">
                        {{ $language }}@if (!$loop->last),@endif
                    </span>
                @endforeach
            </label>
        @endif
    </div>
</div>