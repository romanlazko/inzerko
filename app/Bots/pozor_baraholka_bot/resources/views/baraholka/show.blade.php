<x-telegram::layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __($announcement->title ?? Str::limit($announcement->caption, 50)) }}
            </h2>
            <div class="flex-col">
                <a href="" class="text-2xl">
                    <i class="fa-regular fa-xmark"></i>
                </a>
            </div>
        </div>
    </x-slot>
    <x-slot name="main_full">
        <div class="w-full sm:flex sm:space-x-2">
            <div class="w-full sm:w-2/3">
                <x-telegram::white-block>
                    <div class="relative w-full lg:max-h-[calc(100vh-10rem)] ">
                        <div class="w-full h-full relative justify-center ">
                            <div class="absolute inset-0 h-full overflow-hidden">
                                <img class="main-image h-full w-full object-cover blur-xl" src="{{$announcement->photos->first()?->url}}" alt="Large Image">
                            </div>
                            <div class="relative max-h-[600px] lg:max-h-[calc(100vh-14rem)] square">
                                <img class="main-image h-full m-auto object-contain" src="{{$announcement->photos->first()?->url}}" alt="Large Image">
                            </div>
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
                        </div>

                        <div class="flex items-center text-center justify-center overflow-auto my-3">
                            <div class="flex m-auto">
                                @foreach($announcement->photos as $photo)
                                    <img class="thumbnail-image w-10 h-10 object-cover cursor-pointer mx-3 rounded-lg float-left" src="{{$photo?->url}}" alt="Thumbnail Image 1">
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="w-full space-y-6">
                        <div class="flex justify-between w-full space-x-2">
                            <x-telegram::a-buttons.secondary href="{{ route('pozor_baraholka_bot.announcement.index') }}" class="w-full items-center justify-center text-center" >
                                <i class="fa-solid fa-share"></i> 
                                <p class="hidden sm:block md:hidden lg:block px-2">
                                    {{ __("baraholka::create.main.preview.share") }}
                                </p>
                            </x-telegram::a-buttons.secondary>
                            <x-telegram::a-buttons.secondary href="{{ route('pozor_baraholka_bot.announcement.index') }}" class="w-full items-center justify-center text-center" >
                                <i class="fa-solid fa-bookmark"></i> 
                                <p class="hidden sm:block md:hidden lg:block px-2">
                                    {{ __("baraholka::create.main.preview.save") }}
                                </p>
                            </x-telegram::a-buttons.secondary>
                            <x-telegram::a-buttons.secondary href="{{ route('pozor_baraholka_bot.announcement.index') }}" class="w-full items-center justify-center text-center" >
                                <i class="fa-solid fa-ellipsis"></i>
                            </x-telegram::a-buttons.secondary>
                        </div>
                        <div class="w-full space-y-3">
                            @if ($announcement->title)
                                <h1 class="text-2xl font-bold text-gray-700">{{ __($announcement->title) }}</h1>
                            @endif
                            @if ($announcement->cost)
                                <p class="text-lg text-gray-800">
                                    {{ $announcement->cost }} {{ $announcement->currency ?? "CZK" }}
                                </p>
                            @endif
                            <p class="text-sm text-gray-400">
                                {{ $announcement->created_at->diffForHumans() }}
                            </p>
                        </div>

                        <hr>

                        <div class="space-y-3 max-w-md">
                            @if ($announcement->condition)
                                <div class="flex justify-between items-center">
                                    <p class="text-sm font-bold">
                                        {{ __("baraholka::show.main.description.condition") }}
                                    </p>
                                    <span class="text-sm condition-preview">
                                        {{ __('baraholka::condition.'.$announcement->condition) }}
                                    </span>
                                </div>
                            @endif

                            @if ($announcement->brand)
                                <div class="flex justify-between items-center">
                                    <p class="text-sm font-bold">
                                        {{ __("baraholka::main.description.brand") }}
                                    </p>
                                    <span class="text-sm brand-preview">
                                        {{ $announcement->brand }}
                                    </span>
                                </div>
                            @endif
                        </div>

                        <div class="w-full">
                            <x-telegram::badge>
                                <i class="fa-solid {{ $announcement->category()->first()?->icon_name }}"></i>
                                {{ $announcement->category()->first()?->trans_name() ?? $announcement->category }}
                            </x-telegram::badge>
                            <x-telegram::badge :trigger="!is_null($announcement->subcategory)">
                                <i class="fa-solid {{ $announcement->subcategory?->icon_name }}"></i>
                                {{ $announcement->subcategory?->trans_name() }}
                            </x-telegram::badge>
                        </div>
                        
                        <hr>

                        <div class="space-y-3">
                            <p class="text-sm font-bold">
                                {{ __("baraholka::show.main.description.caption") }}
                            </p>
                            <p class="text-gray-600">
                                {!! nl2br(e($announcement->caption)) !!}
                            </p>
                        </div>

                        <hr>

                        <div class="w-full">
                            @if ($announcement->chat)
                                <div class="w-full items-center space-x-0 space-y-2 sm:justify-between sm:flex sm:space-x-3">
                                    <div class="flex w-full">
                                        <div class="flex-col items-center my-auto">
                                            <img src="{{ $announcement->chat->photo ?? null }}" alt="Avatar" class="mr-4 w-12 h-12 min-w-[48px] rounded-full">
                                        </div>
                                        <div>
                                            <p class="text-md font-medium text-gray-900">
                                                {{ $announcement->chat->first_name ?? null }} {{ $announcement->chat->last_name ?? null }}
                                            </p>
                                            <p class="text-sm text-gray-500">
                                                Create account {{ $announcement->chat->created_at->diffForHumans() ?? null }}
                                            </p>
                                        </div>
                                    </div>
                                    
                                    <x-telegram::a-buttons.primary class="w-full text-center justify-center">
                                        <i class="fa-solid fa-comment-dots"></i>
                                        <p 
                                            class="p-2" 
                                            x-data=""
                                            x-on:click.prevent="$dispatch('open-modal', 'send_message')"
                                        >{{ __("baraholka::create.main.preview.send_message") }}</p>
                                    </x-telegram::a-buttons.primary>
                                </div>
                            @endif
                        </div>
                    </div>
                </x-telegram::white-block>
            </div>
            <div class="w-full sm:w-1/3">
            
                
                <div class="sticky top-0">
                    <x-telegram::white-block>
                        <div class="">
                            {{-- <p class="text-blue-500">#реклама</p> --}}
                            <img class="w-full sm:rounded-lg" src="{{ asset('/storage/advertisement/centr1/1/square-banner.jpg') }}" alt="">
                            <p class="text-blue-500">#реклама</p>
                        </div>
                    </x-telegram::white-block>
                </div>
            
            </div>

        </div>

        <x-modal name="send_message" focusable>
            <form method="post" action="{{ route('baraholka.announcement.send-message', $announcement) }}" class="p-6">
                @csrf

                @guest
                    <div class="mt-6">
                        <x-input-label for="email" value="{{ __('Email') }}" />
        
                        <x-text-input
                            id="email"
                            name="email"
                            type="text"
                            class="mt-1 block w-full"
                            placeholder="{{ __('Email') }}"
                        />
        
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>
                @endguest
                
    
                <div class="mt-6">
                    <x-input-label for="message" value="{{ __('Message') }}"/>
    
                    <x-telegram::form.textarea
                        id="message"
                        name="message"
                        class="mt-1 block w-full"
                        placeholder="{{ __('Message') }}"
                    />
    
                    <x-input-error :messages="$errors->get('message')" class="mt-2" />
                </div>
    
                <div class="mt-6 flex justify-end">
                    <x-secondary-button x-on:click="$dispatch('close')">
                        {{ __('Cancel') }}
                    </x-secondary-button>
    
                    <x-primary-button class="ml-3">
                        {{ __('Send message') }}
                    </x-primary-button>
                </div>
            </form>
        </x-modal>
    </x-slot>
    @section('script')
    <script>
        $(document).ready(function() {
            setSquareHeight();
            // Определяем индекс текущей выбранной фотографии
            let currentIndex = 0;

            const imageCount = $('.thumbnail-image').length;

            // Обработчик клика по стрелке "Вперед"
            $('#next').on('click', function() {
                currentIndex = (currentIndex + 1) % imageCount;
                updateImages();
            });

            // Обработчик клика по стрелке "Назад"
            $('#prev').on('click', function() {
                currentIndex = (currentIndex - 1 + imageCount) % imageCount;
                updateImages();
            });

            // Обработчик наведения на маленькую фотографию
            $('.thumbnail-image').on('mouseover', function() {
                currentIndex = $(this).index();
                updateImages();
            });

            // Обновляет отображение фотографий
            function updateImages() {
                // Удаляем классы активности у всех маленьких фотографий
                $('.thumbnail-image').removeClass('border-2 border-gray-500');

                // Добавляем класс активности текущей маленькой фотографии
                $('.thumbnail-image').eq(currentIndex).addClass('border-2 border-gray-500');

                // Заменяем большую фотографию на текущую
                const largeImagePath = $('.thumbnail-image').eq(currentIndex).attr('src');
                $('.main-image').attr('src', largeImagePath);
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