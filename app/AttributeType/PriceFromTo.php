<?php

namespace App\AttributeType;

use App\Models\Feature;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput as ComponentsTextInput;
use Filament\Support\Components\ViewComponent;
use Filament\Forms\Components\Select as ComponentsSelect;
use Illuminate\Support\Number;
use Guava\FilamentClusters\Forms\Cluster;
use Illuminate\Support\Collection;

class PriceFromTo extends FromTo
{
    public function getValue(Feature $feature = null) : ?string
    {
        return Number::format($feature->translated_value['original']['from'], locale: 'cs') . ' - ' . Number::format($feature->translated_value['original']['to'], locale: 'cs')  . ' ' . $feature->attribute_option?->name;
    }

    protected function getSchema(): null|Collection|array
    {
        return [
            'attribute_id' => $this->attribute->id,
            'attribute_option_id' => $this->data[$this->attribute->name]['currency'],
            'translated_value' => [
                'original' => [
                    'from' => $this->data[$this->attribute->name]['from'],
                    'to' => $this->data[$this->attribute->name]['to']
                ]
            ],
        ];
    }

    protected function getFakeSchema(): null|Collection|array
    {
        return [
            'attribute_id' => $this->attribute->id,
            'attribute_option_id' => $this->attribute->attribute_options->where('is_null', '!=' ,true)->random()->id,
            'translated_value' => [
                'original' => [
                    'from' => fake()->numberBetween(0, 1000),
                    'to' => fake()->numberBetween(1000, 2000),
                ],
            ],
        ];
    }

    protected function getOriginalValue(Feature $feature): mixed
    {
        return [
            'currency' => $feature->attribute_option_id,
            'from' => $feature->translated_value['original']['from'],
            'to' => $feature->translated_value['original']['to']
        ];
    }

    protected function getFilamentCreateComponent(): ?ViewComponent
    {   
        return Grid::make()
            ->schema([
                Cluster::make([
                    ComponentsTextInput::make('attributes.'.$this->attribute->name.'.from')
                        ->label(__('livewire.placeholders.from'))
                        ->placeholder(__('livewire.placeholders.from'))
                        ->numeric()
                        ->default('')
                        ->required($this->attribute->is_required),
                    ComponentsTextInput::make('attributes.'.$this->attribute->name.'.to')
                        ->label(__('livewire.placeholders.to'))
                        ->placeholder(__('livewire.placeholders.to'))
                        ->numeric()
                        ->default('')
                        ->required($this->attribute->is_required),
                    ])
                    ->label($this->attribute->label)
                    ->columns(['default' => 2])
                    ->columnSpan(['default' => 'full', 'md' => 2]),
                    
                ComponentsSelect::make('attributes.'.$this->attribute->name.'.currency')
                    ->label(__('livewire.labels.currency'))
                    ->options($this->attribute->attribute_options->pluck('name', 'id'))
                    ->required($this->attribute->is_required)
                    ->columnSpan(['default' => 'full', 'md' => 1]),
            ])
            ->columns(3);
    }
}