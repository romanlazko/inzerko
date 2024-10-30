<?php

namespace App\Livewire\Actions;

use App\Models\Announcement;
use App\Models\Vote;
use Livewire\Component;

class LikeDislike extends Component
{
    public Announcement $announcement;
    public ?Vote $userVote = null;
    public int $likes = 0;
    public int $dislikes = 0;
    public int $lastUserVote = 0;

    public function mount(Announcement $announcement): void
    {
        $this->announcement = $announcement;
        $this->userVote = $announcement->votes->where('user_id', auth()->id())->first();
        $this->lastUserVote = $this->userVote?->vote ?? 0;
    }

    /**
     * @throws Throwable
     */
    public function like(): void
    {
        if ($this->validateAccess()) {
            $this->userVote = $this->announcement->votes()->updateOrCreate(
                ['user_id' => auth()->id()],
                ['vote' => $this->hasVoted() ? false : true]
            );
        }
    }

    /**
     * @throws Throwable
     */

    public function render()
    {
        return view('livewire.actions.like-dislike');
    }

    private function hasVoted(): bool
    {
        return $this->userVote?->vote == true;
    }

    /**
     * @throws Throwable
     */
    private function validateAccess()
    {
        if (auth()->guest()) {
            $this->redirectIntended('login');
            return false;
        }
        return true;
    }
}