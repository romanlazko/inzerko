<?php

namespace App\Livewire\Actions\Concerns;

use App\Models\AttributeSection;
use Closure;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;

trait ShowLayoutSection 
{
    use AttributeSectionFormSection;
    
    public static function getShowLayoutSection()
    {
        return Section::make(__("Show layout"))
            ->schema([
                Grid::make(3)
                    ->schema([
                        Select::make('show_layout.type')
                            ->options(self::$type_options)
                            ->required()
                            ->live(),
                    ])
                    ->extraAttributes(['class' => 'bg-gray-100 p-4 rounded-lg border border-gray-200']),

                Grid::make(3)
                    ->schema([
                        Select::make('show_layout.section_id')
                            ->label('Section')
                            ->helperText(__('Секция в которой будет находится этот атрибут'))
                            ->relationship(name: 'showSection', modifyQueryUsing: fn (Builder $query) => $query->orderBy('order_number'))
                            ->getOptionLabelFromRecordUsing(fn (AttributeSection $record) => "#{$record->order_number} - {$record->name} ({$record->slug})")
                            ->columnSpanFull()
                            ->required()
                            ->editOptionForm([
                                self::getAttributeSectionFormSection()
                            ])
                            ->createOptionForm([
                                self::getAttributeSectionFormSection()
                            ]),

                        TextInput::make('show_layout.order_number')
                            ->helperText(__("Порядковый номер этого атрибута внутри секции"))
                            ->required(),
                    ])
                    ->extraAttributes(['class' => 'bg-gray-100 p-4 rounded-lg border border-gray-200']),
            ])
            ->columns(3);
    }
}