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

    protected function getFilamentCreateComponent(Get $get = null): ?ViewComponent
    {
        return Grid::make(2)
            ->schema([
                ComponentsSelect::make('attributes.country')
                    ->label(__('livewire.labels.country'))
                    ->placeholder(__('livewire.labels.country'))
                    ->options($this->countries->pluck('name', 'country'))
                    ->searchable()
                    ->live()
                    ->afterStateUpdated(function (Set $set) {
                        $set('geo_id', null);
                    })
                    ->required()
                    ->default('CZ'),
                ComponentsSelect::make('geo_id')
                    ->label(__('livewire.labels.city'))
                    ->placeholder(__('livewire.labels.city'))
                    ->options(fn (Get $get) => Geo::where('country', $get('attributes.country') ?? 'CZ')?->pluck('name', 'id'))
                    ->searchable()
                    ->getSearchResultsUsing(function (string $search, Get $get) {
                        return Geo::where('country', $get('attributes.country') ?? 'CZ')
                            ->whereRaw('LOWER(alternames) LIKE ?', ['%' . mb_strtolower($search) . '%'])
                            ->pluck('name', 'id');
                    })
                    ->preload()
                    ->live()
                    ->required()
            ]);
    }
}