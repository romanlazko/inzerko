<?php

namespace App\AttributeType;

use App\Models\Attribute;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select as ComponentsSelect;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Database\Eloquent\Builder;
use Filament\Support\Components\ViewComponent;
use Igaster\LaravelCities\Geo;
use Illuminate\Support\Facades\Cache;

class Location extends BaseAttributeType
{
    private $countries;

    public function __construct(public Attribute $attribute, public $data = [])
    {
        $this->countries = Cache::rememberForever('countries', fn () => Geo::select('name', 'country')->where('level', 'PCLI')->get());

        parent::__construct($attribute, $data);
    }

    protected function getFilamentFilterComponent(Get $get = null): ?ViewComponent
    {
        return Grid::make(2)
            ->schema([
                ComponentsSelect::make('country')
                    ->label(__('livewire.labels.country'))
                    ->options($this->countries->pluck('name', 'country'))
                    ->searchable()
                    ->afterStateUpdated(function (Set $set) {
                        $set('geo_id', null);
                    })
                    ->placeholder(__('livewire.labels.country'))
                    ->default('CZ')
                    ->live(),
                ComponentsSelect::make('geo_id')
                    ->label(__('livewire.labels.city'))
                    ->searchable()
                    ->preload()
                    ->options(fn (Get $get) => Geo::where('country', $get('country') ?? 'CZ')->orderBy('level')->pluck('name', 'id'))
                    ->getSearchResultsUsing(function (string $search, Get $get) {
                        return Geo::where('country', $get('country') ?? 'CZ')
                            ->whereRaw('LOWER(alternames) LIKE ?', ['%' . mb_strtolower($search) . '%'])
                            ->limit(30)
                            ->pluck('name', 'id');
                    })
                    ->live()
                    ->placeholder(__('livewire.labels.city')),
                ComponentsSelect::make('radius')
                    ->label(__('livewire.labels.radius'))
                    ->options([
                        10 => '10 km',
                        20 => '20 km',
                        30 => '30 km',
                        40 => '40 km',
                        50 => '50 km',
                        60 => '60 km',
                        70 => '70 km',
                    ])
                    ->hidden(fn (Get $get) => $get('geo_id') == null)
                    ->afterStateHydrated(function (Get $get, Set $set) {
                        if ($get('radius') == null) {
                            $set('radius', 30);
                        }
                    })
                    ->selectablePlaceholder(false)
                    ->placeholder(__('livewire.labels.radius')),
            ]);
    }

    protected function getFilamentCreateComponent(Get $get = null): ?ViewComponent
    {
        return Grid::make(2)
            ->schema([
                ComponentsSelect::make('attributes.country')
                    ->label(__('livewire.labels.country'))
                    ->options($this->countries->pluck('name', 'country'))
                    ->searchable()
                    ->afterStateUpdated(function (Set $set) {
                        $set('geo_id', null);
                    })
                    ->required()
                    ->placeholder(__('livewire.labels.country'))
                    ->default('CZ')
                    ->live(),
                ComponentsSelect::make('geo_id')
                    ->label(__('livewire.labels.city'))
                    ->searchable()
                    ->preload()
                    ->options(fn (Get $get) => Geo::where('country', $get('attributes.country') ?? 'CZ')?->pluck('name', 'id'))
                    ->getSearchResultsUsing(function (string $search, Get $get) {
                        return Geo::where('country', $get('attributes.country') ?? 'CZ')
                            ->whereRaw('LOWER(alternames) LIKE ?', ['%' . mb_strtolower($search) . '%'])
                            ->pluck('name', 'id');
                    })
                    ->live()
                    ->required()
                    ->placeholder(__('livewire.labels.city'))
            ]);
    }
}