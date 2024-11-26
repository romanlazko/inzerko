<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('profile.update_communication_information_form.title') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('profile.update_communication_information_form.description') }}
        </p>
    </header>

    <form method="post" action="{{ route('profile.update-communication') }}" class="mt-6 space-y-6 max-w-xl">
        @csrf
        @method('patch')
        
        <div>
            <x-form.label :value="__('profile.update_communication_information_form.contact_information')" :required="true"/>

            <div class="w-full items-center p-1 border border-gray-300 rounded-lg mt-1 space-y-1">
                <x-form.label for="contact_phone" class="items-center grid grid-cols-4 gap-2 w-full justify-between hover:bg-gray-50 p-3 rounded-lg">
                    <div class="flex space-x-1 col-span-3">
                        <x-heroicon-s-phone-arrow-down-left class="size-5 text-indigo-500"/>
                        <span class="font-normal">
                            {{ __('profile.update_communication_information_form.contact_phone') }}
                        </span>
                    </div>
                    
                    <x-form.checkbox 
                        name="communication_settings[contact_phone][visible]" 
                        class="peer/phone ml-auto col-span-1" 
                        value="1" 
                        :checked="old('communication_settings.contact_phone.visible', $user->communication_settings?->contact_phone?->visible ?? false)"
                    />
    
                    <div class="hidden peer-checked/phone:block col-span-full space-y-2">
                        <x-form.label 
                            for="contact_phone" 
                            :value="__('profile.update_communication_information_form.contact_phone_hint')" 
                            :required="true" 
                            class="text-xs font-normal text-gray-400"
                        />

                        <x-form.input 
                            id="contact_phone" 
                            name="communication_settings[contact_phone][phone]"
                            type="tel" 
                            class="block w-full font-normal text-sm phone" 
                            :value="old('communication_settings.contact_phone.phone', $user->communication_settings?->contact_phone?->phone)" 
                            autofocus
                        />

                        <x-form.error 
                            class="mt-2" 
                            :messages="$errors->get('communication_settings.contact_phone.phone')"
                        />
                    </div>
                </x-form.label>
        
                <hr class="mx-2">
    
                <x-form.label for="telegram" class="items-center grid grid-cols-4 gap-2 w-full justify-between hover:bg-gray-50 p-3 rounded-lg">
                    <div class="flex space-x-1 col-span-3">
                        <x-fab-telegram class="size-5 text-blue-500"/>
                        <span class="font-normal">
                            {{ __('Telegram') }}
                        </span>
                    </div>
    
                    <x-form.checkbox
                        name="communication_settings[telegram][visible]" 
                        class="peer/telegram ml-auto col-span-1" 
                        value="1" 
                        :checked="old('communication_settings.telegram.visible', $user->communication_settings?->telegram?->visible ?? false)"
                    />
    
                    <div class="hidden peer-checked/telegram:block col-span-full space-y-2">
                        <x-form.label 
                            for="telegram" 
                            :value="__('profile.update_communication_information_form.telegram_phone_hint')" 
                            :required="true" 
                            class="text-xs font-normal text-gray-400"
                        />

                        <x-form.input 
                            id="telegram" 
                            name="communication_settings[telegram][phone]" 
                            type="tel" 
                            class="block w-full font-normal text-sm phone" 
                            :value="old('communication_settings.telegram.phone', $user->communication_settings?->telegram?->phone)" 
                            autofocus
                        />

                        <x-form.error 
                            class="mt-2" 
                            :messages="$errors->get('communication_settings.telegram.phone')" 
                        />
                    </div>
                </x-form.label>
        
                <hr class="mx-2">
                
                <x-form.label for="whatsapp" class="items-center grid grid-cols-4 gap-2 w-full justify-between hover:bg-gray-50 p-3 rounded-lg">
                    <div class="flex space-x-1 col-span-3">
                        <x-fab-whatsapp-square class="size-5 text-green-500"/>
                        <span class="font-normal">
                            {{ __('WhatsApp') }}
                        </span>
                    </div>
    
                    <x-form.checkbox
                        name="communication_settings[whatsapp][visible]" 
                        class="peer/whatsapp ml-auto col-span-1" 
                        value="1" 
                        :checked="old('communication_settings.whatsapp.visible', $user->communication_settings?->whatsapp?->visible ?? false)"
                    />
    
                    <div class="hidden peer-checked/whatsapp:block col-span-full space-y-2">
                        <x-form.label 
                            for="whatsapp"
                            :value="__('profile.update_communication_information_form.whatsapp_phone_hint')" 
                            :required="true" 
                            class="text-xs font-normal text-gray-400"
                        />

                        <x-form.input 
                            id="whatsapp" 
                            name="communication_settings[whatsapp][phone]" 
                            type="text" 
                            class="block w-full font-normal text-sm phone" 
                            :value="old('communication_settings.whatsapp.phone', $user->communication_settings?->whatsapp?->phone)" 
                            autofocus
                        />

                        <x-form.error 
                            class="mt-2" 
                            :messages="$errors->get('communication_settings.whatsapp.phone')" 
                        />
                    </div>
                </x-form.label>
            </div>

            <x-form.error class="mt-2" :messages="$errors->get('communication_settings')" />
            <x-form.error class="mt-2" :messages="$errors->get('communication_settings.*.visible')" />
        </div>

        <div>
            <x-form.label :value="__('profile.update_communication_information_form.languages')" :required="true"/>

            <div class="w-full items-center p-1 border border-gray-300 rounded-lg mt-1 space-y-1">
                <x-form.label for="en" class="items-center flex space-x-2 w-full justify-between hover:bg-gray-50 p-3 rounded-lg">
                    <span class="font-normal">
                        🇺🇸 {{ __('profile.update_communication_information_form.english') }}
                    </span>
                    <x-form.checkbox id="en" name="communication_settings[languages][]" value="en" :checked="in_array('en', old('lang', $user->communication_settings->languages ?? []))"/>
                </x-form.label>

                <hr class="mx-2">

                <x-form.label for="ru" class="items-center flex space-x-2 w-full justify-between hover:bg-gray-50 p-3 rounded-lg">
                    <span class="font-normal">
                        🇷🇺 {{ __('profile.update_communication_information_form.russian') }}
                    </span>
                    <x-form.checkbox id="ru" name="communication_settings[languages][]" value="ru" :checked="in_array('ru', old('lang', $user->communication_settings->languages ?? []))"/>
                </x-form.label>

                <hr class="mx-2">
                
                <x-form.label for="cz" class="items-center flex space-x-2 w-full justify-between hover:bg-gray-50 p-3 rounded-lg">
                    <span class="font-normal">
                        🇨🇿 {{ __('profile.update_communication_information_form.czech') }}
                    </span>
                    <x-form.checkbox id="cz" name="communication_settings[languages][]" value="cz" :checked="in_array('cz', old('lang', $user->communication_settings->languages ?? []))"/>
                </x-form.label>
            </div>
            
            <x-form.error class="mt-2" :messages="$errors->get('communication_settings.languages')" />
        </div>
        
        <div class="flex items-center gap-4">
            <x-buttons.primary>{{ __('profile.save') }}</x-buttons.primary>
        </div>
    </form>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const inputs = document.querySelectorAll('.phone');
                inputs.forEach(input => {
                    if (input) {
                        let iti = window.intlTelInput(input, {
                            initialCountry: 'cz',
                            geoIpLookup: null,
                            strictMode: true,
                            separateDialCode: true,
                            hiddenInput: () => ({ phone: "communication_settings[" + input.getAttribute('id') + "][phone]"}),
                            loadUtilsOnInit: 'https://cdn.jsdelivr.net/npm/intl-tel-input/build/js/utils.js', // для форматирования
                        });

                        const validate = () => {
                            if (input.value === '') {
                                input.setCustomValidity('');
                            } else if (!iti.isValidNumber()) {
                                input.setCustomValidity(@json(__('validation.custom.phone')));

                                event.preventDefault();
                            } else {
                                input.setCustomValidity('');
                            }
                        };
                        input.closest('form').addEventListener('submit', validate);
                        input.addEventListener('change', validate);
                        input.addEventListener('keyup', validate);
                    }
                });
            });
        </script>
    @endpush
</section>