<div {{ $attributes->merge(['class' => 'bg-white rounded-2xl w-full overflow-auto border ' . ($attributes->get('class') ?? 'p-4')]) }}>
    {{ $slot }}
</div>