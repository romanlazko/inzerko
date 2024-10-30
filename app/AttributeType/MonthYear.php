<?php

namespace App\AttributeType;

use App\Models\Feature;
use Carbon\Carbon;
use Guava\FilamentClusters\Forms\Cluster;
use Filament\Forms\Get;
use Filament\Support\Components\ViewComponent;
use Illuminate\Database\Eloquent\Builder;

use Filament\Forms\Components\Select as ComponentsSelect;

class MonthYear extends BaseAttributeType
{
    protected function getFilterQuery(Builder $query) : Builder
    {
        return (new Between($this->attribute, $this->data))->getFilterQuery($query);
    }

    public function getValueByFeature(Feature $feature = null) : ?string
    {
        return Carbon::parse($feature->translated_value['original'])->format('M Y');
    }

    protected function schema(): array
    {
        return [
            'attribute_id' => $this->attribute->id,
            'translated_value'        => [
                'original' => Carbon::parse($this->data[$this->attribute->name]['year'].'-'.$this->data[$this->attribute->name]['month'])->format('Y-m-d'),
            ],
        ];
    }

    protected function getFilamentFilterComponent(Get $get = null): ?ViewComponent
    {
        return Cluster::make([
            ComponentsSelect::make("attributes.{$this->attribute->name}.from")
                ->options(function () {
                    $startYear = 1960;
                    $currentYear = Carbon::now()->year;

                    $years = [];

                    foreach (range($startYear, $currentYear) as $year) {
                        $years[$year] = $year;
                    }

                    return $years;
                })
                ->extraInputAttributes(['aria-label' => 'Select '.$this->attribute->label])
                ->placeholder(__('livewire.placeholders.from')),

            ComponentsSelect::make("attributes.{$this->attribute->name}.to")
                ->options(function () {
                    $startYear = 1960;
                    $currentYear = Carbon::now()->year;

                    $years = [];

                    foreach (range($startYear, $currentYear) as $year) {
                        $years[$year] = $year;
                    }

                    return $years;
                })
                ->extraInputAttributes(['aria-label' => 'Select '.$this->attribute->label])
                ->placeholder(__('livewire.placeholders.to'))
        ])
        ->label($this->attribute->label)
        ->columns(['default' => 2]);
    }

    protected function getFilamentCreateComponent(Get $get = null): ?ViewComponent
    {
        return Cluster::make([
            ComponentsSelect::make("attributes.{$this->attribute->name}.month")
                ->options([
                    '01' => 'January',
                    '02' => 'February',
                    '03' => 'March',
                    '04' => 'April',
                    '05' => 'May',
                    '06' => 'June',
                    '07' => 'July',
                    '08' => 'August',
                    '09' => 'September',
                    '10' => 'October',
                    '11' => 'November',
                    '12' => 'December',
                ])
                ->placeholder(__('livewire.placeholders.month'))
                ->required($this->attribute->is_required),

            ComponentsSelect::make("attributes.{$this->attribute->name}.year")
                ->options(function () {
                    $startYear = 1960;
                    $currentYear = Carbon::now()->year;

                    $years = [];

                    foreach (range($startYear, $currentYear) as $year) {
                        $years[$year] = $year;
                    }

                    return $years;
                })
                ->placeholder(__('livewire.placeholders.year'))
                ->required($this->attribute->is_required)
        ])
        ->label($this->attribute->label)
        ->columns(['default' => 2]);
    }
}