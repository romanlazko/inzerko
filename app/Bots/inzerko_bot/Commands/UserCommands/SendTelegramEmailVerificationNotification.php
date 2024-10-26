<?php 

namespace App\Bots\inzerko_bot\Commands\UserCommands;

use App\Models\User;
use App\Notifications\TelegramEmailVerification;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class SendTelegramEmailVerificationNotification extends Command
{
    public static $command = 'stevn';

    public static $title = [
        'ru' => 'Отправить письмо снова',
        'en' => 'Send email again',
    ];

    public static $usage = ['stevn'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $telegram_chat_id = DB::getChat($updates->getChat()->getId())->id;
        
        $user = User::firstWhere('telegram_chat_id', $telegram_chat_id);

        $user->notify(new TelegramEmailVerification);

        $buttons = BotApi::inlineKeyboard([
            [array(Profile::getTitle('ru'), Profile::$command, '')],
            [array(SendTelegramEmailVerificationNotification::getTitle('ru'), SendTelegramEmailVerificationNotification::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')]
        ]);

        return BotApi::returnInline([
            'chat_id' => $updates->getChat()->getId(),
            'text' => implode("\n", [
                "*Прежде чем продолжить, пожалуйста, подтвердите свой e-mail*"."\n",
                "На e-mail: *{$user->email}* было отправлено письмо для подтверждения. Пожалуйста, подтвердите свой e-mail, нажав на кнопку в письме."
            ]),
            'parse_mode'    =>  'Markdown',
            'reply_markup'  => $buttons,
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ]);
    }
}