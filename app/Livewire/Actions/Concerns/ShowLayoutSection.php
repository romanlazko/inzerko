<?php

namespace App\Livewire\Actions\Concerns;

use App\Models\Attribute\AttributeSection;
use Closure;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Builder;

trait ShowLayoutSection 
{
    use AttributeSectionFormSection;
    use HasTypeOptions;
    use HasValidationRulles;

    public function getShowLayoutSection()
    {
        return Section::make(__("Show layout"))
            ->schema([
                Grid::make(3)
                    ->schema([
                        Select::make('show_layout.type')
                            ->options($this->type_options)
                            ->required()
                            ->live(),
                    ])
                    ->extraAttributes(['class' => 'bg-gray-100 p-4 rounded-lg border border-gray-200']),

                Grid::make(3)
                    ->schema([
                        Select::make('show_layout.section_id')
                            ->label('Section')
                            ->helperText(__('Секция в которой будет находится этот атрибут'))
                            ->relationship(name: 'showSection', modifyQueryUsing: fn (Builder $query) => $query->where('type', 'show')->orderBy('order_number'))
                            ->getOptionLabelFromRecordUsing(fn (AttributeSection $record) => "#{$record->order_number} - {$record->name} ({$record->slug})")
                            ->columnSpanFull()
                            ->required()
                            // ->editOptionForm([
                            //     $this->getAttributeSectionFormSection()
                            // ])
                            ->createOptionForm([
                                $this->getAttributeSectionFormSection()
                            ]),

                        TextInput::make('show_layout.order_number')
                            ->helperText(__("Порядковый номер этого атрибута внутри секции"))
                            ->required(),
                    ])
                    ->hidden(fn (Get $get) => $get('show_layout.type') == 'hidden')
                    ->extraAttributes(['class' => 'bg-gray-100 p-4 rounded-lg border border-gray-200']),
                Grid::make(3)
                    ->schema([
                        Toggle::make('show_layout.has_label')
                            ->helperText(__("Будет ли отображаться имя этого атрибута внутри секции")),
                    ])
                    ->hidden(fn (Get $get) => $get('show_layout.type') == 'hidden')
                    ->extraAttributes(['class' => 'bg-gray-100 p-4 rounded-lg border border-gray-200']),
            ])
            ->columns(3);
    }
}