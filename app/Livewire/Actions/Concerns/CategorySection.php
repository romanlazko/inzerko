<?php

namespace App\Livewire\Actions\Concerns;

use App\Models\Category;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;

trait CategorySection
{
    public static function getCategorySection(): ?Section
    {
        return Section::make(__('Categories'))
            ->schema([
                Grid::make(2)
                    ->schema([
                        Select::make('categories')
                            ->helperText("Категории к которым принадлежит атрибут. (можно выбрать несколько)")
                            ->relationship('categories')
                            ->multiple()
                            ->options(Category::all()->groupBy('parent.name')->map->pluck('name', 'id'))
                            ->columnSpanFull(),
                    ])
                    ->extraAttributes(['class' => 'bg-gray-100 p-4 rounded-lg border border-gray-200']),
            ]);
    }
}