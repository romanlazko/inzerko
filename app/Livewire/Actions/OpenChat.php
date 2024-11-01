<?php

namespace App\Livewire\Actions;

use App\Livewire\Components\Chats;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Action;
use Livewire\Attributes\On;
use App\Models\Messanger\Message;
use App\Notifications\NewMessage;
use Filament\Forms\Components\Textarea;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\Action as ActionsAction;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use Illuminate\Support\Str;

class OpenChat extends Component implements HasForms, HasActions, HasTable
{
    use InteractsWithTable;
    use InteractsWithActions;
    use InteractsWithForms;

    public function render()
    {
        return view('livewire.actions.open-chat');
    }

    public function table(Table $table): Table
    {
        return $table
            ->view('livewire.components.tables.chat-table')
            ->query(
                auth()->user()?->threads()
                    ?->with(['announcement.media', 'announcement.features' => fn ($query) => $query->forAnnouncementCard(), 'users', 'latestMessage'])
                    ->withCount(['messages as uread_messages_count' => function ($query) {
                        $query->where('read_at', null)->where('user_id', '!=', auth()->id());
                    }])
                    ->whereHas('announcement')
                    ->whereHas('users')
                    ->orderBy(
                        Message::select('created_at')
                            ->whereColumn('thread_id', 'threads.id')
                            ->latest()
                            ->take(1),
                        'desc'
                    )
                    ->getQuery()
            )
            ->emptyStateHeading(__('pagination.nothing'))
            ->paginated(false)
            ->columns([
                Split::make([
                    SpatieMediaLibraryImageColumn::make('announcement.media')
                        ->collection('announcements', 'thumb')
                        ->label(false)
                        ->grow(false)
                        ->circular()
                        ->limit(1)
                        ->extraAttributes(['class' => 'border rounded-full']),
                    TextColumn::make('user')
                        ->state(fn ($record) => "{$record->recipient?->name} - {$record->announcement->title}")
                        ->description(fn ($record) => Str::limit($record->latestMessage->message, 30))
                        ->label(false)
                        ->lineClamp(2)
                        ->searchable(query: function ($query, string $search) {
                            return $query->whereHas('users', fn ($query) =>
                                $query->where(fn ($query) => 
                                    $query->whereRaw('LOWER(name) LIKE ?', ['%' . mb_strtolower($search) . '%'])
                                )
                            );
                        })
                        ->grow()
                        ->weight(fn ($record) => $record->uread_messages_count > 0 ? FontWeight::Bold : FontWeight::Medium)
                        ->extraAttributes(['class' => 'text-xs w-full']),
                    TextColumn::make('unread_messages_count')
                        ->badge()
                        ->grow(false)
                        ->color('danger')
                        ->hidden(fn ($record) => $record?->uread_messages_count === 0)
                        ->state(fn ($record) => $record?->uread_messages_count)
                ])
            ])
            ->recordAction('answer')
            ->actions([
                ActionsAction::make('answer')
                    ->modalHeading(fn ($record) => new HtmlString(view('components.user.card', ['user' => $record->recipient])))
                    ->modalContent(function ($record) {
                        $record->messages()->where('user_id', '!=', auth()->id())->update(['read_at' => now()]);

                        cookie()->queue(cookie()->forget('unreadMessagesCount'));

                        return view('components.messages', ['messages' => $record->messages, 'user_id' => auth()->id()]);
                    })
                    ->modalAutofocus(true)
                    ->form([
                        Textarea::make('message')
                            ->required()
                            ->rows(1)
                            ->autosize()
                            ->placeholder(__('livewire.write_a_message'))
                            ->hiddenLabel()
                            ->autofocus(),
                    ])
                    ->modalCancelAction(false)
                    ->slideOver()
                    ->modalWidth('md')
                    ->color('danger')
                    ->action(function ($data, $record, $form, $action) {
                        $record->messages()->create([
                            'user_id' => auth()->id(),
                            'message' => $data['message'],
                        ]);

                        $record->recipient->notify(new NewMessage($record));

                        $this->dispatch('scroll-to-bottom');

                        $form->fill();

                        $action->halt();
                    })
                    ->modalSubmitAction(fn ($action) => 
                        $action->color('primary')
                    )
                    ->label(fn ($record) => $record->uread_messages_count)
                    ->badge(),
            ]);
    }

    public function openChatAction()
    {
        $action = Action::make('openChat')
            ->modalSubmitAction(false)
            ->modalCancelAction(false)
            ->label(__('livewire.messages'))
            ->modalWidth('md');

        if (! auth()->user()) {
            return $action
                ->action(fn () => redirect(route('login')));
        }

        return $action
            ->modalContent($this->table)
            ->slideOver();
    }

    #[On('open-chat')]
    public function listenForOpenChat()
    {
        $this->mountAction('openChat');
    }
}
