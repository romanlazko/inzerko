<?php

namespace App\Livewire\Actions\Concerns;

use Closure;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;

trait AttributeOptionsSection 
{
    use HasTypeOptions;
    use HasValidationRulles;

    public function getAttributeOptionsSection(): ?Section
    {
        return Section::make(__('Options'))
            ->schema([
                Repeater::make('attribute_options')
                    ->hiddenLabel()
                    ->schema([
                        KeyValue::make('alternames')
                            ->label(__('Label'))
                            ->keyLabel(__('Language'))
                            ->valueLabel(__('Value'))
                            ->default([
                                'en' => '',
                                'cs' => '',
                                'ru' => '',
                            ])
                            ->rules($this->getValidationRules())
                            ->live(debounce: 500),

                        Toggle::make('is_default')
                            ->fixIndistinctState()
                            ->helperText(__('Опция будет выбрана по умолчанию при создании объявления и при фильтрации.'))
                            ->visible(fn (Get $get) => in_array($get('../../filter_layout.type'), [
                                'toggle_buttons',
                            ])),

                        Toggle::make('is_null')
                            ->helperText(__('Опция не будет отображаться при создании объявления и не будет учавствовать в фильтрации объявлений.'))
                            ->live()
                            ->visible(fn (Get $get) => in_array($get('../../filter_layout.type'), [
                                'toggle_buttons',
                            ])),
                    ])
                    ->relationship()
                    ->reorderable()
                    ->reorderableWithButtons()
                    ->reorderableWithDragAndDrop(false)
                    ->cloneable()
                    ->itemLabel(fn (array $state): ?string => 
                        ($state['alternames'][app()->getLocale()] ?? $state['alternames']['en'] ?? null) . ($state['is_default'] ? ", DEFAULT" : "") . ($state['is_null'] ? ", NULL" : "")
                    )
                    ->collapsed()
                    ->columnSpanFull()
                    ->extraAttributes(['class' => 'bg-gray-100 p-4 rounded-lg border border-gray-200'])
            ])
            ->visible(fn (Get $get) => 
                in_array($get('filter_layout.type'), array_keys($this->type_options['fields_with_options'])) 
                OR in_array($get('create_layout.type'), array_keys($this->type_options['fields_with_options']))
            );
    }
}