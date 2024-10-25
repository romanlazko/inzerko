@props(['icon' => true])

<li class="flex items-center gap-1">
    @if ($icon)
        <x-heroicon-o-chevron-right class="size-4"/>
    @endif
    {{ $slot }}
</li>