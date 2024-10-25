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
        $this->userVote = $announcement->userVotes;
        $this->lastUserVote = $this->userVote->vote ?? 0;
    }

    /**
     * @throws Throwable
     */
    public function like(): void
    {
        if ($this->validateAccess()) {
            if ($this->hasVoted(1)) {
                $this->updateVote(0);
                return;
            }
    
            $this->updateVote(1);
        }
    }

    /**
     * @throws Throwable
     */

    public function render()
    {
        return view('livewire.actions.like-dislike');
    }

    private function hasVoted(int $val): bool
    {
        return $this->userVote && $this->userVote->vote === $val;
    }

    private function updateVote(int $val): void
    {
        if ($this->userVote) {
            $this->announcement->votes()->update(['user_id' => auth()->id(), 'vote' => $val]);
        } else {
            $this->userVote = $this->announcement->votes()->create(['user_id' => auth()->id(), 'vote' => $val]);
        }

        $this->lastUserVote = $val;
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