<div>
    @auth
        @php
            $action = $this->getMountedAction();
        @endphp

        <x-filament::modal
            :heading="$action?->getModalHeading()"
            :id="$this->getId() . '-action'"
            :slide-over="$action?->isModalSlideOver()"
            :sticky-header="$action?->isModalHeaderSticky()"
            :width="$action?->getModalWidth()"
            :wire:key="$action ? $this->getId() . '.actions.' . $action->getName() . '.modal' : null"
            class="padding-0"
            :ariaLabelledby="Chats"
        >
            @if ($action)
                {{ $this->getMountedActionForm(mountedAction: $action) }}
            @endif
        </x-filament::modal>

        @php
            $this->hasActionsModalRendered = true;
        @endphp
    @endauth
</div>