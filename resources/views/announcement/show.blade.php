{{ header("Cache-Control: private, max-age=0, must-revalidate"); }}

<x-app-layout :meta="$announcement->getDynamicSEOData()">
    <x-slot name="header">
        <div class="w-full space-y-6">
            <x-nav.breadcrumbs :category="$announcement->categories->filter(function ($category) {
                return $category->children->isEmpty();
            })->first()"/>
        </div>
    </x-slot>
    
    <div class="space-y-6 lg:py-12">
        <div class="grid w-full grid-cols-1 lg:grid-cols-5 xl:grid-cols-3 lg:gap-6 max-w-7xl m-auto px-0 lg:px-3">
            @if ($announcement->media?->isNotEmpty())
                <div class="order-1 col-span-2 lg:col-span-3 xl:col-span-2 lg:rounded-2xl overflow-hidden">
                    <x-ux.slider
                        :medias="$announcement->getMedia('announcements')"
                        :fallbackMedia="$announcement->getFirstMediaUrl('announcements', 'thumb')"
                        height="500px"
                        :withFullscreen="true"
                        :withButtons="true"
                        :withDots="true"
                    />
                </div>
            @endif

            <div 
                @class([
                    'w-full h-min space-y-6 xl:sticky py-6 lg:py-0 top-6 col-span-1 lg:col-span-2 xl:col-span-1 z-20',
                    'order-2' => $announcement->media?->isNotEmpty(),
                    'order-1 lg:order-3' => $announcement->media?->isEmpty(),
                ])
            >
                <div class="space-y-6 w-full">
                    <div class="space-y-4 px-3 ">
                        <div class="h-full flex items-center justify-between">
                            <span class="text-sm text-gray-500">
                                {{ $announcement->geo?->name }} - {{ $announcement->created_at->diffForHumans() }}
                            </span>
                            <div class="flex items-center space-x-2">
                                <livewire:actions.send-report :announcement_id="$announcement->id"/>
                                <livewire:actions.like-dislike :announcement="$announcement"/>
                            </div>
                        </div>
        
                        <div class="w-full space-y-4">
                            <h1 class="font-bold text-2xl">
                                {{ $announcement->title }}
                            </h1>
                            <p class="font-medium text-xl">
                                {{ $announcement->price }}
                            </p>
                        </div>
                    </div>
                    
                    <hr class="mx-3">

                    <div class="space-y-4 w-full p-1">
                        <a href="{{ route('profile.show', $announcement?->user) }}" target="_blank" class="cursor-pointer w-full inline-block hover:bg-gray-100 hover:ring-1 hover:ring-gray-300 rounded-2xl p-2">
                            <x-user.card :user="$announcement?->user"/>
                        </a>

                        <div class="px-2">
                            <livewire:actions.send-message :announcement_id="$announcement->id" :user_id="$announcement->user?->id"/>
                        </div>
                        
                    </div>
                </div>
            </div>

            <div 
                @class([
                    'w-full overflow-hidden order-2 col-span-2 lg:col-span-3 xl:col-span-2 px-3',
                    'order-3' => $announcement->media?->isNotEmpty(),
                    'order-2' => $announcement->media?->isEmpty(),
                ])
            >
                <hr class="pb-6 lg:hidden lg:pb-0">
                <div class="space-y-12">
                    <div class="space-y-4">
                        <h2 class="font-bold text-2xl">
                            {{ __('Description') }}
                        </h2>
                        <div class="space-y-3 html">
                            {!! $announcement->description !!}
                        </div>
                    </div>

                    @if ($announcement->features->where('attribute.is_feature')->isNotEmpty())
                        <div class="space-y-4">
                            <h2 class="font-bold text-2xl">
                                {{ __('Features') }}
                            </h2>

                            <div class="space-y-3 sm:space-y-0 sm:gap-6 grid grid-cols-1 ">
                                @foreach ($announcement->features->where('attribute.is_feature')->sortBy('attribute.showSection.order_number')->groupBy('attribute.showSection.name') as $section_name => $feature_section)
                                    <div class="space-y-2">
                                        <h4 class="font-bold text-sm">
                                            {{ $section_name }}:
                                        </h4>
                                        
                                        <ul class="w-full list-outside space-y-1">
                                            @foreach ($feature_section->sortBy('attribute.show_layout.order_number') as $feature)
                                                <li class="w-full grid grid-cols-2 md:flex space-x-2 text-sm ">
                                                    <span class="text-gray-500 inline-block">
                                                        {{ $feature->label }}:
                                                    </span>
                                                    <span class="">
                                                        {{ $feature->value }}
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        @if ($similar_announcements)
            <x-announcement.list class="bg-white" :announcements="$similar_announcements" :cols="5">
                <x-slot name="header">
                    <h2 class="text-xl lg:text-3xl font-bold">
                        {{ __('announcement.similar') }}
                    </h2>
                </x-slot>
            </x-announcement.list>
        @endif
    </div>
</x-app-layout>

