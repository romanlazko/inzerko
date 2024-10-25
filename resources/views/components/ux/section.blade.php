<section {{ $attributes->merge(['class' => 'w-full space-y-4 lg:space-y-0']) }}>
    @if (isset($header))
        <div class="w-full max-w-7xl m-auto py-4 px-3 border-b lg:border-none bg-white md:bg-inherit">
            {{ $header }}
        </div>
    @endif
    <div class="w-full max-w-7xl m-auto px-3">
        {{ $slot }}
    </div>
</section>