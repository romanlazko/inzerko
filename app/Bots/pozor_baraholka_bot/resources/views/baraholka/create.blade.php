
<x-telegram::layout>
    <x-slot name="sidebar">
        <h2 class="text-2xl font-bold text-gray-900">
            {{ __('baraholka::create.header.title') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('baraholka::create.main.form.description') }}
        </p>
        <form method="post" action="{{ route('pozor_baraholka_bot.announcement.store') }}" class="" enctype="multipart/form-data">
            @csrf
            @method('post')

            <div class="relative space-y-6">
                <div class="w-full">
                    <div class="thumbnailImageContainer grid grid-cols-3 w-full">
                    </div>
                </div>
    
                <div>
                    <label for="photos" class="border border-gray-500 rounded-md shadow-sm inline-flex items-center px-3 text-sm leading-4 font-medium text-gray-500 bg-white hover:text-gray-700 hover:border-2 focus:outline-none transition ease-in-out duration-150 h-16 text-center w-full justify-center space-x-1">
                        <i class="text-2xl fa-solid fa-plus"></i>
                        <p>
                            {{ __('baraholka::create.main.form.photos') }}
                        </p>
                    </label>
                    <input id="photos" type="file" name="photos[]" multiple="multiple" accept="image/*" max="9" class=" hidden">
                    <x-telegram::form.error class="mt-2" :messages="$errors->get('photos')" />
                </div>
    
                <div class="border border-gray-300 rounded-md shadow-sm ">
                    <label for="type" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        <div>{{ __("baraholka::create.main.form.type") }}</div>
                    </label>
                    <select name="type" id="type" class="w-full border-none p-0 appearance-none bg-transparent px-3 text-lg focus:border-none">
                        <option value="sell">{{ __('Sell') }}</option>
                        <option value="buy">{{ __('Buy') }}</option>
                    </select>
                    <x-telegram::form.error class="mt-2" :messages="$errors->get('type')" />
                </div>
    
                <div class="border border-gray-300 rounded-md shadow-sm ">
                    <label for="city" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        <div>{{ __("baraholka::create.main.form.city") }}</div>
                    </label>
                    <select name="city" id="city" class="w-full border-none p-0 appearance-none bg-transparent px-3 text-lg focus:border-none">
                        <option value="brno">{{ __('Brno') }}</option>
                        <option value="prague">{{ __('Prague') }}</option>
                    </select>
                    <x-telegram::form.error class="mt-2" :messages="$errors->get('city')" />
                </div>
    
                <div>
                    <x-telegram::form.input id="title" name="title" type="text" class="mt-1 block w-full" :value="old('title')" required autocomplete="title" placeholder="{{ __('baraholka::create.main.form.title') }}"/>
                    <x-telegram::form.error class="mt-2" :messages="$errors->get('title')" />
                </div>
    
                <div>
                    <x-telegram::form.textarea id="caption" name="caption" class="mt-1 block w-full" :value="old('caption')" required autocomplete="caption" placeholder="{{ __('baraholka::create.main.form.caption') }}"/>
                    <x-telegram::form.error class="mt-2" :messages="$errors->get('caption')" />
                </div>
    
                <div>
                    <x-telegram::form.input id="brand" name="brand" type="text" class="mt-1 block w-full" :value="old('brand')" autocomplete="brand" placeholder="{{ __('baraholka::create.main.form.brand') }}"/>
                    <x-telegram::form.error class="mt-2" :messages="$errors->get('brand')" />
                </div>
        
                <div>
                    <x-telegram::form.input id="cost" name="cost" type="number" class="mt-1 block w-full" :value="old('cost')" required autocomplete="cost" placeholder="{{ __('baraholka::create.main.form.cost') }}"/>
                    <x-telegram::form.error class="mt-2" :messages="$errors->get('cost')" />
                </div>
    
                <div>
                    <x-telegram::form.input id="tags" name="tags" type="text" class="mt-1 block w-full" :value="old('tags')" autocomplete="tags" placeholder="{{ __('baraholka::create.main.form.tags') }}"/>
                    <x-telegram::form.error class="mt-2" :messages="$errors->get('tags')" />
                </div>
    
                <div class="border border-gray-300 rounded-md shadow-sm ">
                    <label for="category" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        <div>{{ __("baraholka::create.main.form.category") }}</div>
                    </label>
                    <select name="category_id" id="category" class="w-full border-none p-0 appearance-none bg-transparent px-3 text-lg focus:border-none">
                        <option value=""></option>
                        @forelse ($categories as $category)
                            <option value="{{ $category->id }}" name="{{ __($category->trans_name()) }}">{{ __($category->trans_name()) }}</option>
                        @empty
                        @endforelse
                    </select>
                    <x-telegram::form.error class="mt-2" :messages="$errors->get('category')" />
                </div>
    
                <div class="border border-gray-300 rounded-md shadow-sm ">
                    <label for="subcategory_id" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        <div>{{ __("baraholka::create.main.form.subcategory") }}</div>
                    </label>
                    
                    @forelse ($categories as $category)
                        <select name="subcategory" class="subcategory w-full border-none p-0 appearance-none bg-transparent px-3 text-lg focus:border-none hidden category-{{ $category->id }}">
                            <option value=""></option>
                            @forelse ($category->subcategories as $subcategory)
                                <option value="{{ $subcategory->id }}" name="{{ __($subcategory->trans_name($category->name)) }}">{{ __($subcategory->trans_name($category->name)) }}</option>
                            @empty
                            @endforelse
                        </select>
                    @empty
                    @endforelse
                    <x-telegram::form.error class="mt-2" :messages="$errors->get('subcategory')" />
                </div>
    
                <div class="border border-gray-300 rounded-md shadow-sm ">
                    <label for="condition" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                        <div>{{ __("baraholka::create.main.form.condition") }}</div>
                    </label>
                    <select name="condition" id="condition" class="w-full border-none p-0 bg-transparent px-3 text-lg focus:border-none">
                        <option value=""></option>
                        <option value="new_product" name="{{ __('New product') }}">{{ __('New product') }}</option>
                        <option value="used_good" name="{{ __('Used in good condition') }}">{{ __('Used in good condition') }}</option>
                        <option value="used_fair" name="{{ __('Used in fair condition') }}">{{ __('Used in fair condition') }}</option>
                        <option value="used_bad" name="{{ __('Used in bad condition') }}">{{ __('Used in bad condition') }}</option>
                    </select>
                    <x-telegram::form.error class="mt-2" :messages="$errors->get('condition')" />
                </div>
    
                <div class=" inline-block">
                    <x-telegram::buttons.primary>{{ __('baraholka::create.main.form.save') }}</x-telegram::buttons.primary>
                    <x-telegram::buttons.secondary class="md:hidden" onclick="$('#preview').toggle(); setSquareHeight()">{{ __('preview') }}</x-telegram::buttons.secondary>
                </div>
            </div>
        </form>
    </x-slot>
    <x-slot name="main_full">
        <div id="preview" class="hidden md:block">
            <x-telegram::white-block class="max-w-7xl sm:p-6 m-auto">
                <div class="space-y-4">
                    <h2 class="text-2xl font-bold text-gray-900 px-3 pt-3">
                        {{ __('baraholka::create.main.preview.title') }} 
                    </h2>
                    <div class="border grid grid-cols-1 justify-between lg:flex lg:space-y-0 lg:rounded-r-xl " >
                        <div class="flex-col w-full lg:w-1/2 2xl:w-2/3">
                            <div class="relative w-full ">
                                <div class="w-full square">
                                    <div class="absolute top-[50%] translate-y-[-50%] flex justify-between w-full p-3">
                                        <button class="relative hover:bg-gray-700 text-white bg-slate-400 p-2 rounded-full cursor-pointer opacity-80 " id="prev">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M15 19l-7-7 7-7"></path>
                                            </svg>
                                        </button>
                                        <button class="relative hover:bg-gray-700 text-white bg-slate-400 p-2 rounded-full cursor-pointer opacity-80" id="next">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </button>
                                    </div>
                                    <div class="justify-center w-full h-full bg-gray-200">
                                        <div class="previewMainImageContainer w-full h-full items-center justify-center hidden">
                                            
                                        </div>
                                        <div class="previewMainImagePreview w-full h-full items-center justify-center flex">
                                            <div class="text-center">
                                                <p class="text-xl font-bold text-gray-700 p-3">{{ __("baraholka::create.main.preview.photo_preview") }}</p>
                                                <p class="text-sm text-gray-600 p-3">{{ __("baraholka::create.main.preview.here_you_can_see") }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center text-center justify-center overflow-auto">
                                    <div class="flex m-auto previewThumbnailImageContainer">
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                        <div class="flex-col w-full lg:w-1/2 2xl:w-1/3 space-y-6 p-3 border-t lg:border-none">
                            <div class="w-full space-y-3">
                                <h1 class="text-2xl font-bold text-gray-500 title-preview">{{ __("baraholka::create.main.preview.title_preview") }}</h1>
                                <p class="text-lg text-gray-500 cost-preview">
                                    {{ __("baraholka::create.main.preview.cost") }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ __("baraholka::create.main.preview.seconds_ago") }}
                                </p>
                            </div>
        
                            <div class="grid w-full grid-cols-1 space-y-2">
                                <div class="w-full">
                                    <x-telegram::a-buttons.primary href="{{ route('pozor_baraholka_bot.announcement.index') }}" class="w-full items-center justify-center text-center " >
                                        <i class="fa-solid fa-comment-dots"></i> 
                                        <p class="p-2">{{ __("baraholka::create.main.preview.send_message") }}</p>
                                    </x-telegram::a-buttons.primary>
                                </div>
                                <div class="flex justify-between w-full space-x-2">
                                    <x-telegram::a-buttons.secondary href="{{ route('pozor_baraholka_bot.announcement.index') }}" class="w-full items-center justify-center text-center" >
                                        <i class="fa-solid fa-share"></i> 
                                        <p class="hidden lg:block px-2">
                                            {{ __("baraholka::create.main.preview.share") }}
                                        </p>
                                    </x-telegram::a-buttons.secondary>
                                    <x-telegram::a-buttons.secondary href="{{ route('pozor_baraholka_bot.announcement.index') }}" class="w-full items-center justify-center text-center" >
                                        <i class="fa-solid fa-bookmark"></i> 
                                        <p class="hidden lg:block px-2">
                                            {{ __("baraholka::create.main.preview.save") }}
                                        </p>
                                    </x-telegram::a-buttons.secondary>
                                    <x-telegram::a-buttons.secondary href="{{ route('pozor_baraholka_bot.announcement.index') }}" class="w-full items-center justify-center text-center" >
                                        <i class="fa-solid fa-ellipsis"></i>
                                    </x-telegram::a-buttons.secondary>
                                </div>
                            </div>
        
                            <hr>

                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <p class="text-sm font-bold">
                                        {{ __("baraholka::create.main.preview.condition") }}
                                    </p>
                                    <span class="text-sm condition-preview text-gray-500">
                                        {{ __("baraholka::create.main.preview.condition_will_be_here") }}
                                    </span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <p class="text-sm font-bold">
                                        {{ __("baraholka::create.main.preview.brand") }}
                                    </p>
                                    <span class="text-sm brand-preview text-gray-500">
                                        {{ __("baraholka::create.main.preview.brand_will_be_here") }}
                                    </span>
                                </div>
                            </div>
        
                            
                            <div class="w-full">
                                <x-telegram::badge class="category-preview">
                                    {{ __("category") }}
                                </x-telegram::badge>
                                <x-telegram::badge class="subcategory-preview">
                                    {{ __("subcategory") }}
                                </x-telegram::badge>
                            </div>
        
                            <hr>
        
                            <div class="space-y-4">
                                <p class="text-sm font-bold">
                                    {{ __("baraholka::create.main.preview.caption") }}
                                </p>
                                <p class="text-gray-600 caption-preview">
                                    {{ __("baraholka::create.main.preview.caption_will_be_here") }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </x-telegram::white-block>
        </div>
    </x-slot>
    @section('script')
        <script>
            $(document).ready(function() {
                setSquareHeight();

                $('#title').change(function(){
                    var titleText = $(this).val();

                    if (titleText) {
                        $('.title-preview').text(titleText).toggleClass('text-black', 'text-gray-500');
                    } else {
                        $('.title-preview').text('{{ __("baraholka::create.main.preview.title_preview") }}').toggleClass('text-black', 'text-gray-500');
                    }
                });

                $('#caption').change(function(){
                    var captionText = $(this).val();

                    if (captionText) {
                        $('.caption-preview').text(captionText).toggleClass('text-black', 'text-gray-500');
                    } else {
                        $('.caption-preview').text('{{ __("baraholka::create.main.preview.caption_will_be_here") }}').toggleClass('text-black', 'text-gray-500');
                    }
                });

                $('#brand').change(function(){
                    var brandText = $(this).val();

                    if (brandText) {
                        $('.brand-preview').text(brandText).toggleClass('text-black', 'text-gray-500');
                    } else {
                        $('.brand-preview').text('{{ __("baraholka::create.main.preview.brand_will_be_here") }}').toggleClass('text-black', 'text-gray-500');
                    }
                });

                $('#cost').change(function(){
                    var costText = $(this).val();

                    if (costText !== "") {
                        $('.cost-preview').text(costText).toggleClass('text-black', 'text-gray-500');
                    } else {
                        $('.cost-preview').text('{{ __("baraholka::create.main.preview.cost") }}').toggleClass('text-black', 'text-gray-500');
                    }
                });

                $('#category').change(function(){
                    var category = $(this);

                    var categoryText = category.find(":selected").attr('name');

                    if (categoryText) {
                        $('.category-preview').html(categoryText);
                    } else {
                        $('.category-preview').text('{{ __("category") }}');
                    }
                    
                    $('.subcategory').hide();
                    $('.subcategory').val("");
                    $('.subcategory-preview').text('{{ __("subcategory") }}');
                    $('.category-'+category.val()).show();
                });

                $('.subcategory').change(function(){
                    var subcategoryText = $(this).find(":selected").attr('name');

                    if (subcategoryText) {
                        $('.subcategory-preview').html(subcategoryText);
                    } else {
                        $('.subcategory-preview').text('{{ __("subcategory") }}');
                    }
                });

                $('#condition').change(function(){
                    var conditionText = $(this).find(":selected").attr('name');

                    if (conditionText) {
                        $('.condition-preview').html(conditionText).toggleClass('text-black', 'text-gray-500');
                    } else {
                        $('.condition-preview').text('{{ __("baraholka::create.main.preview.condition_will_be_here") }}').toggleClass('text-black', 'text-gray-500');
                    }
                });
            });

            $(document).ready(function() {
                let currentIndex = 0;

                // Обработчик клика по стрелке "Вперед"
                $('#next').on('click', function() {
                    imageCount = $('.preview-thumbnail-image').length;
                    if (imageCount > 0) {
                        currentIndex = (currentIndex + 1) % imageCount;
                        updateImages();
                    }
                });

                // Обработчик клика по стрелке "Назад"
                $('#prev').on('click', function() {
                    imageCount = $('.preview-thumbnail-image').length;
                    if (imageCount > 0) {
                        currentIndex = (currentIndex - 1 + imageCount) % imageCount;
                        updateImages();
                    }
                });

                // Обработчик наведения на маленькую фотографию
                $('.preview-thumbnail-image').on('mouseover', function() {
                    currentIndex = $(this).index();
                    updateImages();
                });

                function updateImages() {
                    // Удаляем классы активности у всех маленьких фотографий
                    $('.preview-thumbnail-image').removeClass('border-2 border-gray-500');

                    // Добавляем класс активности текущей маленькой фотографии
                    $('.preview-thumbnail-image').eq(currentIndex).addClass('border-2 border-gray-500');

                    // Заменяем большую фотографию на текущую
                    const largeImagePath = $('.preview-thumbnail-image').eq(currentIndex).attr('src');
                    $('.main-image').attr('src', largeImagePath);
                }
            });
                
            $(document).ready(function() {
                $('#photos').change(function(e) {
                    addToPreview();
                });

                function addToPreview() {
                    var input = document.getElementById('photos');
                    var files = input.files;

                    if (files.length > 0) {
                        $('.previewMainImagePreview').hide();
                        $('.previewMainImageContainer').show();
                    }
                    else {
                        $('.previewMainImagePreview').show();
                        $('.previewMainImageContainer').hide();
                    }

                    $('.thumbnailImageContainer').empty();
                    $('.previewThumbnailImageContainer').empty();
                    $('.previewMainImageContainer').empty();

                    for (var i = 0; i < files.length; i++) {
                        var file = files[i];
                        var reader = new FileReader();

                        reader.onload = (function(i) {
                            return function(e) {
                                addToThumbnailImageContainer(e.target.result, i);

                                if (i === 0) {
                                    addToPreviewMainImageContainer(e.target.result);
                                }
                                addToPreviewThumbnailImageContainer(e.target.result);
                            };
                        })(i);

                        reader.readAsDataURL(file);
                    }

                    setSquareHeight();
                }

                function addToPreviewMainImageContainer(imgSrc) {
                    $('.previewMainImageContainer').append(
                        $('<img>').attr('src', imgSrc).addClass('main-image h-full m-auto object-scale-down')
                    );
                }

                function addToPreviewThumbnailImageContainer(imgSrc) {
                    var thumbnailImage = $('<img>')
                        .attr('src', imgSrc)
                        .addClass('preview-thumbnail-image w-10 h-10 object-cover cursor-pointer rounded-lg m-3');

                    $('.previewThumbnailImageContainer').append(
                        thumbnailImage
                    );
                }

                function addToThumbnailImageContainer(imgSrc, index) {
                    var thumbnailDiv = $('<div>')
                        .addClass('thumbnail-div m-3');

                    var deleteButton = $('<button>')
                        .addClass('absolute text-gray-200 text-2xl hover:text-gray-500 leading-none p-1')
                        .html('<i class="fa-solid fa-circle-xmark bg-gray-500 rounded-full"></i>')
                        .click(function() {
                            removeFromInput(index);
                            addToPreview();
                        });

                    var thumbnailImage = $('<img>')
                        .attr('src', imgSrc)
                        .addClass('thumbnail-image w-full object-cover cursor-pointer rounded-lg square hover:border');

                    $('.thumbnailImageContainer').append(
                        thumbnailDiv
                        .append(deleteButton)
                        .append(thumbnailImage)
                    );

                    setSquareHeight();
                }
                function removeFromInput(index) {
                    var input = document.getElementById('photos');
                    var files = input.files;
                    var dt = new DataTransfer();

                    for (var i = 0; i < files.length; i++) {
                        if (i !== index) {
                            dt.items.add(files[i]);
                        }
                    }

                    input.files = dt.files;
                }
            });

            function setSquareHeight() {
                $('.square').each(function(){
                    var squareWidth = $(this).width();
                    $(this).height(squareWidth);
                });
            }
            
            $(window).resize(function() {
                setSquareHeight();
            });
        </script>
    @endsection
</x-telegram::layout>

