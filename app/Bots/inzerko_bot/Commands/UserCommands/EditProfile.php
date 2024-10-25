<?php 

namespace App\Bots\inzerko_bot\Commands\UserCommands;

use App\Models\User;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class EditProfile extends Command
{
    public static $command = 'edit-profile';

    public static $title = [
        'en' => 'Edit profile',
        'ru' => 'Редактировать профиль'
    ];

    public static $usage = ['edit-profile'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $telegram_chat = DB::getChat($updates->getChat()->getId());

        $notes = $this->getConversation()->notes;

        $profile = User::firstWhere('telegram_chat_id', $telegram_chat->id);

        $buttons = BotApi::inlineKeyboard([
            [array($notes['email'] ?? $profile->email ?? 'Email:', Email::$command, '')],
            [array($notes['phone'] ?? $profile->phone ?? 'Phone:', Phone::$command, '')],
            [array(SaveProfile::getTitle('ru'), SaveProfile::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')]
        ]);

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
