<?php

namespace App\Livewire\Pages\Admin\User;

use App\Enums\Status;
use App\Livewire\Layouts\AdminTableLayout;
use App\Models\Contact;
use App\Models\TelegramChat;
use App\Models\User;
// use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;

use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Actions\Action;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms\Components\ToggleButtons;

class Users extends AdminTableLayout implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    
    public function table(Table $table): Table
    {
        return $table
            ->query(User::withCount([
                'announcements as published_count' => fn ($query) => $query->status(Status::published),
                'announcements as await_moderation_count' => fn ($query) => $query->status(Status::await_moderation),
            ]))
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('id')
                    ->sortable(),
                SpatieMediaLibraryImageColumn::make('image')
                    ->collection('avatar')
                    ->conversion('thumb')
                    ->rounded(),
                TextColumn::make('name')
                    ->description(fn (User $record) => $record?->email)
                    ->searchable(['name', 'email'])
                    ->sortable(),
                TextColumn::make('chat')
                    ->state(fn (User $user) => "{$user?->chat?->first_name} {$user?->chat?->last_name}")
                    ->description(fn (User $record) => "{$record?->chat?->username} ({$record?->chat?->chat_id})"),
                TextColumn::make('roles.name')
                    ->badge()
                    ->color('warning'),
                TextColumn::make('contacts.link')
                    ->state(fn (User $user) => $user?->contacts->pluck('url')->toArray())
                    ->badge()
                    ->copyable()
                    ->color('gray'),
                TextColumn::make('languages')
                    ->state(fn (User $user) => $user->languages)
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
                TextColumn::make('Ban')
                    ->color('danger')
                    ->state(fn (User $record) => $record?->latestBan ? __('profile.penalty.reasons.' . $record?->latestBan?->comment) : '')
                    ->description(fn (User $record) => $record?->latestBan?->expired_at?->format('d M y')),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->headerActions([
                Action::make('Archive')
                    ->icon('heroicon-o-archive-box-x-mark')
                    ->color('warning')
                    ->url(route('admin.users.archive')),
            ])
            ->actions([
                EditAction::make('edit')
                    ->form([
                        TextInput::make('name'),
                        TextInput::make('email')
                            ->email(),
                            
                        Select::make('roles')
                            ->relationship('roles', 'name')
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->required()
                            ->visible($this->roleOrPermission(['manage'], 'role')),

                        Select::make('locale')
                            ->options(config('translate.languages')),

                        Select::make('telegram_chat_id')
                            ->options(TelegramChat::all()->map(fn (TelegramChat $telegram_chat) => [
                                'id' => $telegram_chat->id,
                                'username' => $telegram_chat->username ?? "{$telegram_chat->first_name} {$telegram_chat->last_name}",
                            ])->pluck('username', 'id')) 
                            ->searchable()
                            ->unique(ignoreRecord: true),
                    ])
                    ->button()
                    ->hiddenLabel()
                    ->visible($this->roleOrPermission(['update', 'manage'], 'user'))
                    ->slideOver(),

                Action::make('ban')
                    ->color('warning')
                    ->form([
                        ToggleButtons::make('comment')
                            ->options([
                                'spam' => __('profile.penalty.reasons.spam'),
                                'bot' => __('profile.penalty.reasons.bot'),
                                'fraud' => __('profile.penalty.reasons.fraud'),
                                'abuse' => __('profile.penalty.reasons.abuse'),
                                'scam' => __('profile.penalty.reasons.scam'),
                                'illegal' => __('profile.penalty.reasons.illegal'),
                                'other' => __('profile.penalty.reasons.other'),
                            ])
                            ->inline()
                            ->required(),
                        TextInput::make('expired_at')
                            ->type('date'),
                    ])
                    ->icon('heroicon-s-lock-closed')
                    ->action(function (User $user, array $data) {
                        $user->ban([
                            'comment' => $data['comment'],
                            'expired_at' => $data['expired_at'],
                        ]);

                        $user->disableAnnouncements();
                    })
                    ->button()
                    ->hiddenLabel()
                    ->visible(fn (User $user) => $this->roleOrPermission(['manage'], 'user') AND $user->isNotBanned()),

                Action::make('unban')
                    ->color('success')
                    ->action(function (User $user, array $data) {
                        $user->unban();
                    })
                    ->icon('heroicon-s-lock-open')
                    ->button()
                    ->hiddenLabel()
                    ->visible(fn (User $user) => $this->roleOrPermission(['manage'], 'user') AND $user->isBanned()),

                DeleteAction::make()
                    ->button()
                    ->hiddenLabel()
                    ->visible($this->roleOrPermission(['delete', 'manage'], 'user')),
            ])
            ->recordAction('edit');
    }
}
