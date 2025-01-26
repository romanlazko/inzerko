<?php

namespace App\Bots\inzerko_bot\Http\Controllers;

use App\Bots\inzerko_bot\Commands\UserCommands\CreateAnnouncement;
use App\Bots\inzerko_bot\Commands\UserCommands\Profile\Profile;
use App\Bots\inzerko_bot\Facades\Inzerko;
use App\Http\Controllers\Controller;

class AnnouncementController extends Controller
{
    public function create()
    {
        $chat = auth()->user()->chat;

        try {
            $buttons = Inzerko::inlineKeyboard([
                [array(CreateAnnouncement::getTitle('ru'), CreateAnnouncement::$command, '')],
                [array(Profile::getTitle('ru'), Profile::$command, '')],
            ]);

            $text = implode("\n", [
                "Привет 👋" ."\n", 
                "Я помогу тебе создать объявление в каналах *Pozor*."."\n",
            ]);

            return Inzerko::returnInline([
                'text'          => $text,
                'chat_id'       => $chat->chat_id,
                'reply_markup'  => $buttons,
                'parse_mode'    => 'Markdown',
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
