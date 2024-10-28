<x-app-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form class="bg-white p-4 sm:p-6 max-w-md m-auto my-2 rounded-lg space-y-4 shadow-xl h-full" method="POST" action="{{ route('password.email') }}">
        <div class="text-sm text-gray-600">
            {{ __('auth.forgot_password_title') }}
        </div>
        @csrf
        <x-honey/>
        <x-honey-recaptcha/> 

        <!-- Email Address -->
        <div>
            <x-form.label for="email" :value="__('auth.email')" />
            <x-form.input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-form.error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="text-center space-y-4">
            <x-buttons.primary class="w-full text-center justify-center">
                {{ __('auth.send_password_reset_link') }}
            </x-buttons.primary>
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
