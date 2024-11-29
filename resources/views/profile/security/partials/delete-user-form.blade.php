<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('profile.delete_user_form.title') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('profile.delete_user_form.description') }}
        </p>
    </header>

    <x-a-buttons.delete
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >{{ __('profile.delete_user_form.delete_account') }}</x-a-buttons.delete>

    <x-ux.modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <x-ux.white-block>
            <form method="post" action="{{ route('profile.security.destroy') }}" class="p-6">
                @csrf
                @method('delete')

                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                    {{ __('profile.delete_user_form.modal_title') }}
                </h2>

                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    {{ __('profile.delete_user_form.modal_description') }}
                </p>

                <div class="mt-6">
                    <x-form.label for="password" value="{{ __('profile.password') }}" class="sr-only" />

                    <x-form.input
                        id="password"
                        name="password"
                        type="password"
                        class="mt-1 block w-3/4"
                        placeholder="{{ __('profile.password') }}"
                    />

                    <x-form.error :messages="$errors->userDeletion->get('password')" class="mt-2" />
                </div>

                <div class="mt-6 flex justify-end">
                    <x-buttons.secondary x-on:click="$dispatch('close')">
                        {{ __('profile.delete_user_form.cancel') }}
                    </x-buttons.secondary>

                    <x-buttons.delete class="ms-3">
                        {{ __('profile.delete_user_form.delete_account') }}
                    </x-buttons.delete>
                </div>
            </form>
        </x-ux.white-block>
    </x-ux.modal>
</section>