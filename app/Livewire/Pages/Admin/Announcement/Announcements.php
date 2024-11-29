<?php

namespace App\Livewire\Pages\Admin\Announcement;

use App\Enums\Status;
use App\Models\Announcement;
use App\Models\Category;
use App\Models\Feature;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use App\Livewire\Components\Tables\Columns\StatusSwitcher;
use App\Livewire\Layouts\AdminAnnouncementTableLayout;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\ToggleColumn;

class Announcements extends AdminAnnouncementTableLayout implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->heading("All announcements")
            ->headerActions([
                Action::make('Archive')
                    ->icon('heroicon-o-archive-box-x-mark')
                    ->color('warning')
                    ->url(route('admin.announcement.archive')),
                Action::make('Generate fake announcements')
                    ->form([
                        Select::make('count')
                            ->options([
                                1 => 1,
                                10 => 10,
                                30 => 30,
                                100 => 100,
                                1000 => 1000,
                                3000 => 3000,
                            ])
                            ->required()
                            ->label('Count of announcements')
                            ->default(100),
                        Select::make('category')
                            ->options(Category::all()->filter(fn ($category) => $category->children->isEmpty())->pluck('name', 'id'))
                            ->label('Category without children')
                            ->required()
                    ])
                    ->icon('heroicon-o-plus-circle')
                    ->action(function (array $data) {
                        Announcement::factory($data['count'])->for(Category::find($data['category']))->create();
                    })
                    ->visible($this->roleOrPermission(['create', 'manage'], 'announcement')),
            ])
            ->query(Announcement::with([
                'media',
                'category', 
                'channels.telegram_chat', 
                'geo', 
                'features.attribute_option',
                'features.attribute',
                'reports',
            ]))
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

                ToggleColumn::make('is_active'),

                StatusSwitcher::make('current_status')
                    ->options(Status::class)
                    ->grow(true)
                    ->updateStateUsing(fn (Announcement $announcement, string $state) => $announcement->updateStatus($state))
                    ->color(fn (Announcement $announcement) => $announcement->current_status->filamentColor()),

                TextColumn::make('created_at')
            ])
            
            ->actions([
                    // CREATE STATUS
                    $this->createStatusActions(),

                    // MODERATION STATUS
                    $this->moderationStatusActions(),

                    // TRANSLATE STATUS
                    $this->translateStatusActions(),

                    // PUBLICATION STATUS
                    $this->publicationStatusActions(),

                    // DEFAULT ACTIONS
                    $this->defaultActions(),
            ])
            ->recordUrl(
                fn (Announcement $announcement) => route('admin.announcement.edit', [
                    'announcement' => $announcement
                ])
            )
            ->filters($this->filters())
            ->bulkActions([
                DeleteBulkAction::make()
                    ->visible($this->roleOrPermission(['delete', 'manage'], 'announcement')),
            ])
            ->persistFiltersInSession()
            ->paginationPageOptions([25, 50, 100])
            ->recordClasses(fn (Announcement $record) => "bg-{$record->current_status->color()}-50")
            ->poll('2s');
    }
}
