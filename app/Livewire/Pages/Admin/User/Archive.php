<?php

namespace App\Livewire\Pages\Admin\User;

use App\Enums\Status;
use App\Livewire\Layouts\AdminTableLayout;
use App\Models\User;
use Filament\Tables\Actions\ForceDeleteBulkAction;
use Filament\Tables\Actions\RestoreAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;

class Archive extends AdminTableLayout implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    
    public function table(Table $table): Table
    {
        return $table
            ->query(User::withCount([
                    'announcements as published_count' => fn ($query) => $query->status(Status::published)->withTrashed(),
                    'announcements as await_moderation_count' => fn ($query) => $query->status(Status::await_moderation)->withTrashed(),
                ])
                ->onlyTrashed()
            )
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
                TextColumn::make('roles.name')
                    ->badge()
                    ->color('warning'),
                TextColumn::make('communication.phone')
                    ->state(fn (User $user) => collect((array) $user->communication)->map(fn ($communication) => $communication->phone))
                    ->badge()
                    ->color('gray'),
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
                RestoreAction::make()
                    ->hiddenLabel()
                    ->button()
                    ->visible($this->roleOrPermission(['restore', 'manage'], 'user')),
            ])
            ->bulkActions([
                ForceDeleteBulkAction::make()
                    ->visible($this->roleOrPermission(['force_delete', 'manage'], 'user')),
            ])
            ->headerActions([
                Action::make('back')
                    ->url(route('admin.users.users'))
                    ->icon('heroicon-o-arrow-left')
                    ->button()
            ]);
    }
}
