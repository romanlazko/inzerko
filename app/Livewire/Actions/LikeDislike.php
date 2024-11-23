<?php

namespace App\Livewire\Actions;

use App\Models\Announcement;
use App\Models\Vote;
use Livewire\Component;

class LikeDislike extends Component
{
    public Announcement $announcement;
    public ?Vote $userVote = null;
    public int $lastUserVote = 0;

    public function mount(Announcement $announcement): void
    {
        $this->announcement = $announcement;
        $this->userVote = $announcement->votes->where('user_id', auth()->id())->first();
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

        $this->userVote = $this->announcement->votes()->updateOrCreate(
            ['user_id' => auth()->id()],
            ['vote' => $this->userVote?->vote ? false : true]
        );
    }
}