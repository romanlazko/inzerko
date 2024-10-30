<nav class="h-12 grid grid-cols-5 w-full bg-white border-b border-gray-100 items-center">
    <x-nav.footer-link href="{{ route('announcement.index') }}" :active="request()->routeIs('announcement.index')" :text="__('components.navigation.store')">
        <x-heroicon-s-shopping-bag class="size-5 m-auto"/>
    </x-nav.footer-link>

    <x-nav.footer-link href="{{ route('profile.wishlist') }}" :active="request()->routeIs('profile.wishlist')" :text="__('components.navigation.wishlist')">
        <x-heroicon-s-heart class="size-5 m-auto"/>
    </x-nav.footer-link>

    <x-nav.footer-link href="{{ route('announcement.create') }}" :active="request()->routeIs('announcement.create')" :text="__('components.navigation.create_new')">
        <x-heroicon-c-plus-circle class="size-5 m-auto"/>
    </x-nav.footer-link>

    <x-nav.footer-link @click="$dispatch('open-chat')" :text="__('components.navigation.messages')">
        <div class="relative leading-3 size-5 m-auto">
            <x-unread-messages/>
            <x-heroicon-s-chat-bubble-left-right class="size-5"/>
        </div>
    </x-nav.footer-link>

    <x-nav.footer-link href="{{ route('profile.edit') }}" :active="request()->routeIs('profile.edit')" :text="__('components.navigation.profile')">
        <x-heroicon-s-user class="size-5 m-auto"/>
    </x-nav.footer-link>
</nav>
