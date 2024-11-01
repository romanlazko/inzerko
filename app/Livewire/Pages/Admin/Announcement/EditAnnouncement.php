<?php

namespace App\Livewire\Pages\Admin\Announcement;

use App\Livewire\Layouts\AdminEditFormLayout;
use App\Models\Announcement;
use App\Models\Category;
use App\Services\Actions\CategoryAttributeService;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Get;

class EditAnnouncement extends AdminEditFormLayout
{
    public Announcement $announcement;

    public function mount(Announcement $announcement): void
    {
        $this->form->fill($announcement->toArray());
    }
    
    public function form(Form $form): Form
    {
        return $form
            ->schema(function ($record) {
                $feature_attributes = CategoryAttributeService::forCreate($record->categories->pluck('id')->toArray());
                return [
                    SpatieMediaLibraryFileUpload::make('attachments')
                        ->collection('announcements')
                        ->hiddenLabel()
                        ->multiple()
                        ->image()
                        ->imagePreviewHeight('120')
                        ->required(),
                    Select::make('categories')
                        ->relationship('categories')
                        ->options(fn () => Category::all()->pluck('name', 'id'))
                        ->multiple(),
                    Repeater::make('features')
                        ->relationship('features')
                        ->schema([
                            Select::make('attribute_id')
                                ->options($feature_attributes->pluck('label', 'id'))
                                ->live(),
                            Select::make('attribute_option_id')
                                ->options(fn (Get $get) => $feature_attributes->where('id', $get('attribute_id'))?->first()?->attribute_options->pluck('name', 'id'))
                                ->hidden(fn (Get $get) => $feature_attributes->where('id', $get('attribute_id'))?->first()?->attribute_options->isEmpty()),
                            Textarea::make('translated_value')
                                ->formatStateUsing(fn ($state) => json_encode($state))
                                ->rows(1)
                                ->autosize()
                                ->dehydrateStateUsing(fn ($state) => json_decode($state, true))
                        ])
                        ->columns(2)
                ];
            })
            ->statePath('data')
            ->model($this->announcement);
    }

    public function update()
    {
        $this->announcement->update($this->form->getState());
        $this->form->model($this->announcement)->saveRelationships();
    }
}