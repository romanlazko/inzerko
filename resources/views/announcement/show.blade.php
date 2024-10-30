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

            <div class="w-full overflow-hidden order-3 col-span-2 lg:col-span-3 xl:col-span-2 px-3">
                <hr class="pb-6 lg:hidden lg:pb-0">
                <div class="space-y-6">
                    <x-markdown class="html">
                        {{ $announcement->description }}
                    </x-markdown>

                    @if ($announcement->features->where('attribute.is_feature')->isNotEmpty())
                        <hr>
                        
                        <div class="space-y-3 sm:space-y-0 sm:gap-6 grid grid-cols-1 ">
                            @foreach ($announcement->features->where('attribute.is_feature')->sortBy('attribute.showSection.order_number')->groupBy('attribute.showSection.name') as $section_name => $feature_section)
                                <div class="space-y-2">
                                    <h4 class="font-bold text-sm">
                                        {{ $section_name }}:
                                    </h4>
                                    
                                    <ul class="w-full list-inside list-disc">
                                        @foreach ($feature_section->sortBy('attribute.show_layout.order_number') as $feature)
                                            <li class="w-full grid grid-cols-2 space-x-2 text-sm">
                                                <span class="text-gray-500 inline-block">
                                                    • {{ $feature->label }}:
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
                    @endif
                </div>
            </div>

            <div class="w-full h-min space-y-6 py-6 xl:sticky top-0 order-2 col-span-1 lg:col-span-2 xl:col-span-1 px-3 z-20">
                <div class="space-y-6 w-full">
                    <div class="space-y-4">
                        <div class="h-full flex items-center justify-between">
                            <span class="text-sm text-gray-500">
                                {{ $announcement->geo?->name }} - {{ $announcement->created_at->diffForHumans() }}
                            </span>
                            
                            <livewire:actions.like-dislike :announcement="$announcement"/>
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
                    
                    <hr>

                    <div class="space-y-4 w-full">
                        <x-user.card :user="$announcement->user"/>
                        
                        <livewire:actions.send-message :announcement_id="$announcement->id" :user_id="$announcement->user->id"/>
                    </div>
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

