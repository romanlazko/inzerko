<?php

namespace App\Livewire\Components\Announcement;

use App\Enums\Status;
use App\Models\Announcement;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Livewire\Component;


class MyAnnouncements extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    
    public function table(Table $table): Table
    {
        return $table
            ->emptyStateHeading(__('pagination.nothing'))
            ->query(auth()->user()->announcements()->with('features.attribute', 'features.attribute_option', 'media')->getQuery())
            ->columns([
                SpatieMediaLibraryImageColumn::make('media')
                    ->collection('announcements', 'thumb')
                    ->circular()
                    ->stacked()
                    ->limit(2)
                    ->limitedRemainingText(),
                TextColumn::make('title')
                    ->state(fn (Announcement $announcement) => $announcement->title)
                    ->description(fn (Announcement $announcement) => $announcement->description)
                    ->weight(FontWeight::Bold)
                    ->wrap()
                    ->markdown()
                    ->extraAttributes(['class' => 'py-2']),

                TextColumn::make('price')
                    ->state(fn (Announcement $announcement) => $announcement->price),

                TextColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(fn ($record) => $record->status)
                    ->color(fn ($record) => $record->status->filamentColor())
                    ->extraAttributes(['class' => 'py-1'])
                    ->badge(),
            ])
            ->recordUrl(fn (Announcement $record) => route('announcement.show', $record))
            ->actions([
                ActionGroup::make([
                    Action::make('sold')
                        ->label(__("Mark as 'Sold'"))
                        ->form([
                            Section::make()
                                ->schema([
                                    ToggleButtons::make('sold')
                                        ->label(__('Where did you sell it?'))
                                        ->options([
                                            '1' => __('I sold it on this site'),
                                            '2' => __('I sold it on another site'),
                                            '3' => __("I don't want to answer"),
                                        ])
                                        ->required()
                                ])
                        ])
                        ->slideOver()
                        ->modalWidth('sm')
                        ->closeModalByClickingAway(false)
                        ->icon('heroicon-m-archive-box-x-mark')
                        ->color('success')
                        ->action(function ($record, array $data) {
                            $record->sold();
                        })
                        ->visible(fn ($record) => $record->status == Status::published),
                    Action::make('available')
                        ->label(__("Mark as 'Available'"))
                        ->icon('heroicon-m-archive-box-arrow-down')
                        ->color('info')
                        ->action(function ($record) {
                            $record->published();
                        })
                        ->visible(fn ($record) => $record->status == Status::sold),
                    DeleteAction::make()
                ])
                ->button()
            ]);
    }
    public function render()
    {
        return view('livewire.layouts.table');
    }
}
