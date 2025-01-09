<?php

namespace App\AttributeType;

use App\Models\Attribute\AttributeOption;
use Filament\Forms\Components\CheckboxList as ComponentsCheckboxList;
use Filament\Support\Components\ViewComponent;
use Illuminate\Database\Eloquent\Builder;

class CheckboxList extends BaseAttributeType
{
    protected function getFilterQuery(Builder $query): Builder
    {
        return $query->where('attribute_id', $this->attribute->id)->whereIn('attribute_option_id', $this->data[$this->attribute->name]);
    }
    
    protected function getFilamentFilterComponent(): ?ViewComponent
    {
        return ComponentsCheckboxList::make('attributes.'.$this->attribute->name)
            ->view('livewire.components.forms.fields.checkbox-list')
            ->label($this->attribute->label)
            ->columns(2)
            ->gridDirection('row')
            ->options($this->attribute->attribute_options?->sortBy('name')->pluck('name', 'id'));
    }
}