<section class="space-y-6">
    <header class="flex space-x-6">
        <div>
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('profile.new_message_notifications_form.title') }}
            </h2>
    
            <p class="mt-1 text-sm text-gray-600">
                {{ __('profile.new_message_notifications_form.description') }}
            </p>
        </div>
    </header>
    
    <div class="w-full items-center p-1 border border-gray-300 rounded-lg mt-1 space-y-1">
        <x-form.label for="telegram" class="items-center flex space-x-2 w-full justify-between hover:bg-gray-50 p-3 rounded-lg" :disabled="!$user->chat">
            <span class="font-normal">
                {{ __('profile.new_message_notifications_form.telegram') }}
            </span>
            <x-form.checkbox id="telegram" name="notification_settings[telegram]" value="1" :checked="$user->notification_settings?->telegram ?? false" :disabled="!$user->chat"/>
        </x-form.label>

        <hr class="mx-2">
        
        <x-form.label for="email" class="items-center flex space-x-2 w-full justify-between hover:bg-gray-50 p-3 rounded-lg">
            <span class="text-black font-normal">
                {{ __('profile.new_message_notifications_form.email') }}
            </span>
            <x-form.checkbox id="email" name="notification_settings[email]" value="1" :checked="$user->notification_settings?->email ?? false" />
        </x-form.label>
    </div>
</section>
