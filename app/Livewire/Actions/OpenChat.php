<?php

namespace App\Livewire\Actions;

use App\Livewire\Components\Chat\Chats;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Action;
use Livewire\Attributes\On;
use Filament\Forms\Components\Livewire;
use Livewire\Component;

class OpenChat extends Component implements HasForms, HasActions
{
    use InteractsWithActions;
    use InteractsWithForms;

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
            ->form([
                Livewire::make(Chats::class)
            ])
            ->slideOver();
    }

    #[On('open-chat')]
    public function listenForOpenChat()
    {
        $this->mountAction('openChat');
    }

    public function render()
    {
        return view('livewire.actions.open-chat');
    }
}
