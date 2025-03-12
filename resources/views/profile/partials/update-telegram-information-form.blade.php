<x-filament::section>
    <x-slot name="heading">
        {{ __('profile.update_telegram_information_form.title') }}
    </x-slot>
    <x-slot name="description">
        {{ __('profile.update_telegram_information_form.description') }}
    </x-slot>

    <div class="space-y-6 md:space-y-0 md:flex justify-between items-center">
        @if (! auth()->user()->chat)
            <form action="{{ route('inzerko_bot.telegram.connect') }}" method="post" class="max-w-xl">
                @csrf
                <div class="flex items-center gap-4">
                    <x-filament::button
                        type="submit"
                        icon="heroicon-s-user-plus"
                    >
                        {{ __('profile.update_telegram_information_form.connect_telegram') }}
                    </x-filament::button>
                </div>
            </form>
        @else
            <div class="flex items-center">
                <div class="flex-col items-center my-auto">
                    <img src="{{ auth()->user()->chat->avatar }}" alt="Avatar" class="mr-4 w-12 h-12 min-w-[48px] rounded-full">
                </div>
                <div class="flex-col justify-center">
                    <div class="">
                        <div class="w-full text-md font-medium text-gray-900">
                            {{ auth()->user()->chat->first_name ?? null }} {{ $user->chat->last_name ?? null }}
                        </div>
                        <a class="w-full text-sm font-light text-blue-500 hover:underline" href="{{ $user->chat->contact }}" target="_blank">
                            {{ "@".auth()->user()->chat->username }}
                        </a>
                    </div>
                </div>
            </div>
            <form action="{{ route('inzerko_bot.telegram.disconnect') }}" method="post" class="flex items-center h-min">
                @csrf
                <x-filament::button
                    type="submit"
                    color="danger"
                    icon="heroicon-s-user-minus"
                >
                    {{ __('profile.update_telegram_information_form.disconnect_telegram') }}
                </x-filament::button>
            </form>
        @endif
    </div>
</x-filament::section>