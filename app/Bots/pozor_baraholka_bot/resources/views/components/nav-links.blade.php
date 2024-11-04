<x-nav-link :href="route('pozor_baraholka_bot.announcement.index')" :active="request()->routeIs('pozor_baraholka_bot.announcement.index')">
    {{ __('Announcements') }}
</x-nav-link>
<x-nav-link :href="route('pozor_baraholka_bot.chat.index')" :active="request()->routeIs('chat')">
    {{ __('Chats') }}
</x-nav-link>
<x-nav-link :href="route('pozor_baraholka_bot.advertisement.index')" :active="request()->routeIs('advertisement')">
    {{ __('Advertisement') }}
</x-nav-link>
