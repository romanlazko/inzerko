@props(['user' => null])

<div class="w-full space-y-6">
    <div class="w-full space-y-4">
        @if ($user?->phone)
            <label class="text-gray-500 flex text-sm items-center space-x-1">
                <x-heroicon-o-phone class="size-5"/>
                <span>
                    {{ __('components.user.phone') }}
                </span>
                <a href="tel:{{ $user?->phone }}" class="inline-block w-full h-full text-blue-600 hover:underline cursor-pointer">{{ $user?->phone }}</a>
            </label>
        @endif
    
        @if ($user?->email)
            <label class="text-gray-500 flex text-sm items-center space-x-1">
                <x-heroicon-o-at-symbol class="size-5"/>
                <span>
                    {{ __('components.user.email') }}
                </span>
                <a href="mailto:{{ $user?->email }}" class="inline-block w-full h-full text-blue-600 hover:underline cursor-pointer">{{ $user?->email }}</a>
            </label>
        @endif
    
        @if ($user?->chat)
            <label class="text-gray-500 flex text-sm items-center space-x-1">
                <x-heroicon-o-paper-airplane class="size-5"/>
                <span>
                    {{ __('components.user.telegram') }}
                </span>
                <a href="{{ $user?->chat?->contact }}" class="inline-block w-full h-full text-blue-600 hover:underline cursor-pointer">
                    {{ $user?->chat?->username ?? "@{$user?->chat?->first_name} {$user?->chat?->last_name}" }}
                </a>
            </label>
        @endif
    </div>
</div>
    

