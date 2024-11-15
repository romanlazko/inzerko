<?php

namespace App\AttributeType;

use Filament\Forms\Components\Select as ComponentsSelect;
use Filament\Support\Components\ViewComponent;

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