@props(['categories' => null, 'category' => null])

<div class="w-full grid grid-cols-2 sm:grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-2 order-3">
    @foreach ($categories as $child)
        <a 
            href="{{ route('announcement.search', ['category' => $child->slug]) }}"
            @class([
                'p-2 bg-white rounded-2xl text-sm border hover:border-indigo-700 items-center ', 
                'border-indigo-700' => $category?->slug === $child->slug
            ])
        >
            <div class="flex items-center space-x-2 m-auto h-full">
                <div class="w-12 h-12 min-h-12 min-w-12 items-center justify-center flex">
                    <img src="{{ $child->getFirstMediaUrl('categories', 'thumb') }}" alt="" class="float-right w-12 min-w-12">
                </div>

                <div class="grid">
                    <span class="w-full font-semibold">
                        {{ $child->name }}
                    </span>
                </div>
            </div>
        </a>
    @endforeach
</div>