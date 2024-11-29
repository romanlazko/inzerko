<nav class="space-y-3 px-2">
    @hasanyrole(['admin'])
        <x-nav.responsive-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
            {{ __('Dashboard') }}
        </x-nav.responsive-link>
    @endhasanyrole

    {{-- ANNOUNCEMENTS --}}
        @can('view', 'announcement')
            <x-nav.dropdown :active="request()->routeIs('admin.announcement.*')">
                <x-slot name="trigger">
                    {{ __('components.navigation.announcements') }}
                </x-slot>

                @can('moderate', 'announcement')
                    <x-nav.responsive-link href="{{ route('admin.announcement.moderation') }}" :active="request()->routeIs('admin.announcement.moderation')">
                        {{ __('components.navigation.moderation') }}
                    </x-nav.responsive-link>
                @endcan

                @can('manage', 'announcement')
                    <x-nav.responsive-link href="{{ route('admin.announcement.announcements') }}" :active="request()->routeIs('admin.announcement.announcements') || request()->routeIs('admin.announcement.archive')">
                        {{ __('components.navigation.all_announcements') }}
                    </x-nav.responsive-link>
                @endcan

                @can('manage', 'announcement')
                    <x-nav.responsive-link href="{{ route('admin.announcement.reports') }}" :active="request()->routeIs('admin.announcement.reports')">
                        {{ __('components.navigation.reports') }}
                    </x-nav.responsive-link>
                @endcan
            </x-nav.dropdown>
        @endcan
    {{-- ANNOUNCEMENTS --}}
    
    {{-- TELEGRAM --}}
        @can('view', 'telegram')
            <x-nav.dropdown :active="request()->routeIs('admin.telegram.*')">
                <x-slot name="trigger">
                    {{ __('components.navigation.telegram') }}
                </x-slot>

                <x-nav.responsive-link href="{{ route('admin.telegram.bots') }}" :active="request()->routeIs('admin.telegram.bots')">
                    {{ __('components.navigation.bots') }}
                </x-nav.responsive-link>

                @foreach (\App\Models\TelegramBot::select('first_name', 'id')->get() as $bot)
                    <x-nav.dropdown :active="request()->routeIs('admin.telegram.chats', $bot->id) || request()->routeIs('admin.telegram.logs', $bot->id) || request()->routeIs('admin.telegram.channels', $bot->id) AND request()->telegram_bot->id == $bot->id">
                        <x-slot name="trigger">
                            {{ $bot->first_name }}
                        </x-slot>

                        <x-nav.responsive-link href="{{ route('admin.telegram.chats', $bot->id) }}" :active="request()->routeIs('admin.telegram.chats', $bot->id) AND request()->telegram_bot->id == $bot->id">
                            {{ __('components.navigation.chats') }}
                        </x-nav.responsive-link>

                        <x-nav.responsive-link href="{{ route('admin.telegram.channels', $bot->id) }}" :active="request()->routeIs('admin.telegram.channels', $bot->id) AND request()->telegram_bot->id == $bot->id">
                            {{ __('components.navigation.channels') }}
                        </x-nav.responsive-link>

                        @hasanyrole(['super-duper-admin'])
                            <x-nav.responsive-link href="{{ route('admin.telegram.logs', $bot->id) }}" :active="request()->routeIs('admin.telegram.logs', $bot->id) AND request()->telegram_bot->id == $bot->id">
                                {{ __('components.navigation.logs') }}
                            </x-nav.responsive-link>
                        @endhasanyrole
                    </x-nav.dropdown>
                @endforeach
            </x-nav.dropdown>
        @endcan
    {{-- TELEGRAM --}}

    {{-- USERS --}}
        @can('view', 'user')
            <x-nav.dropdown :active="request()->routeIs('admin.users.*')">
                <x-slot name="trigger">
                    {{ __('components.navigation.users') }}
                </x-slot>

                <x-nav.responsive-link href="{{ route('admin.users.users') }}" :active="request()->routeIs('admin.users.users')">
                    {{ __('components.navigation.all_users') }}
                </x-nav.responsive-link>

                @hasanyrole(['super-duper-admin'])
                    <x-nav.responsive-link href="{{ route('admin.users.roles') }}" :active="request()->routeIs('admin.users.roles')">
                        {{ __('Roles') }}
                    </x-nav.responsive-link>

                    <x-nav.responsive-link href="{{ route('admin.users.permissions') }}" :active="request()->routeIs('admin.users.permissions')">
                        {{ __('Permissions') }}
                    </x-nav.responsive-link>
                @endhasanyrole
            </x-nav.dropdown>
        @endcan
    {{-- USERS --}}

    {{-- SETTINGS --}}
        @can('view', 'setting')
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
                <x-nav.responsive-link href="{{ route('admin.setting.report_options') }}" :active="request()->routeIs('admin.setting.report_options')">
                    {{ __('components.navigation.report_options') }}
                </x-nav.responsive-link>
                <x-nav.responsive-link href="{{ route('admin.setting.html_layouts') }}" :active="request()->routeIs('admin.setting.html_layouts')">
                    {{ __('components.navigation.html_layouts') }}
                </x-nav.responsive-link>
            </x-nav.dropdown>
        @endcan
    {{-- SETTINGS --}}

    {{-- PAGES --}}
        @can('view', 'page')
            <x-nav.responsive-link href="{{ route('admin.pages') }}" :active="request()->routeIs('admin.pages')">
                {{ __('components.navigation.pages') }}
            </x-nav.responsive-link>
        @endcan
    {{-- PAGES --}}

    {{-- SYSTEM_LOGS --}}
        @can('view', 'system_log')
            <x-nav.dropdown :active="request()->routeIs('admin.log.*')">
                <x-slot name="trigger">
                    {{ __('components.navigation.logs') }}
                </x-slot>

                
                <x-nav.responsive-link href="{{ route('admin.log.telescope') }}" :active="request()->routeIs('admin.log.telescope')">
                    Telescope
                </x-nav.responsive-link>

                <x-nav.responsive-link href="{{ route('admin.log.log-viewer') }}" :active="request()->routeIs('admin.log.log-viewer')">
                    LogViewer
                </x-nav.responsive-link>
            </x-nav.dropdown>
        @endcan
    {{-- SYSTEM_LOGS --}}

    <hr>
    <x-nav.responsive-link href="{{ route('home') }}">
        {{ __('components.navigation.back_to_home') }}
    </x-nav.responsive-link>
</nav>