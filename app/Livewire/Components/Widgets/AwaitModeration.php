<?php

namespace App\Livewire\Components\Widgets;

use App\Enums\Status;
use App\Models\Announcement;
use Livewire\Component;

class AwaitModeration extends Component
{
    public function render()
    {
        $count = Announcement::where('current_status', Status::await_moderation)->count();

        return view('livewire.components.widgets.await-moderation', compact('count'));
    }
}
