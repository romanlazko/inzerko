<?php

namespace App\Livewire\Actions\Concerns;

use App\Models\AttributeSection;
use Closure;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Builder;

trait FilterLayoutSection 
{
    use AttributeSectionFormSection;

    public static function getFilterLayoutSection(): ?Section
    {
        return Section::make(__("Filter layout"))
            ->schema([
                Grid::make(3)
                    ->schema([
                        Select::make('filter_layout.type')
                            ->options(self::$type_options)
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
                                self::getAttributeSectionFormSection()
                            ])
                            ->createOptionForm([
                                self::getAttributeSectionFormSection()
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