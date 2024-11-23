<?php

namespace App\Livewire\Actions\Concerns;

use Closure;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\TextInput;

trait AttributeSectionFormSection
{
    public function getAttributeSectionFormSection(array $type_options = [], array $validation_rules = []): ?Section
    {
        return Section::make()
            ->schema([
                KeyValue::make('alternames')
                    ->label('Label')
                    ->keyLabel(__('Language'))
                    ->valueLabel(__('Value'))
                    ->columnSpan(2)
                    ->live(debounce: 500)
                    ->default([
                        'en' => '',
                        'cs' => '',
                        'ru' => '',
                    ])
                    ->rules([
                        fn (): Closure => function (string $attribute, $value, Closure $fail) {
                            if (!isset($value['en']) OR $value['en'] == '') 
                                $fail('The :attribute must contain english translation.');
                        },
                    ])
                    ->required()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', str()->snake($state['en']))),

                TextInput::make('slug')
                    ->required(),

                TextInput::make('order_number')
                    ->helperText(__('Порядковый номер секции внутри формы.'))
                    ->numeric()
                    ->required(),
            ])
            ->columns(2);
    }
}