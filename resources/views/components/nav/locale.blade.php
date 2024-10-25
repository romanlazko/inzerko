<form action="{{ route('locale') }}" method="POST" {{ $attributes->merge(['class' => 'block']) }}>
    @csrf
    <select name="locale" onchange="this.form.submit();" class=" border-none py-0 pl-0 shadow-none focus:ring-0 bg-transparent text-sm text-white">
        @foreach (config('translate.languages') as $key => $locale)
            <option @selected(app()->getLocale() == $key) value="{{ $key }}">{{ strtoupper($key) }}</option>
        @endforeach
    </select>
</form>