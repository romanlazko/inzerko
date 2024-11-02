<?php

namespace App\Livewire\Pages\Admin\User;

use App\Enums\Status;
use App\Livewire\Layouts\AdminTableLayout;
use App\Models\TelegramChat;
use App\Models\User;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class Users extends AdminTableLayout implements HasForms, HasTable
{

    public function table(Table $table): Table
    {
        return $table
            ->query(User::withCount([
                'announcements as published_count' => fn ($query) => $query->status(Status::published),
                'announcements as await_moderation_count' => fn ($query) => $query->status(Status::await_moderation),
            ]))
            ->columns([
                TextColumn::make('id'),
                SpatieMediaLibraryImageColumn::make('image')
                    ->collection('avatar')
                    ->conversion('thumb')
                    ->rounded(),
                TextColumn::make('name')
                    ->description(fn (User $record) => $record?->email)
                    ->searchable(['name', 'email']),
                TextColumn::make('chat')
                    ->state(fn (User $user) => "{$user?->chat?->first_name} {$user?->chat?->last_name}")
                    ->description(fn (User $record) => "{$record?->chat?->username} ({$record?->chat?->chat_id})"),
                TextColumn::make('lang')
                    ->badge()
                    ->wrap(true),
                TextColumn::make('locale')
                    ->badge(),
                ToggleColumn::make('verified')
                    ->state(fn (User $user) => $user->hasVerifiedEmail())
                    ->updateStateUsing(function (User $user, $state) {
                        $state 
                            ? $user->markEmailAsVerified() 
                            : $user->forceFill([
                                'email_verified_at' => null,
                            ])->save();
                    }),
                TextColumn::make('published_count')
                    ->sortable(),
                TextColumn::make('await_moderation_count')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime(),
            ])
            ->actions([
                EditAction::make('edit')
                    ->form([
                        TextInput::make('name'),
                        TextInput::make('email')
                            ->email(),
                        TextInput::make('phone')
                            ->tel(),
                        Select::make('locale')
                            ->options(config('translate.languages')),
                        Select::make('telegram_chat_id')
                            ->options(TelegramChat::all()->map(fn (TelegramChat $telegram_chat) => [
                                'id' => $telegram_chat->id,
                                'username' => $telegram_chat->username ?? "{$telegram_chat->first_name} {$telegram_chat->last_name}",
                            ])) 
                            ->searchable()
                            ->unique(ignoreRecord: true),
                        TextInput::make('telegram_token')
                            ->hidden(fn (Get $get) => is_null($get('telegram_chat_id')))
                            ->suffixAction(
                                Action::make('generate')
                                    ->action(fn (Set $set) => $set('telegram_token', Str::random(8)))
                                    ->icon('heroicon-o-arrow-path')
                            )
                            ->required(),
                    ])
                    ->button()
                    ->hiddenLabel(),
                DeleteAction::make()
                    ->action(fn (User $record) => $record->remove())
                    ->button()
                    ->hiddenLabel(),
            ])
            ->recordAction('edit');
    }
}
