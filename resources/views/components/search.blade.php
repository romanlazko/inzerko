@props(['category' => null, 'search' => null, 'filterButton' => false, 'location' => null, 'filtersCount' => null])

<div {{ $attributes->merge(['class' => "md:flex items-center md:space-x-2 space-y-2 md:space-y-0 w-full max-w-2xl m-auto"]) }}>
    <form action="{{ route('announcement.search', ['category' => $category?->slug]) }}" class="rounded-full flex border-2 border-indigo-600 bg-white p-1 md:p-2 w-full items-center space-x-2">
        <div class="w-full bg-white rounded-full items-center flex ">
            <input type="search" class="w-full border-none rounded-full h-full focus:ring-0 border border-indigo-600" placeholder="{{ __('components.search') }}" name="search" value="{{ $search }}"
            onchange="this.form.submit()">
        </div>
    
        <button @click="sidebarOpen = true" type="button"
            @class(['text-gray-900 hover:text-indigo-700 text-xl lg:hidden p-1 md:p-2 relative', 'hidden' => !$filterButton])
            title="Filters"
        >
            <x-heroicon-o-adjustments-horizontal class="size-6"/>
        </button>
        
        <button class="p-2 rounded-full aspect-square min-w-10 min-h-10 max-w-10 max-h-10 hover:bg-indigo-500 text-xl bg-indigo-600 text-center items-center" title="Search">
            <x-heroicon-o-magnifying-glass class="text-white"/>
        </button>
    </form>
    
    <livewire:actions.location-form :location="$location" :category="$category"/>
</div>
