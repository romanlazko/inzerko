<x-telegram::layout>
    <x-slot name="sidebar">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-900">
                {{ __('Baraholka') }}
            </h2>
            <span class="md:hidden text-sm font-bold text-blue-600" onclick="$('#filter').toggle('fast')">
                {{ __('Filters:') }}
            </span>
        </div>
        
        <form action="{{ route('baraholka.announcement.index') }}" class="hidden md:block " id="filter">
            <p class="mt-1 text-sm text-gray-600">
                {{ __('Find everesing') }}
            </p>

            <hr class="my-3">

            <div class="w-full space-y-6">
                <x-telegram::form.input id="search" name="search" type="search" class="block w-full" :value="old('search', request()->search)" autocomplete="search" placeholder="{{ __('Search:') }}"/>
            </div>

            <hr class="my-3">

            <div class="w-full space-y-6 ">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Filters:') }}
                </h2>
                <div class="border border-gray-300 rounded-md shadow-sm ">
                    <label for="sort" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        <div>{{ __("Sort:") }}</div>
                    </label>
                    <select name="sort" id="sort" class="w-full border-none p-0 appearance-none bg-transparent px-3 text-lg focus:border-none">
                        <option value="cost/asc" @selected(old('sort', request()->sort) == 'cost/asc')>Lower cost</option>
                        <option value="cost/desc" @selected(old('sort', request()->sort) == 'cost/desc')>Higher cost</option>
                        <option value="created_at/desc" @selected(old('sort', request()->sort) == 'created_at/desc')>Newest</option>
                        <option value="created_at/asc" @selected(old('sort', request()->sort) == 'created_at/asc')>Older</option>
                    </select>
                    <x-telegram::form.error class="mt-2" :messages="$errors->get('type')" />
                </div>

                <div class="border border-gray-300 rounded-md shadow-sm ">
                    <label for="type" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        <div>{{ __("Type:") }}</div>
                    </label>
                    <select name="type" id="sort" class="w-full border-none p-0 appearance-none bg-transparent px-3 text-lg focus:border-none">
                        @foreach (App\Bots\pozor_baraholka_bot\Models\BaraholkaAnnouncement::distinct('type')->pluck('type') as $type)
                            <option value="{{ $type }}" @selected(old('type', request()->type) == $type)>{{ ucfirst($type) }}</option>
                        @endforeach
                    </select>
                    <x-telegram::form.error class="mt-2" :messages="$errors->get('type')" />
                </div>

                <div class="border border-gray-300 rounded-md shadow-sm ">
                    <label for="city" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        <div>{{ __("City:") }}</div>
                    </label>
                    <select name="city" id="city" class="w-full border-none p-0 appearance-none bg-transparent px-3 text-lg focus:border-none">
                        @foreach (App\Bots\pozor_baraholka_bot\Models\BaraholkaAnnouncement::distinct('city')->pluck('city') as $city)
                            <option value="{{ $city }}" @selected(old('city', request()->city) == $city)>{{ ucfirst($city) }}</option>
                        @endforeach
                    </select>
                    <x-telegram::form.error class="mt-2" :messages="$errors->get('type')" />
                </div>

                <div>
                    <label class="inline-flex items-center border border-transparent text-sm font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        <div>{{ __("Cost:") }}</div>
                    </label>
                    <div class="flex space-x-2">
                        <x-telegram::form.input name="minCost" class="border border-gray-300 w-full mt-1 block p-1" :value="old('minCost', request()->minCost)" placeholder="{{ __('min') }}"/>
                        
                        <x-telegram::form.input name="maxCost" class="border border-gray-300 w-full mt-1 block p-1" :value="old('maxCost', request()->maxCost)" placeholder="{{ __('max') }}"/>
                    </div>
                    <x-telegram::form.error class="mt-2" :messages="$errors->get('type')" />
                </div>
                <div>
                    <x-telegram::buttons.primary>
                        {{ __('Apply filters') }}
                    </x-telegram::buttons.primary>
                </div>
            </div>

            <hr class="my-3">

            <div class="w-full space-y-3">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Category:') }}
                </h2>
                <x-telegram::badge class="mx-1 my-0.5" color="blue" :active="request()->category == ''">
                    <label for="category-all">
                        <input type="radio" name="category" id="category-all" class="hidden category" value="" @checked(request()->category == "")>
                        {{ __("Show all") }}
                    </label>
                </x-telegram::badge>
                @foreach ($categories as $category)
                    <x-telegram::badge class="mx-1 my-0.5" color="blue" :active="request()->category == $category->id">
                        <label for="category-{{ $category->id }}">
                            <input type="radio" name="category" id="category-{{ $category->id }}" class="hidden category" value="{{ $category->id }}" @checked(request()->category == $category->id)>
                            <i class="fa-solid {{ $category->icon_name }}"></i>
                            {{ __($category->trans_name()) }}
                        </label>
                    </x-telegram::badge>
                @endforeach
            </div>

            @if (request()->category)
                <hr class="my-3">
                <div class="w-full space-y-3">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ __('Subcategory:') }}
                    </h2>
                    
                    <x-telegram::badge class="mx-1 my-0.5" color="blue" :active="request()->subcategory == ''">
                        <label for="subcategory-all">
                            <input type="radio" name="subcategory" id="subcategory-all" class="hidden subcategory" value="" @checked(request()->subcategory == "") onchange="$(this).closest('form').submit();">
                            {{ __("Show all") }}
                        </label>
                    </x-telegram::badge>
                    @foreach ($subcategories as $subcategory)
                        <x-telegram::badge class="mx-1 my-0.5" color="blue" :active="request()->subcategory == $subcategory->id">
                            <label for="subcategory-{{ $subcategory->id }}">
                                <input type="radio" name="subcategory" id="subcategory-{{ $subcategory->id }}" class="hidden subcategory" value="{{ $subcategory->id }}" @checked(request()->subcategory == $subcategory->id) onchange="$(this).closest('form').submit();">
                                <i class="fa-solid {{ $subcategory->icon_name }}"></i>
                                {{ __($subcategory->trans_name()) }}
                            </label>
                        </x-telegram::badge>
                    @endforeach
                </div>
            @endif
        </form>
    </x-slot>

    <x-slot name="main_full">
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 ">
            @foreach ($announcements as $announcement)
                <a href="{{ route('baraholka.announcement.show', $announcement) }}" class="m-1 md:m-3 mb-6 announcement motion-safe:hover:scale-[1.01] transition-all duration-250 rounded-lg">
                    <div class="rounded-lg overflow-hidden relative">
                        <img src="{{ $announcement->main_photo?->url }}" alt="Product Image" class="w-full object-center object-cover main-img square">
                    </div>
                    <div>
                        @if ($announcement->cost)
                            <h3 class="font-bold text-lg text-gray-800">
                                {{ $announcement->cost }} {{ $announcement->currency ?? 'CZK' }}
                            </h3>
                        @endif
                        <p class="text-sm">{{ $announcement->title ?? Str::limit($announcement->caption, 50, '...')}}</p>
                        <p class="text-xs text-gray-500">{{ $announcement->created_at->diffForHumans() }}</p>
                    </div>
                </a>
            @endforeach
        </div>
        <div class="mx-3">
            {{ $announcements->withQueryString()->links() }}
        </div>
    </x-slot>
    @section('script')
    <script>
        $(document).ready(function() {
            // Функция для установки высоты блока равной его ширине
            function setSquareHeight() {
                var squareWidth = $('.square').width();
                $('.square').height(squareWidth);
            }
        
            // Инициализация высоты при загрузке страницы
            setSquareHeight();
        
            // Обновление высоты при изменении размеров окна
            $(window).resize(function() {
                setSquareHeight();
            });

            $('.category').change(function() {
                
                $('.subcategory').val("");
                $(this).closest('form').submit();
            });
        });
    </script>
    @endsection
</x-telegram::layout>