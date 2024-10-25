<x-body-layout>
    <form class="bg-white p-4 sm:p-6 max-w-md m-auto my-2 rounded-lg space-y-4 shadow-xl h-full" method="POST" action="{{ route('password.store') }}">
        @csrf
        <x-honey-recaptcha/> 
        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-form.label for="email" :value="__('auth.email')" />
            <x-form.input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required autofocus autocomplete="username" />
            <x-form.error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-form.label for="password" :value="__('auth.password')" />
            <x-form.input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            <x-form.error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-form.label for="password_confirmation" :value="__('auth.confirm_password')" />

            <x-form.input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />

            <x-form.error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="text-center space-y-4">
            <x-buttons.primary class="w-full text-center justify-center">
                {{ __('auth.reset_password') }}
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
