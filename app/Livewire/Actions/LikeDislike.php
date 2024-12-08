<?php

namespace App\Livewire\Actions;

use App\Models\Vote;
use Livewire\Component;

class LikeDislike extends Component
{
    public $announcement_id;    
    public ?Vote $userVote = null;

    public function mount()
    {
        $this->userVote = auth()->user()?->votes
            ->where('announcement_id', $this->announcement_id)
            ->first();
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
            ['vote' => !($this->userVote?->vote ?? false)]
        );
    }
}