<?php

namespace App\AttributeType;

use App\Models\Feature;
use Filament\Forms\Get;
use Guava\FilamentClusters\Forms\Cluster;
use Filament\Forms\Components\TextInput as ComponentsTextInput;
use Filament\Support\Components\ViewComponent;
use Illuminate\Database\Eloquent\Builder;

class FromTo extends Between
{
    protected function getFilterQuery(Builder $query) : Builder
    {
        $from = $this->data[$this->attribute->name]['from'] ?? null;
        $to = $this->data[$this->attribute->name]['to'] ?? null;

        return $query->when(!empty($from), fn ($query) =>
                $query->where('attribute_id', $this->attribute->id)
                    ->where('translated_value->original->to', '>=', (integer) $from)
                    ->orWhere('translated_value->original->from', '>=', (integer) $from)
            )
            ->when(!empty($to), fn ($query) => 
                $query->where('attribute_id', $this->attribute->id)
                    ->where('translated_value->original->to', '<=', (integer) $to)
                    ->orWhere('translated_value->original->from', '<=', (integer) $to)
            );
    }

    protected function getFakeSchema(): array
    {
        return [
            'attribute_id' => $this->attribute->id,
            'translated_value' => [
                'original' => [
                    'from' => fake()->numberBetween(0, 1000),
                    'to' => fake()->numberBetween(1000, 2000),
                ],
            ],
        ];
    }

    protected function getValue(Feature $feature = null): ?string
    {
        return implode('-', array_filter([
            $feature?->translated_value['original']['from'] ?? null,
            $feature?->translated_value['original']['to'] ?? null,
        ]));
    }

    protected function getFilamentCreateComponent(): ?ViewComponent
    {   
        return Cluster::make([
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
            ->columns(['default' => 2]);
    }
}