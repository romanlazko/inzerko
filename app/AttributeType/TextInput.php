<?php

namespace App\AttributeType;

use Filament\Forms\Components\TextInput as ComponentsTextInput;
use Filament\Forms\Get;
use Filament\Support\Components\ViewComponent;

class TextInput extends BaseAttributeType
{
    protected function getFilamentCreateComponent(): ?ViewComponent
    {
        return ComponentsTextInput::make('attributes.'.$this->attribute->name)
                ->label($this->attribute->label)
                ->suffix($this->attribute->suffix)
                ->rules($this->attribute->create_layout['rules'] ?? [])
                ->required($this->attribute->is_required);
    }

    protected function getFilamentFilterComponent(): ?ViewComponent
    {
        return ComponentsTextInput::make('attributes.'.$this->attribute->name)
                ->label($this->attribute->label)
                ->suffix($this->attribute->suffix);
    }
}