<?php

namespace App\Livewire\Pages\Admin\Telegram;

use App\Livewire\Layouts\AdminTableLayout;
use App\Models\TelegramBot;
use App\Models\TelegramChat;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class Chats extends AdminTableLayout implements HasForms, HasTable
{
    public TelegramBot $telegram_bot;

    public function mount(TelegramBot $telegram_bot)
    {
        $this->telegram_bot = $telegram_bot;
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading("{$this->telegram_bot->first_name} chats")
            ->query(
                $this->telegram_bot
                    ->chats()
                    ->where('type', 'private')
                    ->with(['latestMessage'])
                    ->getQuery()
                )
            ->columns([
                TextColumn::make('id'),
                TextColumn::make('name')
                    ->label('Чат')
                    ->searchable(['first_name', 'last_name', 'username', 'title', 'chat_id'])
                    ->state(function (TelegramChat $telegram_chat) {
                        return "$telegram_chat->first_name $telegram_chat->last_name $telegram_chat->title";
                    })
                    ->description(fn (TelegramChat $telegram_chat) => "{$telegram_chat?->username} ({$telegram_chat?->chat_id})"),
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
