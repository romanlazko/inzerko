<?php

namespace App\AttributeType;

use App\Facades\Purifier;
use Filament\Forms\Components\MarkdownEditor as ComponentsMarkdownEditor;
use Filament\Forms\Get;
use Filament\Support\Components\ViewComponent;

class MarkdownEditor extends BaseAttributeType
{
    protected function getFilamentCreateComponent(Get $get = null): ?ViewComponent
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
            ->required($this->attribute->is_required)
            ->dehydrateStateUsing(fn (string $state) => Purifier::purify($state));
    }
}