<?php

namespace App\AttributeType;

use Filament\Forms\Get;
use Guava\FilamentClusters\Forms\Cluster;
use Filament\Support\Components\ViewComponent;
use Filament\Forms\Components\TextInput as ComponentsTextInput;
use Illuminate\Database\Eloquent\Builder;

class Between extends BaseAttributeType
{
    protected function getFilterQuery(Builder $query) : Builder
    {
        $from = $this->data[$this->attribute->name]['from'] ?? null;
        $to = $this->data[$this->attribute->name]['to'] ?? null;

        $query->when(!empty($from) OR !empty($to), function ($query) use ($from, $to) {
            $query->where('attribute_id', $this->attribute->id)
                ->when(!empty($from), fn ($query) => $query->where('translated_value->original', '>=', (integer) $from))
                ->when(!empty($to), fn ($query) => $query->where('translated_value->original', '<=', (integer) $to));
        });

        return $query;
    }

    protected function getFilamentFilterComponent(): ?ViewComponent
    {   
        return Cluster::make([
            ComponentsTextInput::make('attributes.'.$this->attribute->name.'.from')
                ->label(__('livewire.placeholders.from'))
                ->placeholder(__('livewire.placeholders.from'))
                ->numeric()
                ->default(''),
            ComponentsTextInput::make('attributes.'.$this->attribute->name.'.to')
                ->label(__('livewire.placeholders.to'))
                ->placeholder(__('livewire.placeholders.to'))
                ->numeric()
                ->default(''),
            ])
            ->hint($this->attribute->suffix)
            ->label($this->attribute->label)
            ->columns(['default' => 2]);
    }

    protected function getFilamentCreateComponent(): ?ViewComponent
    {   
        return (new TextInput($this->attribute, $this->data))->getFilamentCreateComponent();
    }
}