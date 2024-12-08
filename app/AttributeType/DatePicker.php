<?php

namespace App\AttributeType;

use App\Models\Feature;
use Carbon\Carbon;
use Filament\Forms\Components\DatePicker as ComponentsDatePicker;
use Filament\Forms\Set;
use Filament\Support\Components\ViewComponent;
use Illuminate\Support\Collection;

class DatePicker extends BaseAttributeType
{
    public function getValue(Feature $feature = null) : ?string
    {
        return Carbon::parse($feature?->translated_value['original'])->format('d.m.Y');
    }

    protected function getSchema(): null|Collection|array
    {
        return [
            'attribute_id' => $this->attribute->id,
            'translated_value'        => [
                'original' => Carbon::parse($this->data[$this->attribute->name])->format('Y-m-d'),
            ],
        ];
    }

    protected function getFakeSchema(): null|Collection|array
    {
        return [
            'attribute_id' => $this->attribute->id,
            'translated_value' => [
                'original' => fake()->dateTime()->format('Y-m-d'),
            ],
        ];
    }

    protected function getFilamentCreateComponent(): ?ViewComponent
    {
        return ComponentsDatePicker::make('attributes.'.$this->attribute->name)
                ->label($this->attribute->label)
                ->suffix($this->attribute->suffix)
                ->required($this->attribute->is_required)
                ->readonly($this->attribute->is_readonly)
                ->afterStateHydrated(function (Set $set) {
                    $set('attributes.'.$this->attribute->name, Carbon::now()->format('Y-m-d'));
                });
    }

    protected function getFilamentFilterComponent(): ?ViewComponent
    {
        return ComponentsDatePicker::make('attributes.'.$this->attribute->name)
                ->label($this->attribute->label);
    }
}