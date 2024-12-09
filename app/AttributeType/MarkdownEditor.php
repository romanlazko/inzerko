<?php

namespace App\AttributeType;

use App\Facades\Purifier;
use Filament\Forms\Components\MarkdownEditor as ComponentsMarkdownEditor;
use Filament\Support\Components\ViewComponent;

class MarkdownEditor extends BaseAttributeType
{
    protected function getFilamentCreateComponent(): ?ViewComponent
    {
        return ComponentsMarkdownEditor::make('attributes.'.$this->attribute->name)
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
            ->dehydrateStateUsing(fn ($state) => Purifier::purify($state));
    }
}