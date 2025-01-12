<?php

namespace App\Livewire\Actions\Concerns;

use App\Models\Attribute\AttributeSection;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Builder;

trait FilterLayoutSection 
{
    use AttributeSectionFormSection;
    use HasTypeOptions;

    public function getFilterLayoutSection(): ?Section
    {
        return Section::make(__("Filter layout"))
            ->schema([
                Grid::make(3)
                    ->schema([
                        Select::make('filter_layout.type')
                            ->options($this->type_options)
                            ->required()
                            ->helperText("ТИП атрибута при поиске.")
                            ->columnSpanFull()
                            ->live(),
                    ])
                    ->extraAttributes(['class' => 'bg-gray-100 p-4 rounded-lg border border-gray-200']),

                Grid::make(3)
                    ->schema([
                        Select::make('filter_layout.section_id')
                            ->label('Section')
                            ->helperText(__('СЕКЦИЯ в которой будет находится этот атрибут'))
                            ->relationship(name: 'filterSection', modifyQueryUsing: fn (Builder $query) => $query->orderBy('order_number')->where('type', 'filter'))
                            ->getOptionLabelFromRecordUsing(fn (AttributeSection $record) => "#{$record->order_number} - {$record->name} ({$record->slug})")
                            ->columnSpanFull()
                            ->required()
                            // ->editOptionForm([
                            //     $this->getAttributeSectionFormSection()
                            // ])
                            ->createOptionForm([
                                $this->getAttributeSectionFormSection()
                            ]),
                        TextInput::make('filter_layout.column_span')
                            ->helperText(__("СКОЛЬКО МЕСТА ПО ШИРИНЕ, внутри секции, будет занимать этот атрибут (от 1 до 4)"))
                            ->required(),

                        TextInput::make('filter_layout.column_start')
                            ->helperText(__("В КАКОМ МЕСТЕ (слева или справа) будет находиться этот атрибут в секции (от 1 до 4)"))
                            ->required(),

                        TextInput::make('filter_layout.order_number')
                            ->helperText(__("ПОРЯДКОВЫЙ НОМЕР этого атрибута внутри секции"))
                            ->required(),

                        TextInput::make('filter_layout.columns')
                            ->helperText(__("На какое количество столбиков по горизонтали будет разбиваться атрибут"))
                            ->required()
                            ->hidden(fn (Get $get) => $get('filter_layout.type') == 'checkbox_list'),
                    ])
                    ->hidden(fn (Get $get) => $get('filter_layout.type') == 'hidden')
                    ->extraAttributes(['class' => 'bg-gray-100 p-4 rounded-lg border border-gray-200']),

                Grid::make(3)
                    ->schema([
                        Toggle::make('is_always_required')
                            ->helperText(__("ВСЕГДА ПОКАЗЫВАТЬ вне зависимости от категории")),
                        Toggle::make('filter_layout.has_label')
                            ->helperText(__("Будет ли отображаться ЛЕЙБЕЛ этого атрибута внутри секции"))
                    ])
                    ->hidden(fn (Get $get) => $get('filter_layout.type') == 'hidden')
                    ->extraAttributes(['class' => 'bg-gray-100 p-4 rounded-lg border border-gray-200']),
            ])
            ->columns(3);
    }
}