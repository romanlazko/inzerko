<?php

namespace App\Livewire\Actions\Concerns;

use Closure;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;

trait AttributeSectionFormSection
{
    use HasValidationRulles;
    
    public function getAttributeSectionFormSection(): ?Section
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
                    ->rules($this->getValidationRules())
                    ->required()
                    ->afterStateUpdated(fn ($state, callable $set) => $set('slug', str()->snake($state['en']))),

                TextInput::make('slug')
                    ->required(),
                
                Select::make('type')
                    ->options([
                        'filter' => 'Filter',
                        'create' => 'Create',
                        'show' => 'Show',
                    ]),

                TextInput::make('order_number')
                    ->helperText(__('Порядковый номер секции внутри формы.'))
                    ->numeric()
                    ->required(),
            ])
            ->columns(2);
    }
}