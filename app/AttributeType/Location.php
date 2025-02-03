<?php

namespace App\AttributeType;

use App\Models\Attribute\Attribute;
use App\Models\Geo;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select as ComponentsSelect;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Support\Components\ViewComponent;
use Illuminate\Support\Facades\Cache;

class Location extends BaseAttributeType
{
    protected function getFilamentCreateComponent(): ?ViewComponent
    {
        return ComponentsSelect::make('geo_id')
                    ->label(__('livewire.labels.city'))
                    ->placeholder(__('livewire.labels.city'))
                    ->options(
                        Geo::orderBy('population', 'desc')
                            ->where('country', 'CZ')
                            ->where(
                                fn ($query) => $query->where('level', 'PPLA')->orWhere('level', 'PPLC')
                            )
                            ->limit(20)
                            ->get()
                            ?->pluck('name', 'id')
                    )
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search) {
                        return Geo::orderBy('population', 'desc')
                            ->when($search, fn ($query) => $query->search($search))
                            ->when(! $search, fn ($query) => $query->where('level', 'PPLA')->orWhere('level', 'PPLC'))
                            ->where('country', 'CZ')
                            ->limit(20)
                            ->get()
                            ->pluck('name', 'id');
                    })
                    ->getOptionLabelUsing(fn ($value): ?string => Geo::find($value)?->name)
                    ->preload()
                    ->live()
                    ->required($this->attribute->is_required);
    }
}