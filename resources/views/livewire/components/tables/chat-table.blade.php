@php
    $columnsLayout = $getColumnsLayout();
    $isLoaded = $isLoaded();
    $records = $isLoaded ? $getRecords() : null;
    $columnsCount = count($getVisibleColumns());
@endphp

<div
    @if (! $isLoaded)
        wire:init="loadTable"
    @endif
    x-ignore
    @if (Filament\Support\Facades\FilamentView::hasSpaMode())
        ax-load="visible"
    @else
        ax-load
    @endif
    ax-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('table', 'filament/tables') }}"
    x-data="table"
>
    {{-- <x-filament-tables::container> --}}
        
    {{-- </x-filament-tables::container> --}}
    <div>
        <div class="fi-ta-header-ctn divide-y divide-gray-200 dark:divide-white/10">
            <div class="fi-ta-header-toolbar flex items-center justify-between px-4 py-3 sm:px-6 w-full">
                <x-filament-tables::search-field
                    :debounce="$getSearchDebounce()"
                    :on-blur="$isSearchOnBlur()"
                    :placeholder="$getSearchPlaceholder()"
                    class="w-full"
                />
            </div>
        </div>
        
        <hr>

        <div wire:poll.5000ms class="fi-ta-content relative divide-y divide-gray-200 overflow-x-auto dark:divide-white/10 dark:border-t-white/10">
            @if (count($records))
                <x-filament::grid x-on:end.stop="$wire.reorderTable($event.target.sortable.toArray())">
                    @foreach ($records as $record)
                        @php
                            $recordAction = $getRecordAction($record);
                            $recordKey = $getRecordKey($record);
                        @endphp

                        <div 
                            @class([
                                'hover:bg-gray-50', 
                                'border-t border-gray-200 dark:border-white/10 dark:hover:bg-white/5' => ! $loop->first,
                            ]) 
                            wire:key="{{ $this->getId() }}.table.records.{{ $recordKey }}"
                        >
                            <button
                                type="button"
                                wire:click="{{ "mountTableAction('{$recordAction}', '{$recordKey}')" }}"
                                wire:loading.attr="disabled"
                                wire:target="{{ "mountTableAction('{$recordAction}', '{$recordKey}')" }}"
                                class="px-2 py-1 block w-full"
                            >
                                <x-filament-tables::columns.layout
                                    :components="$columnsLayout"
                                    :record="$record"
                                    :record-key="$recordKey"
                                />
                            </button>
                        </div>
                        <hr>
                    @endforeach
                </x-filament::grid>
            @else
                <tr>
                    <td colspan="{{ $columnsCount }}">
                        <x-filament-tables::empty-state
                            :actions="$getEmptyStateActions()"
                            :description="$getEmptyStateDescription()"
                            :heading="$getEmptyStateHeading()"
                            :icon="$getEmptyStateIcon()"
                        />
                    </td>
                </tr>
            @endif
        </div>

        @if ((($records instanceof \Illuminate\Contracts\Pagination\Paginator) || ($records instanceof \Illuminate\Contracts\Pagination\CursorPaginator)) &&
             ((! ($records instanceof \Illuminate\Contracts\Pagination\LengthAwarePaginator)) || $records->total()))
            <x-filament::pagination
                :extreme-links="$hasExtremePaginationLinks()"
                :page-options="$getPaginationPageOptions()"
                :paginator="$records"
                class="fi-ta-pagination px-3 py-3 sm:px-6"
            />
        @endif
    </div>

    @if ($this instanceof \Filament\Tables\Contracts\HasTable && (! $this->hasTableModalRendered))
        <form wire:submit.prevent="callMountedTableAction" id="show-chat-form" x-data="{
            scrollToBottom(el) {
                $nextTick(() => { 
                    const modalWindow = el.querySelector('#modal-content');
                    if (modalWindow) {
                        modalWindow.scrollTo(0, modalWindow.scrollHeight);
                    }
                });
            }
        }">
            @php
                $action = $this->getMountedTableAction();
            @endphp

            <x-chat.modal
                :alignment="$action?->getModalAlignment()"
                :autofocus="$action?->isModalAutofocused()"
                :close-button="$action?->hasModalCloseButton()"
                :close-by-clicking-away="$action?->isModalClosedByClickingAway()"
                :close-by-escaping="$action?->isModalClosedByEscaping()"
                :description="$action?->getModalDescription()"
                display-classes="block"
                :extra-modal-window-attribute-bag="$action?->getExtraModalWindowAttributeBag()"
                :heading="$action?->getModalHeading()"
                :icon="$action?->getModalIcon()"
                :icon-color="$action?->getModalIconColor()"
                :id="$this->getId() . '-table-action'"
                :slide-over="$action?->isModalSlideOver()"
                :sticky-footer="true"
                :sticky-header="$action?->isModalHeaderSticky()"
                :visible="filled($action)"
                :width="$action?->getModalWidth()"
                :wire:key="$action ? $this->getId() . '.table.actions.' . $action->getName() . '.modal' : null"
                x-on:closed-form-component-action-modal.window="if (($event.detail.id === '{{ $this->getId() }}') && $wire.mountedTableActions.length) open()"
                x-on:modal-closed.stop="
                    const mountedTableActionShouldOpenModal = {{ \Illuminate\Support\Js::from($action && $this->mountedTableActionShouldOpenModal(mountedAction: $action)) }}

                    if (! mountedTableActionShouldOpenModal) {
                        return
                    }

                    if ($wire.mountedFormComponentActions.length) {
                        return
                    }

                    $wire.unmountTableAction(false);
                "
                x-on:opened-form-component-action-modal.window="
                    if ($event.detail.id === '{{ $this->getId() }}'){ 
                        close();
                    }
                "
                x-on:open-modal.window.debounce.300="scrollToBottom($event.target)"

                x-on:scroll-to-bottom.window="scrollToBottom($event.target)"

                x-on:open-modal.window="
                    if ($event.detail.id === '{{ $this->getId().'-table-action' }}'){ 
                        document.querySelector('main').classList.add('hidden');
                    }
                "

                x-on:close-modal.window="
                    if ($event.detail.id === '{{ $this->getId().'-table-action' }}'){ 
                        document.querySelector('main').classList.remove('hidden');
                    }
                "
            >
                @if ($action)

                    {{ $action->getModalContent() }}

                    <x-slot name="footerActions">
                        <div class="w-full flex items-end justify-end space-x-4 sticky bottom-0">
                            <div class="w-full">
                                @if ($this->mountedTableActionHasForm(mountedAction: $action))
                                    {{ $this->getMountedTableActionForm() }}
                                @endif
                            </div>
                            
                            <div>
                                @foreach ($action?->getVisibleModalFooterActions() as $action)
                                    {{ $action }}
                                @endforeach
                            </div>
                        </div>
                    </x-slot>
                @endif
            </x-chat.modal>
        </form>

        @php
            $this->hasTableModalRendered = true;
        @endphp
    @endif
</div>
