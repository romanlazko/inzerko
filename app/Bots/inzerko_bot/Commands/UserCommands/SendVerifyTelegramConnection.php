<?php 

namespace App\Bots\inzerko_bot\Commands\UserCommands;

use App\Models\User;
use App\Notifications\TelegramEmailVerification;
use App\Notifications\VerifyTelegramConnection;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class SendVerifyTelegramConnection extends Command
{
    public static $command = 'svtc';

    public static $title = [
        'ru' => 'Отправить письмо снова',
        'en' => 'Send email again',
    ];

    public static $usage = ['svtc'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $telegram_chat      = DB::getChat($updates->getChat()->getId());
        $telegram_token     = $updates->getInlineData('telegram_token');
        $user               = User::firstWhere('telegram_token', $telegram_token);
        
        if ($user->notify(new VerifyTelegramConnection($telegram_chat->id))) {
            BotApi::answerCallbackQuery([
                'callback_query_id' => $updates->getCallbackQuery()->getId(),
                'text' => 'Письмо было отправлено. Пожалуйста, подтвердите свой e-mail, перейдя по ссылке на письме.',
                'show_alert' => true
            ]);
        }

        return BotApi::returnInline([
            'chat_id' => $updates->getChat()->getId(),
            'text' => "На ваш эмейл {$user->email} было отправлено письмо для подтверждения связи с ботом. Пожалуйста, подтвердите связь с ботом, нажав на кнопку в письме.",
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()?->getMessageId(),
        ]);
    }
}