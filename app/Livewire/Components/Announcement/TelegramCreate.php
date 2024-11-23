<?php

namespace App\Livewire\Components\Announcement;

use App\Bots\inzerko_bot\Facades\Inzerko;

class TelegramCreate extends Create
{
    public function afterCreating(): void
    {
        $this->dispatch('announcement-created');
        
        Inzerko::sendMessage([
            'text' => 'Объявление успешно создано!',
            'chat_id' => auth()->user()->chat?->chat_id
        ]);
    }
}