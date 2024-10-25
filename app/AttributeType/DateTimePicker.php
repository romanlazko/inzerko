<?php

namespace App\AttributeType;

use App\Models\Feature;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker as ComponentsDateTimePicker;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Support\Components\ViewComponent;
use Illuminate\Database\Eloquent\Builder;

class DateTimePicker extends BaseAttributeType
{   
    public function getValueByFeature(Feature $feature = null) : ?string
    {
        return Carbon::parse($feature->translated_value['original'])->format('Y-m-d H:i:s');
    }

    protected function schema(): array
    {
        return [
            'attribute_id' => $this->attribute->id,
            'translated_value'        => [
                'original' => Carbon::parse($this->data[$this->attribute->name])->format('Y-m-d H:i:s'),
            ],
        ];
    }

    protected function getFilamentCreateComponent(Get $get = null): ?ViewComponent
    {
        return ComponentsDateTimePicker::make('attributes.'.$this->attribute->name)
                ->label($this->attribute->label)
                ->suffix($this->attribute->suffix)
                ->required($this->attribute->is_required)
                ->readonly($this->attribute->is_readonly)
                ->afterStateHydrated(function (Set $set) {
                    $set('attributes.'.$this->attribute->name, Carbon::now()->format('Y-m-d H:i:s'));
                });
    }

    protected function getFilamentFilterComponent(Get $get = null): ?ViewComponent
    {
        return ComponentsDateTimePicker::make('attributes.'.$this->attribute->name)
                ->label($this->attribute->label);
    }
}