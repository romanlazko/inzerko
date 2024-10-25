@props([
    'medias' => [], 
    'height' => null,
    'withThumbnails' => false,
    'withFullscreen' => false,
    'withButtons' => false,
    'withDots' => false,
])

<div 
    x-data="{
        photos_length: {{ $medias->count() }},
        activeIndex: 0,
        touchStartX: 0,
        touchStartY: 0,
        touchEndX: 0,
        touchEndY: 0,
        touchMoveX: 0,
        touchMoveY: 0,
        touchStarted: false,
        horizontalThreshold: 70,
        verticalThreshold: 60,
        ratio: 0,
        fullscreen: false,
        nextSlide() {
            this.activeIndex = (this.activeIndex + 1) % this.photos_length;
            this.centerThumbnail(this.activeIndex);
        },
        prevSlide() {
            this.activeIndex = (this.activeIndex - 1 + this.photos_length) % this.photos_length;
            this.centerThumbnail(this.activeIndex);
        },
        setActiveSlide(index) {
            this.activeIndex = index;
            this.centerThumbnail(this.activeIndex);
        },
        handleTouchStart(event) {
            this.touchStartX = event.touches[0].screenX;
            this.touchStartY = event.touches[0].screenY;
            this.touchStarted = true;
        },
        handleTouchMove(event) {
            if (!this.touchStarted) return;

            this.touchMoveX = event.touches[0].screenX;
            this.touchMoveY = event.touches[0].screenY;

            const deltaX = Math.abs(this.touchMoveX - this.touchStartX);
            const deltaY = Math.abs(this.touchMoveY - this.touchStartY);
            const ratio = deltaX / deltaY;

            if (ratio > 1.5) {
                event.preventDefault();
            } else {
                this.touchStarted = false;
            }
        },
        handleTouchEnd(event) {
            if (!this.touchStarted) return;

            this.touchEndX = event.changedTouches[0].screenX;
            this.touchEndY = event.changedTouches[0].screenY;
            this.touchStarted = false;

            const deltaX = Math.abs(this.touchEndX - this.touchStartX);
            const deltaY = Math.abs(this.touchEndY - this.touchStartY);
            const ratio = deltaX / deltaY;

            if (ratio > 1.5 || deltaX > this.horizontalThreshold) {
                if (this.touchEndX - this.touchStartX < 0) {
                    this.nextSlide();
                } else if (this.touchEndX - this.touchStartX > 0) {
                    this.prevSlide();
                }
            }
        },
        centerThumbnail(index) {
            const thumbnailContainer = this.$refs.thumbnailContainer;

            if (thumbnailContainer) {
                const thumbnailWidth = thumbnailContainer.children[index].offsetWidth;
                const containerWidth = thumbnailContainer.offsetWidth;
                const scrollAmount = thumbnailContainer.children[index].offsetLeft - containerWidth / 2 + thumbnailWidth / 2;
                thumbnailContainer.scrollTo({
                    left: scrollAmount,
                    behavior: 'smooth'
                });
            }
        }
    }"
    class="w-full overflow-hidden flex flex-col sm:px-0 transition duration-200 ease-in-out"
    :class="{
        'fixed inset-0 overflow-hidden z-50 bg-white h-[100dvh]': fullscreen === true,
    }"
    x-on:close.stop="fullscreen = false"
    x-on:keydown.escape.window="fullscreen = false"
    :style="!fullscreen ? { height: '{{ $height }}' } : null"
>
    <div 
        {{ $attributes->merge(['class' => "relative overflow-hidden flex-1 bg-black aspect-square"]) }} 
        @touchstart="handleTouchStart($event)" 
        @touchend="handleTouchEnd($event)" 
        @touchmove="handleTouchMove($event)" 
    >
        <div class="w-full m-auto items-center z-30 h-full overflow-hidden">
            <div class="flex transition-transform duration-500 h-full items-center" :style="'transform: translateX(-' + activeIndex * 100 + '%)'">
                @foreach ($medias as $index => $media)
                    <div class="w-full h-full flex-shrink-0" >
                        <div class="h-full">
                            <img 
                                class="m-auto"
                                :class="{
                                    'object-contain h-full': fullscreen === true,
                                    'object-cover w-full h-full':fullscreen === false, 
                                }"
                                src="{{ $media->responsiveImages('responsive-images')->getPlaceholderSvg() ?? $media->getUrl() }}"
                                srcset="{{ $media->getSrcset('responsive-images') ?? $media->getUrl() }}"
                                alt="Slide {{ $index }}"
                                sizes="11px"
                                onload="window.requestAnimationFrame(function(){if(!(size=getBoundingClientRect().width))return;onload=null;sizes=Math.ceil(size/window.innerWidth*100)+'vw';});"
                                loading="lazy"
                            >
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($withButtons)
                <div class="absolute inset-0 flex justify-between items-center z-10">
                    <button @click.prevent="prevSlide()" @dblclick.prevent class="h-full p-1 lg:px-3">
                        <div class="bg-gray-800 bg-opacity-50 lg:hover:bg-opacity-90 text-white p-2 rounded-full opacity-30 hover:opacity-100 lg:opacity-100 ">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </div>
                    </button>
                    <button @click.prevent="nextSlide()" @dblclick.prevent class="h-full p-1 lg:px-3">
                        <div class="bg-gray-800 bg-opacity-50 lg:hover:bg-opacity-90 text-white p-2 rounded-full opacity-30 hover:opacity-100 lg:opacity-100 ">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </button>
                </div>
            @endif
            
            @if ($withFullscreen)
                <div class="absolute top-3 right-3 z-20">
                    <button 
                        class="flex text-gray-100 text-2xl hover:text-white opacity-70 hover:opacity-100 p-1 w-10 h-10 min-h-10 min-w-10 rounded-full bg-gray-800 items-center justify-center bg-opacity-50 hover:bg-opacity-100" 
                        @click="fullscreen = ! fullscreen"
                    >
                        <x-heroicon-o-arrows-pointing-in x-show="fullscreen" class="size-6"/>
                        <x-heroicon-o-arrows-pointing-out x-show="! fullscreen" class="size-6"/>
                    </button>
                </div>
            @endif

            @if ($withDots)
                <div class="w-full flex justify-center absolute bottom-2 z-20">
                    <div @class(['overflow-x-auto flex m-auto scrollbar-hide rounded-full space-x-2 p-1 hover:opacity-100', 'bg-gray-500 opacity-70' => $medias->count() > 1])x-ref="thumbnailContainer">
                        @for ($index = 0; $index < $medias->count(); $index++)
                            <button
                                @click.prevent="setActiveSlide({{ $index }});" 
                                :class="{
                                    'bg-white opacity-100': activeIndex === {{ $index }}, 
                                    'bg-gray-300 hover:bg-gray-100': activeIndex !== {{ $index }}
                                }" 
                                class="w-2 h-2 min-h-2 min-w-2 transition duration-200 ease-in-out cursor-pointer rounded-full"
                            >
                            </button>
                        @endfor
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if ($withThumbnails)
        <div class="w-full flex justify-center">
            <div class="overflow-x-auto min-h-20 flex mt-4 m-auto scrollbar-hide" x-ref="thumbnailContainer">
                @foreach ($medias as $index => $media)
                    <img 
                        src="{{ $media->getUrl('thumb') ?? null }}"
                        @click="setActiveSlide({{ $index }});" 
                        :class="{
                            'ring-2 ring-indigo-700': activeIndex === {{ $index }}, 
                            'ring-1': activeIndex !== {{ $index }}
                        }" 
                        class="w-16 h-16 min-h-16 min-w-16 object-cover m-1 cursor-pointer rounded-lg ring-gray-200 hover:ring-indigo-400"
                    >
                @endforeach
            </div>
        </div>
    @endif
</div>