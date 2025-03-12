<x-filament::section>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <x-filament::button
            type="submit"
            color="danger"
            icon="heroicon-c-arrow-right-start-on-rectangle"
        >
            {{ __('profile.logout') }}
        </x-filament::button>
    </form>
</x-filament::section>