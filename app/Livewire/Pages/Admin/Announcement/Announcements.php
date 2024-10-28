<?php

namespace App\Livewire\Pages\Admin\Announcement;

use App\Enums\Status;
use App\Models\Announcement;
use App\Models\TelegramChat;
use App\Models\Category;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\ActionSize;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Forms\Components\Livewire;
use App\Livewire\Components\Tables\Columns\StatusSwitcher;
use App\Livewire\Layouts\AdminTableLayout;
use App\Models\Feature;
use Filament\Forms\Components\Select;
use Illuminate\Support\Facades\DB;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Filters\Filter;
use App\Models\AnnouncementChannel;
use Filament\Tables\Actions\EditAction;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\Repeater;
use App\Models\Attribute;
use App\Models\AttributeOption;
use Novadaemon\FilamentPrettyJson\PrettyJson;
use Filament\Forms\Get;
use App\Services\Actions\CategoryAttributeService;
use Filament\Forms\Components\KeyValue;
use Illuminate\Support\Facades\Cache;

class Announcements extends AdminTableLayout implements HasForms, HasTable
{
    public function table(Table $table): Table
    {
        return $table
            ->heading("All announcements")
            ->headerActions([
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
                    ->action(function (array $data) {
                        $categories = Category::find($data['category'])->getParentsAndSelf();

                        Announcement::factory($data['count'])->hasAttached($categories)->create();
                    }),
            ])
            ->query(Announcement::with([
                'media', 
                'user.media', 
                'categories', 
                'channels.telegram_chat', 
                'geo', 
                'features.attribute_option',
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
                            ->map(fn (Feature $feature) => "{$feature->label}: ". substr($feature->value, 0, 100))
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
                        ->state(fn (Announcement $announcement) => $announcement->geo->name)
                        ->badge()
                        ->color('gray'),

                    

                    TextColumn::make('categories')
                        ->getStateUsing(fn (Announcement $announcement) => $announcement->categories->pluck('name'))
                        ->badge(),

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
                    StatusSwitcher::make('current_status')
                        ->options(Status::class)
                        ->grow(true)
                        ->updateStateUsing(fn (Announcement $announcement, string $state) => $announcement->updateStatus($state))
                        ->color(fn (Announcement $announcement) => $announcement->current_status->filamentColor()),

                    TextColumn::make('created_at')
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
                        Action::make('history')
                            ->label(__("View history"))
                            ->form(fn (Announcement $announcement) => [
                                Livewire::make(Audits::class, ['announcement_id' => $announcement->id])
                            ])
                            ->hiddenLabel()
                            ->extraModalWindowAttributes(['style' => 'background-color: #e5e7eb'])
                            ->modalSubmitAction(false)
                            ->slideover()
                            ->modalWidth('7xl')
                            ->button()
                            ->icon('heroicon-o-clock'),
                        
                        Action::make('statuses')
                            ->label(__("View statuses"))
                            ->form(fn (Announcement $announcement) => [
                                Livewire::make(Statuses::class, ['announcement_id' => $announcement->id])
                            ])
                            ->extraModalWindowAttributes(['style' => 'background-color: #e5e7eb'])
                            ->icon('heroicon-m-square-3-stack-3d')
                            ->slideover()
                            ->modalWidth('7xl')
                            ->hiddenLabel()
                            ->color('info')
                            ->button(),

                        Action::make("Telegram Channels")
                            ->modalHeading(fn (Announcement $announcement) => "Telegram Channels: {$announcement->getFeatureByName('title')?->value}")
                            ->form(fn (Announcement $announcement) => [
                                Livewire::make(Channels::class, ['announcement_id' => $announcement->id]),
                            ])
                            ->extraModalWindowAttributes(['style' => 'background-color: #e5e7eb'])
                            ->icon('heroicon-o-megaphone')
                            ->slideover()
                            ->modalWidth('7xl')
                            ->hiddenLabel()
                            ->color('info')
                            ->button(),

                        DeleteAction::make('delete_announcement')
                            ->hiddenLabel()
                            ->button(),
                    
                    // ->size(ActionSize::ExtraSmall)
                    // ->dropdownPlacement('right-start')
                    // ->button()
                    // ->hiddenLabel()
            ])
            ->recordUrl(
                fn (Announcement $announcement) => route('admin.announcement.edit', [
                    'announcement' => $announcement
                ])
            )
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
            ->bulkActions([
                DeleteBulkAction::make()
                    ->modalHeading('Are you sure you want to delete the selected announcements?')
                    ->action(fn ($records) => $records->each->delete())
            ])
            ->persistFiltersInSession()
            // ->contentGrid([
            //     'md' => 2,
            //     'xl' => 3,
            // ])
            ->paginationPageOptions([25, 50, 100])
            ->recordClasses(fn (Announcement $record) => "bg-{$record->current_status->color()}-50")
            ->poll('2s');
    }
}
