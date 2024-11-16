<?php

namespace App\AttributeType;

use App\Models\Feature;
use Filament\Forms\Get;
use Filament\Forms\Components\Toggle as ComponentsToggle;
use Filament\Support\Components\ViewComponent;
use Illuminate\Database\Eloquent\Builder;

class Toggle extends BaseAttributeType
{
    public function getValueByFeature(Feature $feature = null) : ?string
    {
        return $this->attribute->label;
    }

    protected function getFilamentFilterComponent(): ?ViewComponent
    {
        return ComponentsToggle::make('attributes.'.$this->attribute->name)
            ->label($this->attribute->label);
    }

    protected function getFilamentCreateComponent(): ?ViewComponent
    {
        return ComponentsToggle::make('attributes.'.$this->attribute->name)
            ->label($this->attribute->label)
            ->live()
            ->required($this->attribute->is_required);
    }
}