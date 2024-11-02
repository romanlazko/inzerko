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
use Romanlazko\Telegram\Exceptions\TelegramException;

class SendVerifyTelegramConnection extends Command
{
    public static $command = 'svtc';

    public static $title = [
        'ru' => 'Отправить письмо для подтверждения связи с ботом',
        'en' => 'Send email again',
    ];

    public static $usage = ['svtc'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $telegram_chat      = DB::getChat($updates->getChat()->getId());
        $telegram_token     = $updates->getInlineData()->getTelegramToken();
        $user               = User::firstWhere('telegram_token', $telegram_token);

        if (BotApi::getChat(['chat_id' => $updates->getChat()->getId()])->getResult()->getHasPrivateForwards()) {
            return $this->sendPrivacyInstructions($updates, $telegram_token);
        }
        
        if ($user->notify(new VerifyTelegramConnection($telegram_chat->id))) {
            BotApi::answerCallbackQuery([
                'callback_query_id' => $updates->getCallbackQuery()->getId(),
                'text' => 'Письмо было отправлено. Пожалуйста, подтвердите свой e-mail, перейдя по ссылке на письме.',
                'show_alert' => true
            ]);
        }

        return BotApi::returnInline([
            'chat_id' => $updates->getChat()->getId(),
            'text' => "На e-mail: *{$user->email}* было отправлено письмо для подтверждения связи с ботом. Пожалуйста, подтвердите связь с ботом, нажав на кнопку в письме.",
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()?->getMessageId(),
        ]);
    }

    public function sendPrivacyInstructions(Update $updates, string $telegram_token)
    {
        try {
            $buttons = BotApi::inlineKeyboard([
                [array('Продолжить', SendVerifyTelegramConnection::$command, $telegram_token)],
            ], 'telegram_token');

            $text = implode("\n", [
                "*Ой ой*"."\n",
                "Мы не можем подтвердить связь поскольку твои настройки конфиденциальности не позволяют нам сослаться на тебя."."\n",
                "Сделай все как указанно в [инструкции](https://telegra.ph/Kak-razreshit-peresylku-soobshchenij-12-27) что бы исправить это."."\n",
                "*Настройки конфиденциальности вступят в силу в течении 5-ти минут, после этого нажми на кнопку «Продолжить»*",
            ]);

            return BotApi::returnInline([
                'text'          => $text,
                'reply_markup'  => $buttons,
                'chat_id'       => $updates->getChat()->getId(),
                'parse_mode'    => "Markdown",
                'message_id'    =>  $updates->getCallbackQuery()?->getMessage()?->getMessageId(),
            ]);
        }
        catch(TelegramException $e){
            return BotApi::answerCallbackQuery([
                'callback_query_id' => $updates->getCallbackQuery()->getId(),
                'text'              => "Настройки еще не вступили в силу. Подождите 5 минут после изменения настроек и попробуйте еще раз.",
                'show_alert'        => true
            ]);
        }
    }
}