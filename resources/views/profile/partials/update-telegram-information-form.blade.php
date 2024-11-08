<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('profile.update_telegram_information_form.title') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('profile.update_telegram_information_form.description') }}
        </p>
    </header>

    @if (! $user->chat)
        <form action="{{ route('inzerko_bot.telegram.connect') }}" method="post" class="mt-6 space-y-6 max-w-xl">
            @csrf
            <div class="flex items-center gap-4">
                <x-buttons.primary>{{ __('profile.update_telegram_information_form.connect_telegram') }}</x-buttons.primary>
            </div>
        </form>
    @else
        <div class="flex items-center">
            <div class="flex-col items-center my-auto">
                <img src="{{ $user->chat->avatar }}" alt="Avatar" class="mr-4 w-12 h-12 min-w-[48px] rounded-full">
            </div>
            <div class="flex-col justify-center">
                <div class="">
                    <div class="w-full text-md font-medium text-gray-900">
                        {{ $user->chat->first_name ?? null }} {{ $user->chat->last_name ?? null }}
                    </div>
                    <a class="w-full text-sm font-light text-blue-500 hover:underline" href="{{ $user->chat->contact }}" target="_blank">
                        {{ "@".($user->chat->username ?? $user->chat->first_name.$user->chat->last_name) }}
                    </a>
                </div>
            </div>
        </div>
        <form action="{{ route('inzerko_bot.telegram.disconnect') }}" method="post" class="mt-6 space-y-6 max-w-xl">
            @csrf
            <div class="flex items-center gap-4">
                <x-buttons.primary>{{ __('profile.update_telegram_information_form.disconnect_telegram') }}</x-buttons.primary>
            </div>
        </form>
    @endif
</section>