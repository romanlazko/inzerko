<?php

namespace App\AttributeType;

use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Components\ToggleButtons as ComponentsToggleButtons;
use Filament\Support\Components\ViewComponent;
use Illuminate\Database\Eloquent\Builder;

class ToggleButtons extends BaseAttributeType
{
    protected function getFilterQuery(Builder $query) : Builder
    {
        if ($attribute_option = $this->attribute->attribute_options?->where('id', $this->data[$this->attribute->name] ?? null)->first() AND $attribute_option?->is_null) {
            return $query;
        }

        return $query->where('attribute_id', $this->attribute->id)
            ->where('attribute_option_id', $this->data[$this->attribute->name]);
    }

    protected function getFilamentFilterComponent(Get $get = null): ?ViewComponent
    {
        return ComponentsToggleButtons::make('attributes.'.$this->attribute->name)
            ->label($this->attribute->label)
            ->options($this->attribute->attribute_options?->pluck('name', 'id'))
            ->live()
            ->inline()
            ->afterStateHydrated(function (Get $get, Set $set) {
                if ($get('attributes.'.$this->attribute->name) == null) {
                    $set('attributes.'.$this->attribute->name, $this->attribute->attribute_options?->firstWhere('is_default', true)?->id);
                }
            });
    }

    protected function getFilamentCreateComponent(Get $get = null): ?ViewComponent
    {
        return ComponentsToggleButtons::make('attributes.'.$this->attribute->name)
            ->label($this->attribute->label)
            ->options($this->attribute->attribute_options->where('is_null', false)->pluck('name', 'id'))
            ->live()
            ->inline()
            ->required($this->attribute->is_required);
    }
}