<?php

namespace App\AttributeType;

use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Filament\Support\Components\ViewComponent;
use Illuminate\Database\Eloquent\Builder;

class MultipleSelect extends BaseAttributeType
{
    protected function getFilterQuery(Builder $query): Builder
    {
        return $query->where('attribute_id', $this->attribute->id)->whereIn('attribute_option_id', $this->data[$this->attribute->name]);
    }

    protected function getFilamentFilterComponent(): ?ViewComponent
    {
        return Select::make('attributes.'.$this->attribute->name)
            ->label($this->attribute->label)
            ->options($this->attribute->attribute_options->pluck('name', 'id'))
            ->multiple()
            ->searchable();
    }
}