<?php

namespace App\Livewire\Actions;

use App\Livewire\Components\Forms\Components\OpsMap;
use App\Models\Geo;
use Livewire\Component;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\HtmlString;

class LocationForm extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    public $locationData;

    public $category;

    public $countries;

    private $defaultCoordinates = [
        'lat' => 50.073658,
        'lng' => 14.418540
    ];

    public $moving = false;
    

    public function mount($location, $category = null)
    {
        $this->category = $category;
        $this->locationData = $location ?? []; 
        $this->countries = Cache::rememberForever('countries', fn () => Geo::select('name', 'country')->where('level', 'PCLI')->get());
    }

    public function location(): Action
    {
        return Action::make('location') 
            ->link()
            ->icon('heroicon-o-map-pin')
            ->extraAttributes(['class' => 'cursor-pointer whitespace-nowrap'])
            ->label(Geo::find($this->locationData['geo_id'] ?? null)?->name ?? __('livewire.location'))
            ->form([

                Select::make('geo_id')
                    ->label(__('livewire.city'))
                    ->searchable()
                    ->preload()
                    ->options(function (Get $get) {
                        return Geo::orderBy('level')
                            ->select('id', 'name', 'country')
                            ->get()
                            ->pluck('name', 'id');
                    })

                    ->optionsLimit(20)
                    ->getSearchResultsUsing(function (string $search, Get $get) {
                        return Geo::whereRaw('LOWER(alternames) LIKE ?', ['%' . mb_strtolower($search) . '%'])
                            ->limit(30)
                            ->select('id', 'name', 'country')
                            ->get()
                            ->pluck('name', 'id');
                    })
                    ->live()
                    ->placeholder(__('livewire.city'))
                    ->afterStateUpdated(function (Set $set, Get $get, $state, $livewire) {
                        $geo = Geo::find($state) ?? Geo::radius($this->defaultCoordinates['lat'], $this->defaultCoordinates['lng'], 10)?->first();
                        $set('coordinates', [
                            'lat' => $geo?->latitude, 
                            'lng' => $geo?->longitude
                        ]);

                        $livewire->dispatch('refreshMap');
                    })
                    ->required()
                    ->columnSpan(1),
                OpsMap::make('coordinates')
                    ->showMyLocationButton()
                    ->liveLocation(true, false, 500)
                    ->debounce(1000)
                    ->afterStateUpdated(function (Set $set, Get $get, $state, $livewire) {
                        if ($this->moving) {
                            return;
                        }
                        
                        $geo = Geo::radius($state['lat'], $state['lng'], 10)?->first();

                        $set('geo_id', $geo?->id);
                    })
                    ->afterStateHydrated(function (Set $set): void {
                        $set('coordinates', [
                            'lat' => $this->locationData['coordinates']['lat'] ?? $this->defaultCoordinates['lat'], 
                            'lng' => $this->locationData['coordinates']['lng'] ?? $this->defaultCoordinates['lng']
                        ]);
                        $set('geo_id', Geo::radius(
                            $this->locationData['coordinates']['lat'] ?? $this->defaultCoordinates['lat'], 
                            $this->locationData['coordinates']['lng'] ?? $this->defaultCoordinates['lng'], 
                            10
                        )->first()->id);
                    })
                    ->zoom(11)
                    ->extraStyles([
                        'border-radius: 10px'
                    ])
                    ->label(false)
                    ->required()
                    ->columnSpanFull(),
                Select::make('radius')
                    ->label(__('livewire.radius'))
                    ->options([
                        10 => '10 km',
                        20 => '20 km',
                        30 => '30 km',
                        40 => '40 km',
                        50 => '50 km',
                        60 => '60 km',
                        70 => '70 km',
                    ])
                    ->afterStateHydrated(function (Set $set) {
                        $set('radius', $this->locationData['radius'] ?? 30);
                    })
                    ->selectablePlaceholder(false)
                    ->placeholder(__('livewire.radius'))
            ])
            ->action(fn (array $data) => 
                $this->redirectRoute('announcement.search', ['location' => $data, 'category' => $this->category?->slug])
            )
            ->modalCancelAction(false)
            ->extraModalFooterActions([
                Action::make('reset')
                    ->icon('heroicon-m-x-mark')
                    ->action(fn () => $this->resetData())
                    ->label(__('livewire.reset_location'))
                    ->color('danger'),
            ])
            ->modalSubmitAction(fn (StaticAction $action) => 
                $action
                    ->disabled($this->moving)
                    ->label($this->moving ? __('livewire.search') : __('livewire.apply'))
            )
            ->modalWidth('xl');
    }

    public function render()
    {
        $actions = [
            'location',
        ];

        return view('livewire.actions.actions', compact('actions'));
    }

    public function resetData()
    {
        session()->forget('location');
        
        $this->redirectRoute('announcement.search', ['location' => null, 'category' => $this->category?->slug]);
    }
}
