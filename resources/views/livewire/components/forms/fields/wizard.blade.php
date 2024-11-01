@php
    $isContained = $isContained();
    $statePath = $getStatePath();
@endphp

<div
    wire:ignore.self
    x-cloak
    x-data="{
        step: null,

        goToFirstStep: function () {
            this.step = this.getSteps()[0]

            this.autofocusFields()
            this.scrollToTop()
        },

        nextStep: function () {
            let nextStepIndex = this.getStepIndex(this.step) + 1

            if (nextStepIndex >= this.getSteps().length) {
                return
            }

            this.step = this.getSteps()[nextStepIndex]

            this.autofocusFields()
            this.scrollToTop()
        },

        previousStep: function () {
            let previousStepIndex = this.getStepIndex(this.step) - 1

            if (previousStepIndex < 0) {
                return
            }

            this.step = this.getSteps()[previousStepIndex]

            this.autofocusFields()
            this.scrollToTop()
        },

        scrollToTop: function () {
            document.getElementById('main-block').scrollTo({ top: 'top' })
        },

        autofocusFields: function () {
            $nextTick(() =>
                this.$refs[`step-${this.step}`]
                    .querySelector('[autofocus]')
                    ?.focus(),
            )
        },

        getStepIndex: function (step) {
            let index = this.getSteps().findIndex(
                (indexedStep) => indexedStep === step,
            )

            if (index === -1) {
                return 0
            }

            return index
        },

        getSteps: function () {
            return JSON.parse(this.$refs.stepsData.value)
        },

        isFirstStep: function () {
            return this.getStepIndex(this.step) <= 0
        },

        isLastStep: function () {
            return this.getStepIndex(this.step) + 1 >= this.getSteps().length
        },

        isStepAccessible: function (stepId) {
            return (
                @js($isSkippable()) || this.getStepIndex(this.step) > this.getStepIndex(stepId)
            )
        },

        updateQueryString: function () {
            if (! @js($isStepPersistedInQueryString())) {
                return
            }

            const url = new URL(window.location.href)
            url.searchParams.set(@js($getStepQueryStringKey()), this.step)

            history.pushState(null, document.title, url.toString())
        },
    }"
    x-init="
        $watch('step', () => updateQueryString())

        step = getSteps().at({{ $getStartStep() - 1 }})

        autofocusFields()
    "
    x-on:next-wizard-step.window="if ($event.detail.statePath === '{{ $statePath }}') nextStep()"
    x-on:previous-wizard-step.window="if ($event.detail.statePath === '{{ $statePath }}') previousStep()"
    x-on:reset-form.window="goToFirstStep()"
    {{
        $attributes
            ->merge([
                'id' => $getId(),
            ], escape: false)
    }}
>
    <input
        type="hidden"
        value="{{
            collect($getChildComponentContainer()->getComponents())
                ->filter(static fn (\Filament\Forms\Components\Wizard\Step $step): bool => $step->isVisible())
                ->map(static fn (\Filament\Forms\Components\Wizard\Step $step) => $step->getId())
                ->values()
                ->toJson()
        }}"
        x-ref="stepsData"
    />

    @foreach ($getChildComponentContainer()->getComponents() as $step)
        <div x-bind:class="{
            'hidden': getStepIndex(step) !== {{ $loop->index }},
        }">
            {{ $step }}
        </div>
    @endforeach

    <div
        @class([
            'flex items-center justify-between gap-x-3',
            'px-6 pb-6' => $isContained,
            'mt-6' => ! $isContained,
        ])
    >
        <span x-cloak 
            x-on:click="
                $wire.dispatchFormEvent(
                    'wizard::previousStep',
                    '{{ $statePath }}',
                    getStepIndex(step),
                )
            "   
            x-show="! isFirstStep()"
        >
            {{ $getAction('previous') }}
        </span>

        <span x-show="isFirstStep()">
            {{ $getCancelAction() }}
        </span>

        <span
            x-cloak
            x-on:click="
                $wire.dispatchFormEvent(
                    'wizard::nextStep',
                    '{{ $statePath }}',
                    getStepIndex(step),
                )
            "
            x-show="! isLastStep()"
        >
            {{ $getAction('next') }}
        </span>

        <span x-show="isLastStep()">
            {{ $getSubmitAction() }}
        </span>
    </div>
</div>
