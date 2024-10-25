<nav {{ $attributes->merge(['class' => 'space-y-6 p-4 lg:p-0']) }}>
    <x-nav.responsive-link :href="route('profile.edit')" :active="request()->routeIs('profile.edit')">
        <x-heroicon-s-heart class="size-5"/>
        <span>{{ __("components.navigation.profile") }}</span>
    </x-nav.responsive-link>

    {{-- <a href="{{ route('profile.announcement.index') }}" @class(['flex text-gray-700 hover:text-indigo-600 whitespace-nowrap items-center space-x-3', 'text-indigo-600' => request()->routeIs('profile.announcement.index')])>
        <i class="fa-solid fa-clipboard-list w-4"></i>
        <span class="font-medium text-sm">{{ __("My announcements") }}</span>
    </a> --}}

    <x-nav.responsive-link :href="route('profile.wishlist')" :active="request()->routeIs('profile.wishlist')">
        <x-heroicon-s-heart class="size-5"/>
        <span>{{ __("components.navigation.wishlist") }}</span>
    </x-nav.responsive-link>

    <x-nav.responsive-link @click="$dispatch('open-chat')">
        <div class="relative leading-3 size-5">
            @if ($unreadMessagesCount > 0)
                <p class="absolute text-[8px] text-white w-3 h-3 rounded-full bg-red-500 top-3 text-center content-center items-center">{{ $unreadMessagesCount }}</p>
            @endif
            <x-heroicon-s-chat-bubble-left-right class="size-5"/>
        </div>
        <span>{{ __("components.navigation.messages") }}</span>
    </x-nav.responsive-link>

    {{ $slot }}

    @hasrole('super-duper-admin')
        <hr>
        <x-nav.responsive-link :href="route('admin.announcement.moderation')" :active="request()->routeIs('admin.announcements.inde')">
            <x-heroicon-o-adjustments-horizontal class="size-5"/>
            <span>{{ __("components.navigation.admin") }}</span>
        </x-nav.responsive-link>
    @endhasrole
</nav>