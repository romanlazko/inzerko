<?php

namespace App\Livewire\Pages\Admin\Telegram;

use App\Livewire\Layouts\AdminTableLayout;
use App\Models\Category;
use App\Models\Geo;
use App\Models\TelegramBot;
use App\Models\TelegramChat;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class Channels extends AdminTableLayout implements HasForms, HasTable
{
    public TelegramBot $telegram_bot;

    public function mount(TelegramBot $telegram_bot)
    {
        $this->telegram_bot = $telegram_bot;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading("{$this->telegram_bot->first_name} channels")
            ->query(
                $this->telegram_bot
                    ->chats()
                    ->where('type', 'channel')
                    ->getQuery()
                )
            ->columns([
                TextColumn::make('name')
                    ->label('Чат')
                    ->searchable(['first_name', 'last_name', 'username', 'title', 'chat_id'])
                    ->state(function (TelegramChat $telegram_chat) {
                        return "$telegram_chat->first_name $telegram_chat->last_name $telegram_chat->title";
                    })
                    ->description(fn (TelegramChat $telegram_chat) => $telegram_chat->username),
                TextColumn::make('location')
                    ->state(fn (TelegramChat $telegram_chat) => $telegram_chat->geo?->name),
                TextColumn::make('categories')
                    ->state(fn (TelegramChat $telegram_chat) => $telegram_chat->categories->pluck('name'))
                    ->badge(),
                TextColumn::make('updated_at')
                    ->sortable()
                    ->label('Последняя активность')
                    ->dateTime(),

            ])
            ->actions([
                DeleteAction::make()
                    ->hiddenLabel()
                    ->button(),
                EditAction::make('Edit Location')
                    ->modalHeading(fn (TelegramChat $telegram_chat) => "Edit Location: {$telegram_chat->title}")
                    ->form([
                        Section::make()
                            ->schema([
                                Select::make('country')
                                    ->label(__('Country'))
                                    ->options(Geo::select('name', 'country')->where('level', 'PCLI')->get()->pluck('name', 'country'))
                                    ->searchable()
                                    ->afterStateUpdated(function (Set $set) {
                                        $set('geo_id', null);
                                    })
                                    ->placeholder(__('Country'))
                                    ->default('CZ')
                                    ->dehydrated(false)
                                    ->live(),
                                Select::make('geo_id')
                                    ->label(__('City'))
                                    ->searchable()
                                    ->preload()
                                    ->options(fn (Get $get) => Geo::where('country', $get('country') ?? 'CZ')?->get()->pluck('name', 'id'))
                                    ->getSearchResultsUsing(function (string $search, Get $get) {
                                        return Geo::where('country', $get('country') ?? 'CZ')
                                            ->whereRaw('LOWER(alternames) LIKE ?', ['%' . mb_strtolower($search) . '%'])
                                            ->get()
                                            ->pluck('name', 'id');
                                    })
                                    ->afterStateHydrated(fn (Set $set, TelegramChat $telegram_chat) => $set('country', $telegram_chat->geo?->country))
                                    ->live()
                                    ->placeholder(__('City'))
                            ])
                            ->columns(2),
                        Section::make()
                            ->schema([
                                Select::make('categories')
                                    ->relationship('categories')
                                    ->multiple()
                                    ->options(Category::with('parent')->get()->groupBy('parent.name')->map->pluck('name', 'id')),
                            ])
                    ])
                    ->slideOver()
                    ->closeModalByClickingAway(false)
                    ->hiddenLabel()
                    ->button(),
            ]);
    }
}
