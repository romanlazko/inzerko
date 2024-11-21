@props(['messages'])

<ul {{ $attributes->merge(['class' => 'text-xs font-normal text-red-600 space-y-1 h-min']) }}>
    @if ($messages)
        @foreach ((array) $messages as $message)
            @if (is_array($message))
                @foreach ((array) $message as $item)
                    <li>{{ $item }}</li>
                @endforeach
            @else
                <li>{{ $message }}</li>
            @endif
        @endforeach
    @endif
</ul>
