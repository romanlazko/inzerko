<?php 

namespace App\Bots\inzerko_bot\Commands\UserCommands\Profile;

use App\Bots\inzerko_bot\Commands\UserCommands\MenuCommand;
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

        $saveProfileCommand = $user ? UpdateProfile::class : StoreProfile::class;

        $buttons = BotApi::inlineKeyboard([
            [array($notes['email'] ?? $user?->email ?? 'Email:', Email::$command, '')],
            [array($notes['phone'] ?? $user?->phone ?? 'Phone:', Phone::$command, '')],
            [array($saveProfileCommand::getTitle('ru'), $saveProfileCommand::$command, '')],
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
