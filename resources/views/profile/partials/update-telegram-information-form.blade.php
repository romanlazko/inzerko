<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('profile.update_telegram_information_form.title') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('profile.update_telegram_information_form.description') }}
        </p>
    </header>

    @if (! auth()->user()->chat)
        <form action="{{ route('inzerko_bot.telegram.connect') }}" method="post">
            @csrf
            <button class="mt-6 text-sm text-blue-500 hover:text-blue-700 hover:underline">{{ __('profile.update_telegram_information_form.connect_telegram') }}</button>
        </form>
    @else
        <div class="flex items-center">
            <div class="flex-col items-center my-auto">
                <img src="{{ auth()->user()->chat->avatar }}" alt="Avatar" class="mr-4 w-12 h-12 min-w-[48px] rounded-full">
            </div>
            <div class="flex-col justify-center">
                <div class="">
                    <div class="w-full text-md font-medium text-gray-900">
                        {{ auth()->user()->chat->first_name ?? null }} {{ auth()->user()->chat->last_name ?? null }}
                    </div>
                    <a class="w-full text-sm font-light text-blue-500 hover:underline" href="{{ auth()->user()->chat->contact }}" target="_blank">
                        {{ "@".(auth()->user()->chat->username ?? auth()->user()->chat->first_name.auth()->user()->chat->last_name) }}
                    </a>
                </div>
            </div>
        </div>
    @endif
</section>