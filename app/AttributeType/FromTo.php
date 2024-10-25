<?php

namespace App\AttributeType;

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

    protected function getFeatureValue(null|string|array $translated_value = null): ?string
    {
        return implode('-', array_filter([
            'from' => $translated_value['from'],
            'to' => $translated_value['to'],
        ]));
    }

    protected function getFilamentCreateComponent(Get $get = null): ?ViewComponent
    {   
        return Cluster::make([
            ComponentsTextInput::make('attributes.'.$this->attribute->name.'.from')
                ->placeholder(__('livewire.placeholders.from'))
                ->numeric()
                ->default('')
                ->required($this->attribute->is_required),
            ComponentsTextInput::make('attributes.'.$this->attribute->name.'.to')
                ->placeholder(__('livewire.placeholders.to'))
                ->numeric()
                ->default('')
                ->required($this->attribute->is_required),
            ])
            ->label($this->attribute->label)
            ->columns(['default' => 2]);
    }
}