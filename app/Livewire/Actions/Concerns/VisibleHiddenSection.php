<?php

namespace App\Livewire\Actions\Concerns;

use App\Models\Attribute\Attribute;
use App\Models\Category;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Get;
use Filament\Forms\Set;

trait VisibleHiddenSection 
{
    public function getVisibleHiddenSection(array $type_options = [], array $validation_rules = []): ?Section
    {
        return Section::make(__('Visible/Hidden'))
            ->schema([
                Grid::make(1)
                    ->schema([
                        Toggle::make('show_on_condition')
                            ->label(__('Show on condition'))
                            ->live()
                            ->dehydrated(false)
                            ->columnSpanFull(),

                        Repeater::make('visible')
                            ->schema([
                                Select::make('attribute_name')
                                    ->label('Attribute')
                                    ->options(function (Get $get) {
                                        $categories = Category::whereIn('id',$get('../../categories'))->get()->map(function ($category) { 
                                            return $category->parentsAndSelf->pluck('id'); 
                                        })
                                        ->flatten();

                                        return Attribute::whereHas('attribute_options')
                                            ->whereHas('categories', fn ($query) => $query->whereIn('category_id', $categories ?? []))
                                            ->get()
                                            ->pluck('label', 'name');
                                    })
                                    ->required()
                                    ->live(),

                                Select::make('value')
                                    ->options(fn (Get $get) => Attribute::whereName($get('attribute_name'))->first()?->attribute_options->pluck('name', 'id'))
                            ])
                            ->visible(fn (Get $get) => $get('show_on_condition'))
                            ->afterStateHydrated(fn ($state, Set $set) => $set('show_on_condition', !empty($state)))
                            ->defaultItems(1)
                            ->hiddenLabel()
                            ->columns(1)
                    ])
                    ->columnSpan(1)
                    ->extraAttributes(['class' => 'bg-gray-100 p-4 rounded-lg border border-gray-200']),
                Grid::make(1)
                    ->schema([
                        Toggle::make('hide_on_condition')
                            ->label(__('Hide on condition'))
                            ->live()
                            ->dehydrated(false),

                        Repeater::make('hidden')
                            ->schema([
                                Select::make('attribute_name')
                                    ->label('Attribute')
                                    ->options(function (Get $get) {
                                        $categories = Category::whereIn('id',$get('../../categories'))->get()->map(function ($category) { 
                                            return $category->parentsAndSelf->pluck('id'); 
                                        })
                                        ->flatten();

                                        return Attribute::whereHas('attribute_options')
                                            ->whereHas('categories', fn ($query) => $query->whereIn('category_id', $categories ?? []))
                                            ->get()
                                            ->pluck('label', 'name');
                                    })
                                    ->required()
                                    ->live(),

                                Select::make('value')
                                    ->options(fn (Get $get) => Attribute::whereName($get('attribute_name'))->first()?->attribute_options->pluck('name', 'id'))
                            ])
                            ->visible(fn (Get $get) => $get('hide_on_condition'))
                            ->afterStateHydrated(fn ($state, Set $set) => !empty($state) ? $set('hide_on_condition', true) : $set('hide_on_condition', false))
                            ->defaultItems(1)
                            ->hiddenLabel()
                            ->columns(1)
                    ])
                    ->columnSpan(1)
                    ->extraAttributes(['class' => 'bg-gray-100 p-4 rounded-lg border border-gray-200'])
            ])
            ->columns(2);
    }
}