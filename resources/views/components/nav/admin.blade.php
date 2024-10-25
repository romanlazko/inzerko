<nav class="space-y-3 px-2">
    @hasrole('super-duper-admin')
        <x-nav.dropdown :active="request()->routeIs('admin.announcement.*')">
            <x-slot name="trigger">
                {{ __('components.navigation.announcements') }}
            </x-slot>
            <x-nav.responsive-link href="{{ route('admin.announcement.moderation') }}" :active="request()->routeIs('admin.announcement.moderation')">
                {{ __('components.navigation.moderation') }}
            </x-nav.responsive-link>
            <x-nav.responsive-link href="{{ route('admin.announcement.announcements') }}" :active="request()->routeIs('admin.announcement.announcements')">
                {{ __('components.navigation.all_announcements') }}
            </x-nav.responsive-link>
        </x-nav.dropdown>
    @endhasrole

    @hasrole('super-duper-admin')
        <x-nav.dropdown :active="request()->routeIs('admin.telegram.*')">
            <x-slot name="trigger">
                {{ __('components.navigation.telegram') }}
            </x-slot>
            <x-nav.responsive-link href="{{ route('admin.telegram.bots') }}" :active="request()->routeIs('admin.telegram.bots')">
                {{ __('components.navigation.bots') }}
            </x-nav.responsive-link>
            @foreach (\App\Models\TelegramBot::select('first_name', 'id')->get() as $bot)
                <x-nav.dropdown :active="request()->routeIs('admin.telegram.chats', $bot->id) || request()->routeIs('admin.telegram.logs', $bot->id) || request()->routeIs('admin.telegram.channels', $bot->id)">
                    <x-slot name="trigger">
                        {{ $bot->first_name }}
                    </x-slot>
                    <x-nav.responsive-link href="{{ route('admin.telegram.chats', $bot->id) }}" :active="request()->routeIs('admin.telegram.chats', $bot->id)">
                        {{ __('components.navigation.chats') }}
                    </x-nav.responsive-link>
                    <x-nav.responsive-link href="{{ route('admin.telegram.channels', $bot->id) }}" :active="request()->routeIs('admin.telegram.channels', $bot->id)">
                        {{ __('components.navigation.channels') }}
                    </x-nav.responsive-link>
                    <x-nav.responsive-link href="{{ route('admin.telegram.logs', $bot->id) }}" :active="request()->routeIs('admin.telegram.logs', $bot->id)">
                        {{ __('components.navigation.logs') }}
                    </x-nav.responsive-link>
                </x-nav.dropdown>
            @endforeach
        </x-nav.dropdown>
    @endhasrole

    @hasrole('super-duper-admin')
        <x-nav.dropdown :active="request()->routeIs('admin.users.*')">
            <x-slot name="trigger">
                {{ __('components.navigation.users') }}
            </x-slot>
            <x-nav.responsive-link href="{{ route('admin.users.users') }}" :active="request()->routeIs('admin.users.users')">
                {{ __('components.navigation.all_users') }}
            </x-nav.responsive-link>
            {{-- <x-nav.responsive-link href="{{ route('admin.users') }}" :active="request()->routeIs('admin.users')">
                {{ __('Rolles') }}
            </x-nav.responsive-link>
            <x-nav.responsive-link href="{{ route('admin.users') }}" :active="request()->routeIs('admin.users')">
                {{ __('Permissions') }}
            </x-nav.responsive-link> --}}
        </x-nav.dropdown>
    @endhasrole

    @hasrole('super-duper-admin')
        <x-nav.dropdown :active="request()->routeIs('admin.setting.*')">
            <x-slot name="trigger">
                {{ __('components.navigation.settings') }}
            </x-slot>

            <x-nav.responsive-link href="{{ route('admin.setting.categories') }}" :active="request()->routeIs('admin.setting.categories')">
                {{ __('components.navigation.categories') }}
            </x-nav.responsive-link>
            <x-nav.responsive-link href="{{ route('admin.setting.attributes') }}" :active="request()->routeIs('admin.setting.attributes')">
                {{ __('components.navigation.attributes') }}
            </x-nav.responsive-link>
            <x-nav.responsive-link href="{{ route('admin.setting.sections') }}" :active="request()->routeIs('admin.setting.sections')">
                {{ __('components.navigation.sections') }}
            </x-nav.responsive-link>
            <x-nav.responsive-link href="{{ route('admin.setting.sortings') }}" :active="request()->routeIs('admin.setting.sortings')">
                {{ __('components.navigation.sortings') }}
            </x-nav.responsive-link>
        </x-nav.dropdown>
    @endhasrole

    @hasrole('super-duper-admin')
        <x-nav.responsive-link href="{{ route('admin.logs') }}" :active="request()->routeIs('admin.logs')">
            {{ __('components.navigation.logs') }}
        </x-nav.responsive-link>
    @endhasrole

    <hr>
    <x-nav.responsive-link href="{{ route('home') }}">
        {{ __('components.navigation.back_to_home') }}
    </x-nav.responsive-link>
</nav>