@props(['user' => null])

    <div class="w-full space-y-6">
        @if ($user?->communication_settings?->contact_phone?->visible ?? false)
            <label class="text-gray-500 flex text-sm items-center space-x-1">
                <x-heroicon-o-phone class="size-5"/>
                <a href="tel:{{ $user?->communication_settings?->contact_phone?->phone }}" class="inline-block w-full h-full text-blue-600 hover:underline cursor-pointer">
                    {{ $user?->communication_settings?->contact_phone?->phone }}
                </a>
            </label>
        @endif
    
        @if ($user?->email)
            <label class="text-gray-500 flex text-sm items-center space-x-1">
                <x-heroicon-o-at-symbol class="size-6"/>
                <a href="mailto:{{ $user?->email }}"  class="inline-block w-full h-full text-blue-600 hover:underline cursor-pointer">
                    {{ $user?->email }}
                </a>
            </label>
        @endif

        @if ($user?->communication_settings?->telegram?->visible ?? false)
            <label class="text-gray-500 flex text-sm items-center space-x-1">
                <x-fab-telegram class="size-5 text-blue-500"/>
                <a href="https://t.me/{{ str($user?->communication_settings?->telegram?->phone)->replace(' ', '') }}" target="_blank" class="inline-block w-full h-full text-blue-600 hover:underline cursor-pointer">
                    {{ str($user?->communication_settings?->telegram?->phone) }}
                </a>
            </label>
        @endif

        @if ($user?->communication_settings?->whatsapp?->visible ?? false)
            <label class="text-gray-500 flex text-sm items-center space-x-1">
                <x-fab-whatsapp-square class="size-5 text-green-500"/>
                <a href="https://wa.me/{{ str($user?->communication_settings?->whatsapp?->phone)->replace(' ', '') }}" target="_blank" class="inline-block w-full h-full text-blue-600 hover:underline cursor-pointer">
                    {{ str($user?->communication_settings?->whatsapp?->phone) }}
                </a>
            </label>
        @endif
</div>
    

