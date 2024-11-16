<?php

namespace App\AttributeType;

use Filament\Forms\Components\CheckboxList as ComponentsCheckboxList;
use Filament\Forms\Get;
use Filament\Support\Components\ViewComponent;
use Illuminate\Database\Eloquent\Builder;

class CheckboxList extends BaseAttributeType
{
    protected function getFilamentFilterComponent(): ?ViewComponent
    {
        return ComponentsCheckboxList::make('attributes.'.$this->attribute->name)
            ->label($this->attribute->label)
            ->options($this->attribute->attribute_options?->pluck('name', 'id'));
    }
}