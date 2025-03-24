@props(['user' => null])

<div class="w-full space-y-6">
    @if ($user?->email)
        <label class="text-gray-500 flex text-sm items-center space-x-1">
            <x-heroicon-o-at-symbol class="size-6"/>
            <a href="mailto:{{ $user?->email }}"  class="inline-block w-full h-full text-blue-600 hover:underline cursor-pointer">
                {{ $user?->email }}
            </a>
        </label>
    @endif

    @if ($user?->chat?->username ?? false)
        <label class="text-gray-500 flex text-sm items-center space-x-1">
            <x-fab-telegram class="size-5 text-blue-500"/>
            <a href="https://t.me/{{ $user?->chat?->username }}" target="_blank" class="inline-block w-full h-full text-blue-600 hover:underline cursor-pointer">
                {{ $user?->chat?->username }}
            </a>
        </label>
    @endif

    @foreach ($user?->contacts as $contact)
        <label class="text-gray-500 flex text-sm items-center space-x-1">
            <x-filament::icon-button
                icon="{{ $contact->icon }}"
                color="{{ $contact->color }}"
                href="{{ $contact->url }}"
                tag="a"
                label="{{ $contact->link }}"
            />
            <a href="{{ $contact->url }}" target="_blank" class="inline-block w-full h-full text-blue-600 hover:underline cursor-pointer">
                {{ $contact->link }}
            </a>
        </label>
    @endforeach
</div>
    

