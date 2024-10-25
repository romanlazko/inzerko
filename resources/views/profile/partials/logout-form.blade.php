<form method="POST" action="{{ route('logout') }}">
    @csrf
    <x-buttons.danger type="submit">
        {{ __('profile.logout') }}
    </x-buttons.danger>
</form>