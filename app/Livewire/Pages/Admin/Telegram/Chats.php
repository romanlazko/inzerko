<?php

namespace App\Livewire\Pages\Admin\Telegram;

use App\Livewire\Pages\Layouts\AdminLayout;
use App\Models\Category;
use App\Models\Geo;
use App\Models\TelegramBot;
use App\Models\TelegramChat;
use Faker\Provider\ar_EG\Text;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class Chats extends AdminLayout implements HasForms, HasTable
{
    public TelegramBot $telegram_bot;

    public function mount(TelegramBot $telegram_bot)
    {
        $this->telegram_bot = $telegram_bot;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading("All Chats")
            ->headerActions([
                Action::make('back')
                    ->icon('heroicon-o-arrow-left-circle')
                    ->url(route('admin.telegram.bots')),
            ])
            ->query(
                $this->telegram_bot
                    ->chats()
                    ->where('type', 'private')
                    ->with(['latestMessage'])
                    ->getQuery()
                )
            ->columns([
                TextColumn::make('id')
                    ->description(fn (TelegramChat $record) => $record?->chat_id),
                TextColumn::make('name')
                    ->label('Чат')
                    ->searchable(['first_name', 'last_name', 'username', 'title'])
                    ->state(function (TelegramChat $telegram_chat) {
                        return "$telegram_chat->first_name $telegram_chat->last_name $telegram_chat->title";
                    })
                    ->description(fn (TelegramChat $telegram_chat) => $telegram_chat->username),
                TextColumn::make('type')
                    ->sortable()
                    ->badge(),
                TextColumn::make('role')
                    ->sortable()
                    ->badge(),
                TextColumn::make('latest_message')
                    ->label('Последнее сообщение')
                    ->state(fn (TelegramChat $telegram_chat) => $telegram_chat->latestMessage?->text)
                    ->limit(50)
                    ->wrap(),
                TextColumn::make('updated_at')
                    ->sortable()
                    ->label('Последняя активность')
                    ->dateTime(),

            ])
            ->actions([
                DeleteAction::make()
                    ->hiddenLabel()
                    ->button(),
            ]);
    }
}
