<?php

namespace App\AttributeType;

use App\Models\Feature;
use Filament\Forms\Components\Select as ComponentsSelect;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Support\Components\ViewComponent;
use Illuminate\Database\Eloquent\Builder;

class Select extends BaseAttributeType
{
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
            ->options($this->attribute->attribute_options->pluck('name', 'id'))
            ->live()
            ->required($this->attribute->is_required);
    }
}