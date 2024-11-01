<div class="grid grid-cols-1 gap-2 w-max">
    @foreach ($collection as $item)
        <x-filament::badge
            :color="$item->color"
        >
            {{ $item->title }}
        </x-filament::badge>
    @endforeach
</div>