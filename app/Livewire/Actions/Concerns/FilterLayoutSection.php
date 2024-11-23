<?php

namespace App\Livewire\Actions\Concerns;

use App\Models\Attribute\AttributeSection;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Builder;

trait FilterLayoutSection 
{
    use AttributeSectionFormSection;

    public function getFilterLayoutSection(array $type_options = [], array $validation_rules = []): ?Section
    {
        return Section::make(__("Filter layout"))
            ->schema([
                Grid::make(3)
                    ->schema([
                        Select::make('filter_layout.type')
                            ->options($type_options)
                            ->required()
                            ->helperText("Тип атрибута при поиске.")
                            ->columnSpanFull()
                            ->live(),
                    ])
                    ->extraAttributes(['class' => 'bg-gray-100 p-4 rounded-lg border border-gray-200']),

                Grid::make(3)
                    ->schema([
                        Select::make('filter_layout.section_id')
                            ->label('Section')
                            ->helperText(__('Секция в которой будет находится этот атрибут'))
                            ->relationship(name: 'filterSection', modifyQueryUsing: fn (Builder $query) => $query->orderBy('order_number'))
                            ->getOptionLabelFromRecordUsing(fn (AttributeSection $record) => "#{$record->order_number} - {$record->name} ({$record->slug})")
                            ->columnSpanFull()
                            ->required()
                            ->editOptionForm([
                                $this->getAttributeSectionFormSection($type_options, $validation_rules)
                            ])
                            ->createOptionForm([
                                $this->getAttributeSectionFormSection($type_options, $validation_rules)
                            ]),
                        TextInput::make('filter_layout.column_span')
                            ->helperText(__("Сколько места по ширине, внутри секции, будет занимать этот атрибут (от 1 до 4)"))
                            ->required(),

                        TextInput::make('filter_layout.column_start')
                            ->helperText(__("В каком месте (слева или справа) будет находиться этот атрибут в секции (от 1 до 4)"))
                            ->required(),

                        TextInput::make('filter_layout.order_number')
                            ->helperText(__("Порядковый номер этого атрибута внутри секции"))
                            ->required()
                    ])
                    ->hidden(fn (Get $get) => $get('filter_layout.type') == 'hidden')
                    ->extraAttributes(['class' => 'bg-gray-100 p-4 rounded-lg border border-gray-200']),
            ])
            ->columns(3);
    }
}