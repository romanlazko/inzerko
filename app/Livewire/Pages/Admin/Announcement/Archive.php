<?php

namespace App\Livewire\Pages\Admin\Announcement;

use App\Enums\Status;
use App\Models\Announcement;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\Livewire\Components\Tables\Columns\StatusSwitcher;
use App\Livewire\Layouts\AdminAnnouncementTableLayout;
use App\Models\Feature;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;

class Archive extends AdminAnnouncementTableLayout implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->heading("Archived announcements")
            ->headerActions([
                Action::make('back')
                    ->url(route('admin.announcement.announcements'))
                    ->icon('heroicon-o-arrow-left')
                    ->button()
            ])
            ->query(Announcement::with([
                    'media',
                    'category' => fn ($query) => $query->withTrashed(), 
                    'channels' => fn ($query) => $query->withTrashed()->with('telegram_chat'), 
                    'geo', 
                    'features' => fn ($query) => $query->withTrashed()->with('attribute', 'attribute_option'),
                    'user' => fn ($query) => $query->withTrashed(), 
                ])
                ->onlyTrashed()
            )
            ->defaultSort('created_at', 'desc')
            ->columns([
                    TextColumn::make('id'),

                    SpatieMediaLibraryImageColumn::make('media')
                        ->collection('announcements', 'thumb')
                        ->limit(3)
                        ->wrap(),

                    TextColumn::make('features')
                        ->state(fn (Announcement $announcement) => $announcement->features
                            ->groupBy('attribute.createSection.order_number')
                            ->map
                            ->sortBy('attribute.create_layout.order_number')
                            ->flatten()
                            ->map(fn (Feature $feature) => "{$feature->label}: ". str($feature->value)->stripTags()->limit(100))
                        )
                        ->color('neutral')
                        ->badge()
                        ->searchable(query: function ($query, string $search) {
                            return $query
                                ->whereRaw('LOWER(slug) LIKE ?', ['%' . mb_strtolower($search) . '%'])
                                ->orWhereHas('features', fn ($query) => 
                                    $query->whereRaw('LOWER(translated_value) LIKE ?', ['%' . mb_strtolower($search) . '%'])
                                        ->orWhereHas('attribute_option', fn ($query) => 
                                            $query->whereRaw('LOWER(alternames) LIKE ?', ['%' . mb_strtolower($search) . '%'])
                                        )
                                    );
                        }),

                    TextColumn::make('user.name')
                        ->description(fn (Announcement $announcement) => $announcement->user?->email)
                        ->extraAttributes(['class' => 'text-xs']),

                    TextColumn::make('location')
                        ->state(fn (Announcement $announcement) => $announcement->geo?->name)
                        ->badge()
                        ->color('gray'),

                    TextColumn::make('categories')
                        ->getStateUsing(fn (Announcement $announcement) => $announcement->categories?->pluck('name'))
                        ->badge(),

                    TextColumn::make('channels')
                        ->state(fn (Announcement $announcement) => view(
                            'livewire.components.tables.columns.telegram-channel-status',
                            [
                                'collection' => $announcement->channels->map(function ($channel) {
                                    $channel->color = $channel->status?->filamentColor();
                                    $channel->title = "{$channel->telegram_chat?->title}: {$channel->status?->getLabel()}";
                                    return $channel;
                                }),
                            ],
                        )),

                    StatusSwitcher::make('current_status')
                        ->options(Status::class)
                        ->grow(true)
                        ->updateStateUsing(fn (Announcement $announcement, string $state) => $announcement->updateStatus($state))
                        ->color(fn (Announcement $announcement) => $announcement->current_status->filamentColor()),

                    TextColumn::make('created_at')
                ])
            
            ->actions([
                RestoreAction::make()
                    ->hiddenLabel()
                    ->button()
                    ->visible($this->roleOrPermission(['restore', 'manage'], 'announcement')),
            ])
            ->filters($this->filters())
            ->bulkActions([
                ForceDeleteBulkAction::make()
                    ->visible($this->roleOrPermission(['force_delete', 'manage'], 'announcement')),
            ])
            ->persistFiltersInSession()
            ->paginationPageOptions([25, 50, 100])
            ->recordClasses(fn (Announcement $record) => "bg-{$record->current_status->color()}-50");
    }
}
