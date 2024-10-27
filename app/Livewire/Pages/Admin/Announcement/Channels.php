<?php

namespace App\Livewire\Pages\Admin\Announcement;

use App\Enums\Status;
use App\Livewire\Layouts\AdminTableLayout;
use App\Models\Announcement;
use App\Models\AnnouncementChannel;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use App\Models\TelegramChat;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\DeleteBulkAction;
use App\Livewire\Components\Tables\Columns\StatusSwitcher;
use Novadaemon\FilamentPrettyJson\PrettyJson;
use Filament\Tables\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;

use Filament\Forms\Components\Select;

class Channels extends AdminTableLayout implements HasForms, HasTable
{
    public Announcement $announcement;

    public function mount($announcement_id)
    {
        $this->announcement = Announcement::find($announcement_id);
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading("All channels")
            ->query($this->announcement->channels()->with('currentStatus')->getQuery())
            ->columns([
                TextColumn::make('telegram_chat.title')
                    ->sortable()
                    ->grow()
                    ->description(fn ($record) => $record->username),
                StatusSwitcher::make('current_status')
                    ->options(Status::class)
                    ->grow(true)
                    ->updateStateUsing(fn (AnnouncementChannel $announcement_channel, string $state) => $announcement_channel->updateStatus($state, [
                        'message' => auth()?->user()?->name . " changed channel status to {$state}",
                    ]))
                    ->color(fn (AnnouncementChannel $announcement_channel) => $announcement_channel->current_status?->filamentColor()),
                TextColumn::make('currentStatus.info.message')
                    ->action(fn (AnnouncementChannel $announcement_channel) =>
                        dump($announcement_channel->currentStatus?->info)
                    )
                    ->badge()
            ])
            ->actions([
                DeleteAction::make()
                    ->hiddenLabel()
                    ->button(),
                Action::make('retry')
                    ->action(function ($record) {
                        $record->publish();
                    })
                    ->button(),
            ])
            ->headerActions([
                CreateAction::make()
                    ->form([
                        Select::make('telegram_chat_id')
                            ->label('Channel')
                            ->options(TelegramChat::where('type', 'channel')->whereNotIn('id', $this->announcement->channels()->pluck('telegram_chat_id')->toArray())->get()->pluck('title', 'id')),
                    ])
                    ->action(function ($data) {
                        $this->announcement->channels()->create([
                            'telegram_chat_id' => $data['telegram_chat_id'],
                            'current_status' => Status::created,
                        ]);
                    })
                    ->extraModalWindowAttributes(['style' => 'background-color: #e5e7eb'])
                    ->slideover(),
            ])
            ->poll('2s');
    }
}
