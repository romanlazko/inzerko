<nav {{ $attributes->merge(['class' => 'space-y-6 p-4 lg:p-0']) }}>
    <x-nav.responsive-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
        <x-heroicon-s-user class="size-5"/>
        <span>{{ __("components.navigation.profile") }}</span>
    </x-nav.responsive-link>

    <x-nav.responsive-link :href="route('profile.announcement.index')" :active="request()->routeIs('profile.announcement.index')">
        <x-heroicon-c-queue-list class="size-5"/>
        <span>{{ __("components.navigation.my_announcements") }}</span>
    </x-nav.responsive-link>

    <x-nav.responsive-link :href="route('profile.announcement.wishlist')" :active="request()->routeIs('profile.announcement.wishlist')">
        <x-heroicon-s-heart class="size-5"/>
        <span>{{ __("components.navigation.wishlist") }}</span>
    </x-nav.responsive-link>

    <x-nav.responsive-link @click="$dispatch('open-chat')" tag="button">
        <div class="relative leading-3 size-5">
            <x-unread-messages/>
            <x-heroicon-s-chat-bubble-left-right class="size-5"/>
        </div>
        <span>{{ __("components.navigation.messages") }}</span>
    </x-nav.responsive-link>

    <x-nav.responsive-link :href="route('profile.notification')" :active="request()->routeIs('profile.notification.edit')">
        <x-heroicon-m-bell-alert class="size-5"/>
        <span>{{ __("components.navigation.notifications") }}</span>
    </x-nav.responsive-link>

    <x-nav.responsive-link :href="route('profile.security')" :active="request()->routeIs('profile.security.edit')">
        <x-heroicon-c-shield-check class="size-5"/>
        <span>{{ __("components.navigation.security") }}</span>
    </x-nav.responsive-link>

    {{ $slot }}

    @hasanyrole(['admin'])
        <hr>
        <x-nav.responsive-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
            <x-heroicon-o-adjustments-horizontal class="size-5"/>
            <span>{{ __("components.navigation.admin") }}</span>
        </x-nav.responsive-link>
    @endhasanyrole
</nav>