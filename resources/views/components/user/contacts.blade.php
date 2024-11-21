@props(['user' => null])

    <div class="w-full space-y-6">
        @if ($user?->communication?->contact_phone?->visible ?? false)
            <label class="text-gray-500 flex text-sm items-center space-x-1">
                <x-heroicon-o-phone class="size-5"/>
                <span>
                    {{ __('components.user.phone') }}
                </span>
                <a href="tel:{{ $user?->communication?->contact_phone?->phone }}" class="inline-block w-full h-full text-blue-600 hover:underline cursor-pointer">
                    {{ $user?->communication?->contact_phone?->phone }}
                </a>
            </label>
        @endif
    
        @if ($user?->email)
            <label class="text-gray-500 flex text-sm items-center space-x-1">
                <x-heroicon-o-at-symbol class="size-5"/>
                <span>
                    {{ __('components.user.email') }}
                </span>
                <a href="mailto:{{ $user?->email }}"  class="inline-block w-full h-full text-blue-600 hover:underline cursor-pointer">{{ $user?->email }}</a>
            </label>
        @endif

        @if ($user?->communication?->telegram?->visible ?? false)
            <label class="text-gray-500 flex text-sm items-center space-x-1">
                <x-fab-telegram class="size-5 text-blue-500"/>
                <a href="https://t.me/{{ $user?->communication?->telegram?->phone }}" target="_blank" class="inline-block w-full h-full text-blue-600 hover:underline cursor-pointer">
                    Telegram
                </a>
            </label>
        @endif

        @if ($user?->communication?->whatsapp?->visible ?? false)
            <label class="text-gray-500 flex text-sm items-center space-x-1">
                <x-fab-whatsapp-square class="size-5 text-green-500"/>
                <a href="https://api.whatsapp.com/send?phone={{ $user?->communication?->whatsapp?->phone }}" target="_blank" class="inline-block w-full h-full text-blue-600 hover:underline cursor-pointer">
                    WhatsApp
                </a>
            </label>
        @endif
</div>
    

