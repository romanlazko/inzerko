<?php

namespace App\AttributeType;

use Filament\Forms\Components\CheckboxList as ComponentsCheckboxList;
use Filament\Forms\Get;
use Filament\Support\Components\ViewComponent;
use Illuminate\Database\Eloquent\Builder;

class CheckboxList extends BaseAttributeType
{
    protected function getFilterQuery(Builder $query) : Builder
    {
        return $query->where('attribute_id', $this->attribute->id)
            ->when($this->attribute->attribute_options->isNotEmpty(), fn ($query) =>
                $query->whereIn('attribute_option_id', $this->data[$this->attribute->name])
            )
            ->when($this->attribute->attribute_options->isEmpty(), fn ($query) =>
                $query->whereIn('translated_value->original', $this->data[$this->attribute->name])
            );
    }

    protected function getFilamentFilterComponent(Get $get = null): ?ViewComponent
    {
        return ComponentsCheckboxList::make('attributes.'.$this->attribute->name)
            ->label($this->attribute->label)
            ->options($this->attribute->attribute_options?->pluck('name', 'id'));
    }
}