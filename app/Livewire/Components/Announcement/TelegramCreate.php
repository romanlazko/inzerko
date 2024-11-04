<?php

namespace App\Livewire\Components\Announcement;

use App\Bots\inzerko_bot\Facades\Inzerko;

class TelegramCreate extends Create
{
    public function afterCreating()
    {
        $this->dispatch('announcement-created');
        
        return Inzerko::sendMessage([
            'text' => 'Объявление успешно создано!',
            'chat_id' => auth()->user()->chat?->chat_id
        ]);
    }
}