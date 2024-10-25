@php
    $title = $category?->translated_name ?? __('announcement.all');
@endphp

<x-body-layout :title="$title" class="w-full max-w-7xl m-auto">
    @if ($category)
        <x-slot name="meta">
            {!! seo()->for($category) !!}
        </x-slot>
    @endif

    <x-slot name="sidebar">
        <livewire:components.announcement.filters :filters="$request->filters ?? null" :category="$category"/>
    </x-slot>

    <x-slot name="search">
        <div class="w-full py-1 md:py-2 p-2">
            <x-search :category="$category" :search="$request->search ?? null" :location="$request->location ?? null" :filterButton="true"/>
        </div>
    </x-slot>
    
    <x-slot name="header">
        <div class="w-full space-y-6">
            <x-nav.breadcrumbs :category="$category"/>
            
            <x-categories :categories="$categories" :category="$category"/>
        </div>
    </x-slot>

    <x-announcement.list :announcements="$announcements" :cols="3" :paginator="true">
        <x-slot name="header">
            <div class="w-full md:flex justify-between items-center space-y-2 md:space-y-0">
                <h2 class="text-xl lg:text-3xl font-bold">
                    {{ $category?->name ?? __('announcement.all') }} <span class="text-gray-500">{{ $paginator->total() }}</span>
                </h2>
                <form action="{{ route('announcement.search', ['category' => $category?->slug]) }}">
                    <div class="w-full flex items-center space-x-2 ">
                        <label for="sort" class="text-gray-500 text-sm whitespace-nowrap">
                            {{ __('announcement.sort_by') }}
                        </label>
                        <select name="sort" class="border-none py-0 pl-0 shadow-none focus:ring-0 font-bold bg-transparent text-sm text-gray-900 text-ellipsis w-full" onchange="this.form.submit()">
                            @foreach ($sortings as $sorting)
                                <option value="{{ $sorting->id }}" @selected($request->sort == $sorting->id)>
                                    {{ $sorting->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>
        </x-slot>
    </x-announcement.list>
</x-body-layout>