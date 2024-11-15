<?php

namespace App\AttributeType;

use App\Models\Attribute;
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
        return Grid::make(2)
            ->schema([
                ComponentsSelect::make('geo_id')
                    ->label(__('livewire.labels.city'))
                    ->placeholder(__('livewire.labels.city'))
                    ->options(fn (Get $get) => Geo::orderBy('level')->where('country', 'CZ')->limit(10)->get()?->pluck('name', 'id'))
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search) {
                        return Geo::whereRaw('LOWER(alternames) LIKE ?', ['%' . mb_strtolower($search) . '%'])
                            ->where('country', 'CZ')
                            ->limit(10)
                            ->get()
                            ->pluck('name', 'id');
                    })
                    ->getOptionLabelUsing(fn ($value): ?string => Geo::find($value)?->name)
                    ->preload()
                    ->live()
                    ->required()
            ]);
    }
}