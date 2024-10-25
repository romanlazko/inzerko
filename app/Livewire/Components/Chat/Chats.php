<?php

namespace App\Livewire\Components\Chat;

use App\Models\Messanger\Message;
use App\Notifications\NewMessage;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Support\Enums\FontWeight;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;
use Livewire\Component;
use Illuminate\Support\Str;

class Chats extends Component implements HasForms, HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;

    public function table(Table $table): Table
    {
        return $table
            ->view('components.livewire.chat.chat-table')
            ->query(
                auth()->user()->threads()
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
                ])
            ])
            ->recordAction('answer')
            ->actions([
                Action::make('answer')
                    ->modalHeading(fn ($record) => new HtmlString(view('components.user.card', ['user' => $record->recipient])))
                    ->modalContent(function ($record) {
                        $record->messages()->where('user_id', '!=', auth()->id())->update(['read_at' => now()]);

                        return view('components.livewire.chat.show', ['messages' => $record->messages]);
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

                        $record->recipient->notify((new NewMessage($record))->delay(now()->addMinutes(3)));

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

    public function render()
    {
        return view('components.livewire.chat.index');
    }
}