<?php

namespace App\Bots\inzerko_bot\Http\Controllers;

use App\Bots\inzerko_bot\Facades\Inzerko;
use App\Http\Controllers\Controller;

class AnnouncementController extends Controller
{
    public function create()
    {
        $chat = auth()->user()->chat;

        try {
            Inzerko::editMessageReplyMarkup([
                'chat_id'       => $chat->chat_id,
                'message_id'    => $chat->latestMessage->message_id,
            ]);
        } catch (\Throwable $th) {
            //throw $th;
        }

        return response()
            ->view('profile.announcement.create')
            ->header('Cache-Control', 'private, max-age=0, must-revalidate');
    }
}
