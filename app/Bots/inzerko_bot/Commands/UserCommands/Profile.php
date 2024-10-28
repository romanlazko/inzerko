<?php 

namespace App\Bots\inzerko_bot\Commands\UserCommands;

use App\Models\User;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Profile extends Command
{
    public static $command = 'edit-profile';

    public static $title = [
        'en' => 'Profile',
        'ru' => 'Профиль'
    ];

    public static $usage = ['edit-profile'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $telegram_chat = DB::getChat($updates->getChat()->getId());

        $notes = $this->getConversation()->notes;

        $user = User::firstWhere('telegram_chat_id', $telegram_chat->id);

        $buttons = [
            [array($notes['email'] ?? $user?->email ?? 'Email:', Email::$command, '')],
            [array($notes['phone'] ?? $user?->phone ?? 'Phone:', Phone::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')]
        ];

        if (!empty($notes)) {
            $buttons[] = [
                $user 
                    ? [array(UpdateProfile::getTitle('ru'), UpdateProfile::$command, '')]
                    : [array(StoreProfile::getTitle('ru'), StoreProfile::$command, '')]
            ];
        }

        $buttons = BotApi::inlineKeyboard($buttons);

        $text = implode("\n", [
            "*Ваш профиль:*"."\n",
        ]);

        return BotApi::returnInline([
            'text'          =>  $text,
            'chat_id'       =>  $updates->getChat()->getId(),
            'reply_markup'  =>  $buttons,
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()?->getMessageId(),
        ]);
    }
}
