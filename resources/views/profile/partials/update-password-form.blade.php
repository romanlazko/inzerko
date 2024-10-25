<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('profile.update_passport_form.title') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('profile.update_passport_form.description') }}
        </p>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6 max-w-xl">
        @csrf
        @method('put')

        <div>
            <x-form.label for="update_password_current_password" :value="__('profile.update_passport_form.current_password')" />
            <x-form.input id="update_password_current_password" name="current_password" type="password" class="mt-1 block w-full" autocomplete="current-password" />
            <x-form.error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
        </div>

        <div>
            <x-form.label for="update_password_password" :value="__('profile.update_passport_form.new_password')" />
            <x-form.input id="update_password_password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-form.error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-form.label for="update_password_password_confirmation" :value="__('profile.update_passport_form.new_password_confirmation')" />
            <x-form.input id="update_password_password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-form.error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center gap-4">
            <x-buttons.primary>{{ __('profile.save') }}</x-buttons.primary>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600"
                >{{ __('profile.saved.') }}</p>
            @endif
        </div>
    </form>
</section>
