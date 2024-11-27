<?php 

namespace App\Bots\inzerko_bot\Commands\UserCommands;

use App\Bots\inzerko_bot\Commands\UserCommands\Profile\Profile;
use App\Bots\inzerko_bot\Facades\Inzerko;
use App\Bots\inzerko_bot\Notifications\TelegramEmailVerification;
use App\Models\User;
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

        Inzerko::returnInline([
            'chat_id' => $updates->getChat()->getId(),
            'text' => 'Отправление письма...',
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ]);

        $user->notify(new TelegramEmailVerification);

        Inzerko::answerCallbackQuery([
            'callback_query_id' => $updates->getCallbackQuery()->getId(),
            'text' => 'Письмо было отправлено. Пожалуйста, подтвердите свой e-mail, перейдя по ссылке на письме.',
            'show_alert' => true
        ]);

        $buttons = Inzerko::inlineKeyboard([
            [array(Profile::getTitle('ru'), Profile::$command, '')],
            [array(SendTelegramEmailVerificationNotification::getTitle('ru'), SendTelegramEmailVerificationNotification::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')]
        ]);

        $text = implode("\n", [
            "*Прежде чем продолжить, пожалуйста, подтвердите свой e-mail*"."\n",
            "На e-mail: *{$user->email}* было отправлено письмо для подтверждения. Пожалуйста, подтвердите свой e-mail, нажав на кнопку в письме."."\n",
            "_Письмо обычно приходит через несколько минут._"
        ]);

        return Inzerko::returnInline([
            'chat_id' => $updates->getChat()->getId(),
            'text' => $text,
            'parse_mode' =>  'Markdown',
            'reply_markup' => $buttons,
            'message_id' =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ]);
    }
}