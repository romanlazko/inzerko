<?php 

namespace App\Bots\inzerko_bot\Commands\UserCommands;

use App\Models\User;
use Illuminate\Support\Facades\URL;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class EmailNotVerified extends Command
{
    public static $command = 'email_not_verified';

    public static $title = [
        'en' => 'Email not verified',
        'ru' => 'Почта не подтверждена',
    ];

    public static $usage = ['email_not_verified'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = BotApi::inlineKeyboard([
            [array(EditProfile::getTitle('ru'), EditProfile::$command, '')],
            [array(SendTelegramEmailVerificationNotification::getTitle('ru'), SendTelegramEmailVerificationNotification::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')]
        ]);

        $data = [
            'text'          => "Вы не подтвердили свою почту. Пожалуйста, подтвердите свою почту, перейдя по ссылке на эмейле.",
            'chat_id'       => $updates->getChat()->getId(),
            'parse_mode'    => "Markdown",
            'reply_markup'  => $buttons,
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()?->getMessageId(),
        ];

        return BotApi::returnInline($data);
    }
}
