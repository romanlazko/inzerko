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
                <x-form.label for="contact_phone_visible" class="items-center grid grid-cols-4 gap-2 w-full justify-between hover:bg-gray-50 p-3 rounded-lg">
                    <div class="flex space-x-1 col-span-3">
                        <x-heroicon-s-phone-arrow-down-left class="size-5 text-indigo-500"/>
                        <span class="font-normal">
                            {{ __('profile.update_communication_information_form.contact_phone') }}
                        </span>
                    </div>
                    
                    <x-form.checkbox 
                        id="contact_phone_visible" 
                        name="communication[contact_phone][visible]" 
                        class="peer/phone ml-auto col-span-1" 
                        value="1" 
                        :checked="old('communication.contact_phone.visible', $user->communication?->contact_phone?->visible ?? false)"
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
                            name="communication[contact_phone][phone]" 
                            type="text" 
                            class="block w-full font-normal text-sm" 
                            :value="old('communication.contact_phone.phone', $user->communication?->contact_phone?->phone)" 
                            autofocus 
                            autocomplete="phone"
                            placeholder="{{ __('profile.update_communication_information_form.contact_phone') }}"
                        />
                        <x-form.error 
                            class="mt-2" 
                            :messages="$errors->get('communication.contact_phone.phone')"
                        />
                    </div>
                </x-form.label>
        
                <hr class="mx-2">
    
                <x-form.label for="telegram_visible" class="items-center grid grid-cols-4 gap-2 w-full justify-between hover:bg-gray-50 p-3 rounded-lg">
                    <div class="flex space-x-1 col-span-3">
                        <x-fab-telegram class="size-5 text-blue-500"/>
                        <span class="font-normal">
                            {{ __('Telegram') }}
                        </span>
                    </div>
    
                    <x-form.checkbox 
                        id="telegram_visible" 
                        name="communication[telegram][visible]" 
                        class="peer/telegram ml-auto col-span-1" 
                        value="1" 
                        :checked="old('communication.telegram.visible', $user->communication?->telegram?->visible ?? false)"
                    />
    
                    <div class="hidden peer-checked/telegram:block col-span-full space-y-2">
                        <x-form.label 
                            for="telegram_phone" 
                            :value="__('profile.update_communication_information_form.telegram_phone_hint')" 
                            :required="true" 
                            class="text-xs font-normal text-gray-400"
                        />
                        <x-form.input 
                            id="telegram_phone" 
                            name="communication[telegram][phone]" 
                            type="text" 
                            class="block w-full font-normal text-sm" 
                            :value="old('communication.telegram.phone', $user->communication?->telegram?->phone)" 
                            autofocus 
                            autocomplete="phone"
                            placeholder="{{ __('profile.update_communication_information_form.telegram_phone') }}"
                        />
                        <x-form.error 
                            class="mt-2" 
                            :messages="$errors->get('communication.telegram.phone')" 
                        />
                    </div>
                </x-form.label>
        
                <hr class="mx-2">
                
                <x-form.label for="whatsapp_visible" class="items-center grid grid-cols-4 gap-2 w-full justify-between hover:bg-gray-50 p-3 rounded-lg">
                    <div class="flex space-x-1 col-span-3">
                        <x-fab-whatsapp-square class="size-5 text-green-500"/>
                        <span class="font-normal">
                            {{ __('WhatsApp') }}
                        </span>
                    </div>
    
                    <x-form.checkbox 
                        id="whatsapp_visible" 
                        name="communication[whatsapp][visible]" 
                        class="peer/whatsapp ml-auto col-span-1" 
                        value="1" 
                        :checked="old('communication.whatsapp.visible', $user->communication?->whatsapp?->visible ?? false)"
                    />
    
                    <div class="hidden peer-checked/whatsapp:block col-span-full space-y-2">
                        <x-form.label 
                            for="phone"
                            :value="__('profile.update_communication_information_form.whatsapp_phone_hint')" 
                            :required="true" 
                            class="text-xs font-normal text-gray-400"
                        />
                        <x-form.input 
                            id="phone" 
                            name="communication[whatsapp][phone]" 
                            type="text" 
                            class="block w-full font-normal text-sm" 
                            :value="old('communication.whatsapp.phone', $user->communication?->whatsapp?->phone)" 
                            autofocus 
                            autocomplete="phone"
                            placeholder="{{ __('profile.update_communication_information_form.whatsapp_phone') }}"
                        />
                        <x-form.error 
                            class="mt-2" 
                            :messages="$errors->get('communication.whatsapp.phone')" 
                        />
                    </div>
                </x-form.label>
            </div>

            <x-form.error class="mt-2" :messages="$errors->get('communication')" />
            <x-form.error class="mt-2" :messages="$errors->get('communication.*.visible')" />
        </div>

        <div>
            <x-form.label :value="__('profile.update_communication_information_form.languages')" :required="true"/>

            <div class="w-full items-center p-1 border border-gray-300 rounded-lg mt-1 space-y-1">
                <x-form.label for="en" class="items-center flex space-x-2 w-full justify-between hover:bg-gray-50 p-3 rounded-lg">
                    <span class="font-normal">
                        🇺🇸 {{ __('profile.update_communication_information_form.english') }}
                    </span>
                    <x-form.checkbox id="en" name="lang[]" value="en" :checked="in_array('en', old('lang', $user->lang ?? []))"/>
                </x-form.label>

                <hr class="mx-2">

                <x-form.label for="ru" class="items-center flex space-x-2 w-full justify-between hover:bg-gray-50 p-3 rounded-lg">
                    <span class="font-normal">
                        🇷🇺 {{ __('profile.update_communication_information_form.russian') }}
                    </span>
                    <x-form.checkbox id="ru" name="lang[]" value="ru" :checked="in_array('ru', old('lang', $user->lang ?? []))"/>
                </x-form.label>

                <hr class="mx-2">
                
                <x-form.label for="cz" class="items-center flex space-x-2 w-full justify-between hover:bg-gray-50 p-3 rounded-lg">
                    <span class="font-normal">
                        🇨🇿 {{ __('profile.update_communication_information_form.czech') }}
                    </span>
                    <x-form.checkbox id="cz" name="lang[]" value="cz" :checked="in_array('cz', old('lang', $user->lang ?? []))"/>
                </x-form.label>
            </div>
            
            <x-form.error class="mt-2" :messages="$errors->get('lang')" />
        </div>
        
        <div class="flex items-center gap-4">
            <x-buttons.primary>{{ __('profile.save') }}</x-buttons.primary>
        </div>
    </form>

    
</section>