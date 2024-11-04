<x-app-layout class="w-full max-w-4xl m-auto">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $page->title }}
        </h2>
    </x-slot>

    <div class="py-5 px-3 space-y-6">
        @foreach ($page->blocks as $block)
            <x-ux.white-block class="p-6 space-y-6">
                <div class="space-y-3">
                    <h4 class="font-bold">
                        {{ $block->title }}
                    </h4>
                    <x-markdown class="html space-y-6">
                        {{ $block->content }}
                    </x-markdown>
                </div>
            </x-ux.white-block>
        @endforeach
        
    </div>
</x-app-layout>