<x-app-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    <form class="bg-white p-4 sm:p-6 max-w-md m-auto my-2 rounded-lg space-y-4 shadow-xl h-full" method="POST" action="{{ route('auth.store') }}">
        @csrf
        <x-honey/>
        <x-honey-recaptcha/> 
        <!-- Email Address -->
        <div>
            <x-form.label for="email" :value="__('auth.email')" />
            <x-form.input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $email)" required autofocus autocomplete="username" />
            <x-form.error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div>
            <x-form.label for="password" :value="__('auth.password')" />

            <x-form.input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-form.error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div>
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">
                    {{ __('auth.remember_me') }}
                </span>
            </label>
        </div>

        <div class="text-center space-y-4">
            <x-buttons.primary class="w-full text-center justify-center">
                {{ __('auth.login') }}
            </x-buttons.primary>

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
