<x-app-layout>
    <form method="POST" class="bg-white p-4 sm:p-6 max-w-md m-auto my-2 rounded-lg space-y-4 shadow-xl h-full" action="{{ route('login.store') }}">
        <x-auth-session-status class="mb-4" :status="session('status')" />
        @csrf

        <div>
            <x-form.label for="email" :value="__('auth.email')" />
            <x-form.input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-form.error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="text-center space-y-4">
            <x-filament::button 
                type="submit"
                class="w-full"
                color="dark"
            >
                {{ __('auth.continue') }}
            </x-filament::button>

            @if (Route::has('password.request'))
                <a class="block underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('auth.forgot_password') }}
                </a>
            @endif
            <a class="block underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('register') }}">
                {{ __('auth.register') }}
            </a>
        </div>

        <hr>
        <div>
            <small>{{ __('auth.protected_of_google') }} 
                <a href="https://policies.google.com/privacy" class="underline text-blue-500">{{ __('auth.google_privacy') }}</a> {{ __('auth.and') }}
                <a href="https://policies.google.com/terms" class="underline text-blue-500">{{ __('auth.google_terms') }}</a> {{ __('auth.apply') }}.
            </small>
        </div>
    </form>
</x-app-layout>
