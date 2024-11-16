<?php

namespace App\AttributeType;

use App\Facades\Purifier;
use Filament\Forms\Components\RichEditor as ComponentsRichEditor;
use Filament\Support\Components\ViewComponent;

class RichEditor extends BaseAttributeType
{
    protected function getFilamentCreateComponent(): ?ViewComponent
    {
        return ComponentsRichEditor::make('attributes.'.$this->attribute->name)
            ->label($this->attribute->label)
            ->toolbarButtons([
                'bold',
                'bulletList',
                'italic',
                'orderedList',
                'redo',
                'undo',
            ])
            ->maxLength(1000)
            ->live()
            ->required($this->attribute->is_required)
            ->dehydrateStateUsing(fn (string $state) => Purifier::purify($state));
    }
}