<?php

namespace App\Livewire\Actions;

use App\Models\Announcement;
use App\Models\Vote;
use Livewire\Component;
use Filament\Actions\Contracts\HasActions;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Actions\Action;
use Filament\Actions\Concerns\InteractsWithActions;

class LikeDislike extends Component
{
    public $announcement_id;
    public ?Vote $userVote = null;

    public function mount(): void
    {
        $this->userVote = auth()->user()?->votes->where('announcement_id', $this->announcement_id)->first();
    }

    public function render()
    {
        return view('livewire.actions.like-dislike');
    }

    public function like()
    {
        if (auth()->guest()) {
            return $this->redirectIntended(route('login'));
        }

        $this->userVote = auth()->user()->votes()->updateOrCreate(
            ['announcement_id' => $this->announcement_id],
            ['vote' => $this->userVote?->vote ? false : true]
        );
    }
}

// class LikeDislike extends Component implements HasForms, HasActions
// {
//     use InteractsWithActions;
//     use InteractsWithForms;

//     public $announcement_id;
//     public $lastUserVote;

//     public function render()
//     {
//         $actions = [
//             'likeDislike',
//         ];

//         return view('livewire.layouts.actions', compact('actions'));
//     }

//     public function likeDislike()
//     {
//         $userVote = auth()->user()?->votes->where('announcement_id', $this->announcement_id)->first();

//         $action = Action::make('likeDislike')
//             ->hiddenLabel()
//             ->icon($userVote?->vote ? 'heroicon-s-heart' : 'heroicon-o-heart')
//             ->color('danger')
//             ->link()
//             ->extraAttributes([
//                 'class' => 'w-full',
//             ])
//             ->modalWidth('xl');

//         if (auth()->guest()) {
//             return $action
//                 ->requiresConfirmation()
//                 ->modalHeading(__('livewire.should_be_loggined'))
//                 ->modalDescription('')
//                 ->extraModalFooterActions([
//                     Action::make('login')
//                         ->label(__('livewire.login'))
//                         ->color('primary')
//                         ->action(fn () => redirect(route('login')))
//                 ])
//                 ->modalSubmitAction(false)
//                 ->modalCancelAction(false);
//         }

//         return $action
//             ->action(function (Action $action) use ($userVote) {
//                 $lastUserVote = auth()->user()->votes()->updateOrCreate(
//                     ['announcement_id' => $this->announcement_id],
//                     ['vote' => $userVote?->vote ? false : true]
//                 );

//                 $action->icon($lastUserVote?->vote ? 'heroicon-s-heart' : 'heroicon-o-heart');
//             });
//     }
// }