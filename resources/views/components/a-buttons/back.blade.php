<a
    {{ 
        $attributes->merge([
            'class' => 'cursor-pointer font-semibold text-xl text-gray-800 leading-tight text-center whitespace-nowrap items-center cursor-pointer grid hover:bg-gray-200 aspect-square w-8 rounded-full content-center'
        ]) 
    }}
>
    {{ __('←') }}
</a>