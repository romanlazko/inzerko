<?php

namespace App\Livewire\Components\Announcement;

class TelegramCreate extends Create
{
    public function afterCreating()
    {
        $this->dispatch('announcement-created');
    }
}