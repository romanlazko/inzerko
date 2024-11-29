<x-app-layout class="w-full max-w-7xl m-auto">
    <div class="w-full max-w-7xl m-auto lg:flex">
        <div class="px-3 py-5 w-full lg:w-1/3 ">
            <x-ux.white-block class="p-3 py-6 space-y-6 h-min">
                <x-user.card :user="$user"/>
                <hr>
                <x-user.contacts :user="$user"/>
            </x-ux.white-block>
        </div>
        
        <div class="w-full lg:w-2/3">
            @foreach ($announcements->groupBy('category.name') as $category_name => $category_announcements)
                <x-announcement.list :announcements="$category_announcements->sortByDesc('created_at')" :cols="3">
                    <x-slot name="header">
                        <h2 class="text-xl lg:text-3xl font-bold">
                            {{ $category_name }}
                        </h2>
                    </x-slot>
                </x-announcement.list>
            @endforeach
            
            <div class="px-3">
                {{ $announcements?->onEachSide(1)->links() }}
            </div>
        </div>
    </div>
</x-app-layout>