<x-filament::section
    @class(['empty-section-with-header' => ! auth()->user()->chat])
>
    <x-slot name="heading">
        {{ __('profile.update_telegram_information_form.title') }}
    </x-slot>

    <x-slot name="description">
        {{ __('profile.update_telegram_information_form.description') }}
    </x-slot>

    <x-slot name="headerActions">
        @if (auth()->user()->chat)
            <form action="{{ route('inzerko_bot.telegram.disconnect') }}" method="post">
                @csrf
                <x-filament::button
                    type="submit"
                    color="danger"
                    icon="heroicon-s-user-minus"
                >
                    {{ __('profile.update_telegram_information_form.disconnect_telegram') }}
                </x-filament::button>
            </form>
        @else
            <form action="{{ route('inzerko_bot.telegram.connect') }}" method="post">
                @csrf
                <div class="">
                    <x-filament::button
                        type="submit"
                        icon="heroicon-s-user-plus"
                    >
                        {{ __('profile.update_telegram_information_form.connect_telegram') }}
                    </x-filament::button>
                </div>
            </form>
        @endif
    </x-slot>
    @if (auth()->user()->chat)
        <div class="space-y-6 md:space-y-0 md:flex justify-between items-center">
            <div class="flex items-center">
                <div class="flex-col items-center my-auto">
                    <img src="{{ auth()->user()->chat?->avatar }}" alt="Avatar" class="mr-4 w-12 h-12 min-w-[48px] rounded-full">
                </div>
                <div class="flex-col justify-center">
                    <div class="">
                        <div class="w-full text-md font-medium text-gray-900">
                            {{ auth()->user()->chat?->first_name ?? null }} {{ auth()->user()->chat?->last_name ?? null }}
                        </div>
                        <p class="w-full text-sm font-light text-blue-500 hover:underline">
                            {{ "@".auth()->user()->chat?->username }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
        @if (! auth()->user()->chat->username)
            <div class="p-4 bg-red-600 text-white rounded-2xl max-w-xl mt-6">
                <div class="text-sm">
                    {{ __('profile.update_telegram_information_form.username_required') }}
                </div>
            </div>
        @endif
    @endif
</x-filament::section>