<?php

namespace App\Livewire\Pages\Admin\Announcement;

use App\Enums\Status;
use App\Livewire\Components\Tables\Columns\ImageGridColumn;
use App\Models\Announcement;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Spatie\LaravelMarkdown\MarkdownRenderer;
use App\Livewire\Components\Tables\Columns\StatusSwitcher;
use App\Livewire\Layouts\AdminAnnouncementTableLayout;

class Moderation extends AdminAnnouncementTableLayout implements HasForms, HasTable
{
    public function table(Table $table): Table
    {
        return $table
            ->heading("Moderation of announcements")
            ->query(Announcement::with([
                'media', 
                'user.media', 
                'category', 
                'channels.telegram_chat', 
                'geo', 
                'features.attribute_option',
                'features' => fn ($query) => $query->forModerationCard(),
            ]))
            ->columns([
                Stack::make([
                    StatusSwitcher::make('current_status')
                        ->options(Status::class)
                        ->color(fn (Announcement $announcement) => $announcement->current_status->filamentColor()),

                    TextColumn::make('categories')
                        ->getStateUsing(fn (Announcement $announcement) => $announcement->categories?->pluck('name'))
                        ->badge(),
                    
                    Panel::make([
                        Split::make([
                            SpatieMediaLibraryImageColumn::make('user.avatar')
                                ->collection('avatar', 'thumb')
                                ->grow(false)
                                ->circular()
                                ->extraAttributes(['class' => 'border rounded-full']),
                            TextColumn::make('user.name')
                                ->description(fn (Announcement $announcement) => $announcement->user?->email)
                                ->extraAttributes(['class' => 'text-xs'])
                        ])
                    ])
                    ->extraAttributes(['style' => 'padding: 0.5rem;']),
                    
                    SpatieMediaLibraryImageColumn::make('image')
                        ->view('livewire.components.tables.columns.image-grid-column')
                        ->collection('announcements')
                        ->height(200)
                        ->extraAttributes(['class' => 'w-full']),
                    
                    TextColumn::make('Title')
                        ->wrap()
                        ->weight(FontWeight::Bold)
                        ->state(fn (Announcement $announcement) => $announcement?->title)
                        ->description(fn (Announcement $announcement) => 
                            new HtmlString(
                                app(MarkdownRenderer::class)->toHtml($announcement?->description ?? '')
                            )
                        )
                        ->extraAttributes([
                            'class' => 'html text-xs'
                        ]),

                    TextColumn::make('location')
                        ->state(fn (Announcement $announcement) => $announcement->geo->name)
                        ->badge()
                        ->color('gray'),

                    TextColumn::make('channels')
                        ->state(fn (Announcement $announcement) => view(
                            'livewire.components.tables.columns.telegram-channel-status',
                            [
                                'collection' => $announcement->channels?->map(function ($channel) {
                                    $channel->color = $channel->status?->filamentColor();
                                    $channel->title = "{$channel->telegram_chat?->title}: {$channel->status?->getLabel()}";
                                    return $channel;
                                }),
                            ],
                        )),
                ])
                ->extraAttributes(['class' => 'space-y-2'])
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
            ])
            ->filters($this->filters())
            ->persistFiltersInSession()
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->paginationPageOptions([25, 50, 100])
            ->poll('2s');
    }
}
