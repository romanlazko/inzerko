@php
    use Filament\Support\Enums\Alignment;
    use Filament\Support\Enums\MaxWidth;
@endphp

@props([
    'alignment' => Alignment::Start,
    'ariaLabelledby' => "Chat-modal-heading-{$id}",
    'autofocus' => \Filament\Support\View\Components\Modal::$isAutofocused,
    'closeButton' => \Filament\Support\View\Components\Modal::$hasCloseButton,
    'closeByClickingAway' => \Filament\Support\View\Components\Modal::$isClosedByClickingAway,
    'closeByEscaping' => \Filament\Support\View\Components\Modal::$isClosedByEscaping,
    'closeEventName' => 'close-modal',
    'description' => null,
    'displayClasses' => 'inline-block',
    'extraModalWindowAttributeBag' => null,
    'footer' => null,
    'footerActions' => [],
    'footerActionsAlignment' => Alignment::Start,
    'header' => null,
    'heading' => null,
    'icon' => null,
    'iconAlias' => null,
    'iconColor' => 'primary',
    'id' => null,
    'openEventName' => 'open-modal',
    'slideOver' => false,
    'stickyFooter' => false,
    'stickyHeader' => false,
    'trigger' => null,
    'visible' => true,
    'width' => 'sm',
])

@php
    if (! $width instanceof MaxWidth) {
        $width = filled($width) ? (MaxWidth::tryFrom($width) ?? $width) : null;
    }

    $closeEventHandler = filled($id) ? '$dispatch(' . \Illuminate\Support\Js::from($closeEventName) . ', { id: ' . \Illuminate\Support\Js::from($id) . ' })' : 'close()';
@endphp

<div
    @if ($ariaLabelledby)
        aria-labelledby="{{ $ariaLabelledby }}"
    @elseif ($heading)
        aria-labelledby="{{ "{$id}.heading" }}"
    @endif
    aria-modal="true"
    role="dialog"
    x-data="{
        isOpen: false,

        livewire: null,

        close: function () {
            this.isOpen = false

            this.$refs.modalContainer.dispatchEvent(
                new CustomEvent('modal-closed', { id: '{{ $id }}' }),
            )
        },

        open: function () {
            this.isOpen = true

            this.$refs.modalContainer.dispatchEvent(
                new CustomEvent('modal-opened', { id: '{{ $id }}' }),
            )
        },
    }"
    @if ($id)
        x-on:{{ $closeEventName }}.window="if ($event.detail.id === '{{ $id }}') close()"
        x-on:{{ $openEventName }}.window="if ($event.detail.id === '{{ $id }}') open()"
    @endif
    x-trap.noscroll{{ $autofocus ? '' : '.noautofocus' }}="isOpen"
    x-bind:class="{
        'fi-modal-open': isOpen,
    }"
>
    <div x-cloak x-show="isOpen">
        <div
            id="resizable-element"
            @class([
                'fixed inset-0 z-40',
                'overflow-y-auto' => ! ($slideOver || ($width === MaxWidth::Screen)),
                'cursor-pointer' => $closeByClickingAway,
            ])
        >
            <div
                x-ref="modalContainer"
                @if ($closeByClickingAway)
                    x-on:click.self="
                        document.activeElement.selectionStart === undefined &&
                            document.activeElement.selectionEnd === undefined &&
                            {{ $closeEventHandler }}
                    "
                @endif
                {{
                    $attributes->class([
                        'relative grid min-h-full grid-rows-[1fr_auto_1fr] justify-items-center sm:grid-rows-[1fr_auto_3fr]',
                    ])
                }}
            >
                <div
                    id="modal-content"
                    x-data="{ isShown: false }"
                    x-init="
                        $nextTick(() => {
                            isShown = isOpen
                            $watch('isOpen', () => (isShown = isOpen))
                        })
                    "
                    x-on:keydown.window.escape="{{ $closeEventHandler }}"
                    x-show="isShown"
                    x-transition:enter="duration-300"
                    x-transition:leave="duration-300"
                    x-transition:enter-start="translate-x-full rtl:-translate-x-full"
                    x-transition:enter-end="translate-x-0"
                    x-transition:leave-start="translate-x-0"
                    x-transition:leave-end="translate-x-full rtl:-translate-x-full"
                    {{
                        ($extraModalWindowAttributeBag ?? new \Illuminate\View\ComponentAttributeBag())->class([
                            'fi-modal-window pointer-events-auto relative row-start-2 flex w-full cursor-default flex-col bg-white shadow-xl ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10',
                            'fi-modal-slide-over-window ms-auto overflow-y-auto' => $slideOver,
                            'h-[100dvh]' => $slideOver || ($width === MaxWidth::Screen),
                            'hidden' => ! $visible,
                            match ($width) {
                                MaxWidth::ExtraSmall => 'max-w-xs',
                                MaxWidth::Small => 'max-w-sm',
                                MaxWidth::Medium => 'max-w-md',
                                MaxWidth::Large => 'max-w-lg',
                                MaxWidth::ExtraLarge => 'max-w-xl',
                                MaxWidth::TwoExtraLarge => 'max-w-2xl',
                                MaxWidth::ThreeExtraLarge => 'max-w-3xl',
                                MaxWidth::FourExtraLarge => 'max-w-4xl',
                                MaxWidth::FiveExtraLarge => 'max-w-5xl',
                                MaxWidth::SixExtraLarge => 'max-w-6xl',
                                MaxWidth::SevenExtraLarge => 'max-w-7xl',
                                MaxWidth::Full => 'max-w-full',
                                MaxWidth::MinContent => 'max-w-min',
                                MaxWidth::MaxContent => 'max-w-max',
                                MaxWidth::FitContent => 'max-w-fit',
                                MaxWidth::Prose => 'max-w-prose',
                                MaxWidth::ScreenSmall => 'max-w-screen-sm',
                                MaxWidth::ScreenMedium => 'max-w-screen-md',
                                MaxWidth::ScreenLarge => 'max-w-screen-lg',
                                MaxWidth::ScreenExtraLarge => 'max-w-screen-xl',
                                MaxWidth::ScreenTwoExtraLarge => 'max-w-screen-2xl',
                                MaxWidth::Screen => 'fixed inset-0',
                                default => $width,
                            },
                        ])
                    }}
                >
                    @if ($heading)
                        <div class="fi-modal-header flex p-3 items-center fi-sticky sticky top-0 z-10 border-b border-gray-200 bg-white dark:border-white/10 dark:bg-gray-900">
                            <div class="absolute end-6 top-6">
                                <x-filament::icon-button
                                    color="gray"
                                    icon="heroicon-o-x-mark"
                                    icon-alias="modal.close-button"
                                    icon-size="lg"
                                    :label="__('filament::components/modal.actions.close.label')"
                                    tabindex="-1"
                                    :x-on:click="$closeEventHandler"
                                    class="fi-modal-close-btn"
                                />
                            </div>

                            <x-filament::modal.heading>
                                {{ $heading }}
                            </x-filament::modal.heading>
                        </div>
                    @endif

                    @if ($slot)
                        <div  class="fi-modal-content flex flex-col gap-y-4 py-6 flex-1">
                            {{ $slot }}
                        </div>
                    @endif

                    @if ($footerActions)
                        <div class="fi-modal-footer w-full bottom-0 border-t border-gray-200 bg-white py-5 dark:border-white/10 dark:bg-gray-900 sticky px-4 py-2">
                            <div class="fi-modal-footer-actions gap-3">
                                {{ $footerActions }}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
