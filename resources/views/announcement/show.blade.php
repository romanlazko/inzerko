{{ header("Cache-Control: private, max-age=0, must-revalidate") }}

<x-app-layout :meta="$announcement->getDynamicSEOData()">
    <x-slot name="header">
        <div class="w-full space-y-6">
            <x-nav.breadcrumbs :category="$announcement->categories->filter(function ($category) {
                return $category->children->isEmpty();
            })->first()"/>
        </div>
    </x-slot>
    
    <div class="space-y-6 lg:py-12">
        <div class="grid w-full grid-cols-1 lg:grid-cols-6 lg:gap-6 max-w-7xl m-auto px-0 lg:px-3">
            @if ($announcement->media?->isNotEmpty())
                <div
                    @class([
                        'overflow-hidden h-min lg:rounded-2xl',
                        'col-span-1 lg:col-span-3'
                    ])
                >
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
                    'w-full h-min space-y-6 xl:sticky py-6 lg:py-0 top-6 z-20',
                    'col-span-1 lg:row-span-2 lg:col-span-2',
                    'order-2' => $announcement->media?->isNotEmpty(),
                    'order-1 lg:order-3' => $announcement->media?->isEmpty(),
                ])
            >
                <div class="space-y-6 w-full">
                    <div class="space-y-4 px-3">
                        <div class="w-full space-y-4">
                            <h1 class="font-bold text-2xl">
                                {{ $announcement->title }}
                            </h1>
                            <p class="font-medium text-xl">
                                {{ $announcement->price }}
                            </p>
                        </div>
                        <div class="h-full flex items-center justify-between">
                            <span class="text-sm text-gray-500">
                                {{ $announcement->geo?->name }} - {{ $announcement->created_at->diffForHumans() }}
                            </span>
                            <div class="flex items-center space-x-2">
                                <livewire:actions.send-report :announcement_id="$announcement->id"/>
                                <livewire:actions.like-dislike :announcement_id="$announcement->id"/>
                            </div>
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
                    'w-full overflow-hidden px-3',
                    'col-span-1 lg:col-span-3',
                    'order-4' => $announcement->media?->isNotEmpty(),
                    'order-2' => $announcement->media?->isEmpty(),
                ])
            >
                <hr class="pb-6 lg:hidden lg:pb-0">

                @if ($announcement->features->isNotEmpty())
                    <div class="space-y-12">
                        @foreach ($announcement->features->where('attribute.is_feature')->sortBy('attribute.showSection.order_number')->groupBy('attribute.showSection.name') as $section_name => $features_by_section)
                            <div class="space-y-2">
                                <h2 class="font-bold text-2xl">
                                    {{ $section_name }}: 
                                </h2>
                                
                                <ul class="w-full list-outside space-y-1">
                                    @foreach ($features_by_section->sortBy('attribute.show_layout.order_number') as $feature)
                                        @if ($feature->value)
                                            <li class="w-full text-base items-center">
                                                @if ($feature->attribute?->show_layout['has_label'] ?? false)
                                                    <span class="text-gray-500">
                                                        {{ $feature->label }}:
                                                    </span>
                                                @endif
                                                <span class="space-y-3 html">
                                                    {!! str_replace("******", "<a class='text-blue-500 underline' href='".route('profile.show', $announcement?->user)."'>".__('components.user.contacts')."</a>", $feature->value) !!}
                                                </span>
                                            </li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>

