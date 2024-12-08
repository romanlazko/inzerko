<?php

namespace App\AttributeType;

use App\Models\Feature;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput as ComponentsTextInput;
use Filament\Support\Components\ViewComponent;
use Filament\Forms\Components\Select as ComponentsSelect;
use Illuminate\Support\Collection;
use Illuminate\Support\Number;


class Price extends Between
{
    public function getValue(Feature $feature = null) : ?string
    {
        return Number::format($feature->translated_value['original'], locale: 'cs')  . ' ' . $feature->attribute_option?->name;
    }

    protected function getSchema(): null|Collection|array
    {
        return [
            'attribute_id' => $this->attribute->id,
            'attribute_option_id' => $this->data[$this->attribute->name]['currency'],
            'translated_value' => [
                'original' => $this->data[$this->attribute->name]['amount']
            ],
        ];
    }

    protected function getFakeSchema(): null|Collection|array
    {
        return [
            'attribute_id' => $this->attribute->id,
            'attribute_option_id' => $this->attribute->attribute_options->where('is_null', '!=' ,true)->random()->id,
            'translated_value' => [
                'original' => fake()->numberBetween(0, 100000),
            ],
        ];
    }

    protected function getFilamentCreateComponent(): ?ViewComponent
    {   
        return Grid::make()
            ->schema([
                ComponentsTextInput::make('attributes.'.$this->attribute->name.'.amount')
                    ->label($this->attribute->label)
                    ->numeric()
                    ->required($this->attribute->is_required)
                    ->columnSpan(['default' => 'full', 'md' => 2]),
                ComponentsSelect::make('attributes.'.$this->attribute->name.'.currency')
                    ->label(__('livewire.labels.currency'))
                    ->options($this->attribute->attribute_options->pluck('name', 'id'))
                    ->required($this->attribute->is_required)
                    ->columnSpan(['default' => 'full', 'md' => 1]),
            ])
            ->columns(3);
    }

    public function getOriginalValue(Feature $feature = null): mixed
    {
        return [
            'amount' => $feature?->translated_value['original'],
            'currency' => $feature?->attribute_option_id
        ];
    }
}