<?php

namespace App\Livewire\Pages\Admin\User;

use App\Enums\Status;
use App\Livewire\Pages\Layouts\AdminLayout;
use App\Models\User;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class Users extends AdminLayout implements HasForms, HasTable
{

    public function table(Table $table): Table
    {
        return $table
            ->query(User::withCount([
                'announcements as published_count' => fn ($query) => $query->status(Status::published),
                'announcements as await_moderation_count' => fn ($query) => $query->status(Status::await_moderation),
            ]))
            ->columns([
                TextColumn::make('id')
                    ->description(fn (User $record) => $record?->telegram_chat->chat_id),
                SpatieMediaLibraryImageColumn::make('image')
                    ->collection('avatar')
                    ->conversion('thumb')
                    ->rounded(),
                TextColumn::make('name')
                    ->description(fn (User $record) => $record?->email),
                TextColumn::make('chat')
                    ->state(fn (User $user) => $user?->chat?->first_name . ' ' . $user?->chat?->last_name)
                    ->description(fn (User $record) => $record?->chat?->username),
                TextColumn::make('lang')
                    ->badge()
                    ->wrap(true),
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
            ->filters([
                //
            ])
            ->actions([
                EditAction::make(),
            ]);
    }
}
