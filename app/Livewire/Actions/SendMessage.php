<?php

namespace App\Livewire\Actions;

use App\Models\Announcement;
use App\Models\Report;
use App\Models\User;
use App\Notifications\NewMessage;
use Livewire\Component;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Illuminate\Support\HtmlString;

class SendMessage extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

    public $announcement_id;

    public $user_id;

    public function render()
    {
        $actions = [
            'sendMessage',
            'showContact',
        ];

        return view('livewire.layouts.actions', compact('actions'));
    }

    public function showContact()
    {
        $user = User::findOrFail($this->user_id);

        return Action::make('showContact')
            ->label(__('livewire.show_contact'))
            ->modalHeading(fn () => new HtmlString(view('components.user.card', ['user' => $user])))
            ->icon('heroicon-s-phone')
            ->extraAttributes(['class' => 'w-full'])
            ->button()
            ->color('primary')
            ->modalWidth('xl')
            ->stickyModalHeader(true)
            ->modalSubmitAction(false)
            ->modalCancelAction(false)
            ->modalContent(view('components.user.contacts', ['user' => $user]));
    }

    public function sendMessage()
    {
        if (! $announcement = Announcement::find($this->announcement_id)) {
            return Action::make('sendMessage')
                ->hidden()
                ->extraAttributes([
                    'class' => 'hidden',
                ]);
        }

        $action = Action::make('sendMessage')
            ->label(__('livewire.send_message'))
            ->icon('heroicon-s-chat-bubble-bottom-center')
            ->outlined()
            ->extraAttributes(['class' => 'w-full'])
            ->button()
            ->modalWidth('xl');

        if (auth()->guest()) {
            return $action
                ->requiresConfirmation()
                ->modalHeading(__('livewire.should_be_loggined'))
                ->modalDescription('')
                ->extraModalFooterActions([
                    Action::make('login')
                        ->label(__('livewire.login'))
                        ->color('primary')
                        ->action(fn () => redirect(route('login')))
                ])
                ->modalSubmitAction(false)
                ->modalCancelAction(false);
        }

        if ($announcement->user->id == auth()->id()) {
            return $action
                ->modalHeading(__('livewire.you_cant_send_message_to_yourself'))
                ->requiresConfirmation()
                ->modalDescription('')
                ->modalSubmitAction(false);
        }

        return $action
            ->modalHeading(false)
            ->form([
                Textarea::make('message')
                    ->required()
                    ->hiddenLabel()
                    ->placeholder(__('livewire.write_a_message'))
                    ->rows(6),
            ])
            ->action(function (array $data) use ($announcement) {
                $thread = auth()->user()->threads()->where('announcement_id', $announcement->id)->first();

                if (!$thread) {
                    $thread = auth()->user()->threads()->create([
                        'announcement_id' => $announcement->id,
                    ]);

                    $thread->users()->attach([$announcement->user->id]);
                }

                $thread->messages()->create([
                    'user_id' => auth()->id(),
                    'message' => $data['message'],
                ]);
        
                $thread->recipient->notify((new NewMessage($thread))->delay(now()->addMinutes(10)));
            });
    }
}
