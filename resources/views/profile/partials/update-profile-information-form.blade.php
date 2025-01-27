<section>
    <header class="flex space-x-6">
        <form method="post" action="{{ route('profile.update-avatar') }}" enctype="multipart/form-data" onsubmit="event.target.submit(); return false;">
            @csrf
            @method('patch')
            <label for="avatar" class="block w-14 h-14 min-w-14 min-h-14 rounded-full border-2 hover:border-indigo-700 overflow-hidden">
                <img src="{{ $user->getFirstMediaUrl('avatar', 'thumb') }}" class="object-cover w-full h-full" alt="">
                <input type="file" name="avatar" id="avatar" class="hidden" accept="image/*" onchange="event.target.form.submit();">
            </label>
            <x-form.error class="mt-2" :messages="$errors->get('avatar')" />
        </form>
        <div>
            <h2 class="text-lg font-medium text-gray-900">
                {{ __('profile.update_profile_information_form.title') }}
            </h2>
    
            <p class="mt-1 text-sm text-gray-600">
                {{ __('profile.update_profile_information_form.description') }}
            </p>
        </div>
    </header>

    <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="mt-6 space-y-6 max-w-xl">
        @csrf
        @method('patch')

        <div>
            <x-form.label for="name" :value="__('profile.update_profile_information_form.name')" :required="true"/>
            <x-form.input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-form.error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-form.label for="email" :value="__('profile.update_profile_information_form.email')" :required="true"/>
            <x-form.input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-form.error class="mt-2" :messages="$errors->get('email')" />
        </div>

        

        <div class="flex items-center gap-4">
            <x-buttons.primary>{{ __('profile.save') }}</x-buttons.primary>
        </div>
    </form>
</section>
