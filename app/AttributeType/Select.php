<?php

namespace App\AttributeType;

use Filament\Forms\Components\Select as ComponentsSelect;
use Filament\Support\Components\ViewComponent;
use Illuminate\Database\Eloquent\Builder;

class Select extends BaseAttributeType
{
    protected function getFilterQuery(Builder $query) : Builder
    {
        if ($attribute_option = $this->attribute->attribute_options?->where('id', $this->data[$this->attribute->name] ?? null)->first() AND $attribute_option?->is_null) {
            return $query;
        }

        return $query->where('attribute_id', $this->attribute->id)
            ->where('attribute_option_id', $this->data[$this->attribute->name]);
    }

    protected function getFilamentFilterComponent(): ?ViewComponent
    {
        return ComponentsSelect::make('attributes.'.$this->attribute->name)
            ->label($this->attribute->label)
            ->options($this->attribute->attribute_options?->pluck('name', 'id'))
            ->live()
            ->extraInputAttributes(['title' => 'Select '.$this->attribute->label]);
    }

    protected function getFilamentCreateComponent(): ?ViewComponent
    {
        return ComponentsSelect::make('attributes.'.$this->attribute->name)
            ->label($this->attribute->label)
            ->options($this->attribute->attribute_options->where('is_null', false)->pluck('name', 'id'))
            ->live()
            ->required($this->attribute->is_required);
    }
}