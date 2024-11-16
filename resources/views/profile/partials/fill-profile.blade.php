@if (! $user->isProfileFilled())
    <div class="p-4 bg-red-600 text-white rounded-2xl">
        <div class="text-sm">
            {{ __('profile.fill_profile.description') }}
        </div>
    </div>
@endif