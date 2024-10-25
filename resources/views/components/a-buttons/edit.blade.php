<a {{ $attributes->merge(['class' => 'cursor-pointer inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-2xl font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150 disabled:opacity-25']) }}>
    <i class="fa-solid fa-pen-to-square mr-1"></i>
    <p>
        {{ $slot }}
    </p>
</a>
