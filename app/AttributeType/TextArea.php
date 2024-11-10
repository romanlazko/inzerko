<?php

namespace App\AttributeType;

use Filament\Forms\Components\Textarea as ComponentsTextarea;
use Filament\Forms\Get;
use Filament\Support\Components\ViewComponent;

class TextArea extends BaseAttributeType
{
    protected function getFilamentCreateComponent(): ?ViewComponent
    {
        return ComponentsTextarea::make('attributes.'.$this->attribute->name)
            ->label($this->attribute->label)
            ->autosize()
            ->rows(6)
            ->required($this->attribute->is_required)
            ->rules($this->attribute->create_layout['rules'] ?? []);
    }
}