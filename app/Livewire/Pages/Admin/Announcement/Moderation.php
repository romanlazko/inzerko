<?php

namespace App\Livewire\Pages\Admin\Announcement;

use App\Enums\Status;
use App\Livewire\Components\Tables\Columns\ImageGridColumn;
use App\Models\Announcement;
use App\Models\Category;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\ActionSize;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\Layout\Panel;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Spatie\LaravelMarkdown\MarkdownRenderer;
use Filament\Forms\Components\Livewire;
use App\Livewire\Components\Tables\Columns\StatusSwitcher;
use App\Livewire\Layouts\AdminTableLayout;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\EditAction;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Filters\Filter;

class Moderation extends AdminTableLayout implements HasForms, HasTable
{
    public function table(Table $table): Table
    {
        return $table
            ->heading("Moderation of announcements")
            ->query(Announcement::with([
                'media', 
                'user.media', 
                'categories', 
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
                        ->getStateUsing(fn (Announcement $announcement) => $announcement->categories->pluck('name'))
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
                    
                    ImageGridColumn::make('image')
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
                            'components.livewire.tables.columns.telegram-channel-status',
                            [
                                'collection' => $announcement->channels->map(function ($channel) {
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
                    ActionGroup::make([
                        Action::make('send_to_moderation')
                            ->label(__("Send to moderation"))
                            ->action(fn (Announcement $announcement) => $announcement->moderate())
                            ->color('info')
                            ->button()
                            ->icon('heroicon-m-arrow-right-circle')
                            ->size(ActionSize::ExtraSmall),
                    ])
                    ->dropdown(false)
                    ->visible(fn (Announcement $announcement) => 
                        $announcement->status?->isCreated()
                    ),

                    // MODERATION STATUS
                    ActionGroup::make([
                        Action::make('approve')
                            ->label(__("Approve"))
                            ->action(fn (Announcement $announcement) => $announcement->approve())
                            ->color('info')
                            ->button()
                            ->icon('heroicon-s-check-circle')
                            ->size(ActionSize::ExtraSmall),

                        Action::make('reject')
                            ->label(__("Reject"))
                            ->form([
                                Section::make()
                                    ->schema([
                                        Textarea::make('info')
                                            ->label(__("Reason"))
                                            ->required()
                                            ->rows(6),
                                    ])
                            ])
                            ->action(fn (array $data, Announcement $announcement) => $announcement->reject($data))
                            ->color('danger')
                            ->button()
                            ->icon('heroicon-c-no-symbol')
                            ->slideOver()
                            ->modalWidth('md')
                            ->extraModalWindowAttributes(['style' => 'background-color: #e5e7eb'])
                            ->size(ActionSize::ExtraSmall),
                    ])
                    ->dropdown(false)
                    ->visible(fn (Announcement $announcement) => 
                        $announcement->status?->isOnModeration()
                    ),

                    // TRANSLATE STATUS
                    ActionGroup::make([
                        Action::make('translate')
                            ->label(__("Translate"))
                            ->action(fn (Announcement $announcement) => $announcement->translate())
                            ->color('info')
                            ->button()
                            ->icon('heroicon-c-language')
                            ->size(ActionSize::ExtraSmall)
                            ->visible(fn (Announcement $announcement) => $announcement->status?->isApproved()),
                            
                        Action::make('publish_without_translating')
                            ->label(__("Publish without translating"))
                            ->action(fn (Announcement $announcement) => $announcement->publish())
                            ->color('warning')
                            ->button()
                            ->icon('heroicon-c-no-symbol')
                            ->size(ActionSize::ExtraSmall),

                        Action::make('stop_translating')
                            ->label(__("Stop"))
                            ->action(fn (Announcement $announcement) => $announcement->translationFailed())
                            ->color('danger')
                            ->button()
                            ->icon('heroicon-c-no-symbol')
                            ->size(ActionSize::ExtraSmall)
                            ->visible(fn (Announcement $announcement) => $announcement->status?->isAwaitTranslation()),
                        
                        Action::make('retry_translation')
                            ->label(__("Retry"))
                            ->action(fn (Announcement $announcement) => $announcement->translate())
                            ->color('warning')
                            ->button()
                            ->icon('heroicon-c-arrow-path-rounded-square')
                            ->size(ActionSize::ExtraSmall)
                            ->visible(fn (Announcement $announcement) => $announcement->status?->isTranslationFailed()),
                    ])
                    ->dropdown(false)
                    ->visible(fn (Announcement $announcement) => 
                        $announcement->status?->isApproved() OR $announcement->status?->isAwaitTranslation() OR $announcement->status?->isTranslationFailed()
                    ),

                    // PUBLICATION STATUS
                    ActionGroup::make([
                        Action::make('stop_publishing')
                            ->label(__("Stop"))
                            ->action(fn (Announcement $announcement) => $announcement->publishingFailed())
                            ->color('danger')
                            ->button()
                            ->icon('heroicon-c-no-symbol')
                            ->size(ActionSize::ExtraSmall)
                            ->visible(fn (Announcement $announcement) => 
                                $announcement->status?->isAwaitPublication()
                            ),

                        Action::make('retry_publication')
                            ->label(__("Retry"))
                            ->action(fn (Announcement $announcement) => $announcement->publish())
                            ->color('warning')
                            ->button()
                            ->icon('heroicon-c-arrow-path-rounded-square')
                            ->size(ActionSize::ExtraSmall)
                            ->visible(fn (Announcement $announcement) => 
                                $announcement->status?->isPublishingFailed()
                            ),
                    ])
                    ->dropdown(false),

                    // ActionGroup::make([
                        // Action::make('history')
                        //     ->label(__("View history"))
                        //     ->form(fn (Announcement $announcement) => [
                        //         Livewire::make(Audits::class, ['announcement_id' => $announcement->id])
                        //     ])
                        //     ->hiddenLabel()
                        //     ->extraModalWindowAttributes(['style' => 'background-color: #e5e7eb'])
                        //     ->modalSubmitAction(false)
                        //     ->slideover()
                        //     ->button()
                        //     ->icon('heroicon-o-clock'),

                        // DeleteAction::make()
                        //     ->hiddenLabel()
                        //     ->button(),
                    // ])
                    // ->size(ActionSize::ExtraSmall)
                    // ->dropdownPlacement('right-start')
                    // ->button()
                    // ->hiddenLabel()
                    
            ])
            ->filters([
                Filter::make('current_status')
                    ->form([
                        Select::make('current_status')
                            ->options(fn () => 
                                Announcement::select('current_status', DB::raw('count(id) as count'))
                                    ->groupBy('current_status')
                                    ->get()
                                    ->mapWithKeys(function ($announcement_status) {
                                        return [$announcement_status->current_status->value => Status::from($announcement_status->current_status->value)->getLabel() . ' (' . $announcement_status->count . ')'];
                                    })
                                    ->toArray()
                            ),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when($data['current_status'], fn ($query) => $query->where('current_status', $data['current_status']));
                    }),

                Filter::make('category')
                    ->form([
                        Select::make('category')
                            ->options(fn () => 
                                Category::all()
                                    ->loadCount('announcements')
                                    ->groupBy('parent.name')
                                    ->map
                                    ->mapWithKeys(fn ($category) => [$category->id => $category->name . ' (' . $category->announcements_count . ')'])
                                    ->toArray()
                            ),
                    ])
                    ->query(function ($query, array $data) {
                        return $query->when($data['category'], fn ($query) => $query->category(Category::find($data['category'])));
                    }),

                SelectFilter::make('user')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
            ])
            ->persistFiltersInSession()
            ->contentGrid([
                'md' => 2,
                'xl' => 3,
            ])
            ->paginationPageOptions([25, 50, 100])
            ->poll('2s');
    }
}
