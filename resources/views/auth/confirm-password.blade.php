<x-body-layout>
    <form class="bg-white p-4 sm:p-6 max-w-md m-auto my-2 rounded-lg space-y-4 shadow-xl h-full" method="POST" action="{{ route('password.confirm') }}">
        <div class="mb-4 text-sm text-gray-600">
            {{ __('auth.confirm_password_title') }}
        </div>
        @csrf
        <x-honey/>
        <x-honey-recaptcha/> 
        <!-- Password -->
        <div>
            <x-form.label for="password" :value="__('auth.password')" />

            <x-form.input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-form.error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="text-center space-y-4">
            <x-buttons.primary class="w-full text-center justify-center">
                {{ __('auth.confirm') }}
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
</x-body-layout>
