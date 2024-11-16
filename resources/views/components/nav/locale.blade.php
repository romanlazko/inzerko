{{-- <form action="{{ route('locale') }}" method="POST" {{ $attributes->merge(['class' => 'block']) }}>
    @csrf
    <select name="locale" onchange="this.form.submit();" class=" border-none py-0 pl-0 shadow-none focus:ring-0 bg-transparent text-sm text-white" aria-label="Locale">
        @foreach (config('translate.languages') as $key => $locale)
            <option @selected(app()->getLocale() == $key) value="{{ $key }}">{{ strtoupper($key) }}</option>
        @endforeach
    </select>
</form> --}}

{{-- <x-nav.dropdown align="right" width="" >
    <x-slot name="trigger">
        <p class="text-gray-600 hover:text-indigo-600 hover:underline">{{ app()->getLocale() }}</p>
    </x-slot>
    <x-slot name="content">
        <x-nav.dropdown-link href="{{ route('locale', 'ru') }}">
            RU
        </x-nav.dropdown-link>
        <x-nav.dropdown-link href="{{ route('locale', 'cz') }}">
            CZ
        </x-nav.dropdown-link>
        <x-nav.dropdown-link href="{{ route('locale', 'sk') }}">
            SK
        </x-nav.dropdown-link>
        <x-nav.dropdown-link href="{{ route('locale', 'en') }}">
            EN
        </x-nav.dropdown-link>
    </x-slot>
</x-nav.dropdown> --}}
<div x-data="{ dropdownOpen: false }" {{ $attributes->merge(['class' => 'relative text-sm']) }}>
    <button @click="dropdownOpen = ! dropdownOpen" class="flex text-white hover:text-indigo-700" title="Locale">
        {{ strtoupper(app()->getLocale()) }}
    </button>

    <div x-cloak :class="dropdownOpen ? 'block' : 'hidden'" @click="dropdownOpen = false" class="fixed inset-0 z-[35] transition-opacity"></div>

    <div x-cloak x-show="dropdownOpen" class="absolute right-0 z-40 mt-2 p-0 overflow-hidden bg-white rounded-md shadow-xl border">
        @foreach (config('translate.languages') as $key => $locale)
            <x-nav.dropdown-link href="{{ route('locale', ['locale' => $key]) }}">
                {{ strtoupper($key) }}
            </x-nav.dropdown-link>
        @endforeach
    </div>
</div>