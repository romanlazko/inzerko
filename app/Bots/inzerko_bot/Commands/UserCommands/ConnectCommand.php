<?php 

namespace App\Bots\inzerko_bot\Commands\UserCommands;

use App\Models\User;
use App\Notifications\VerifyTelegramConnection;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;
use Romanlazko\Telegram\Exceptions\TelegramException;
use Romanlazko\Telegram\Exceptions\TelegramUserException;

class ConnectCommand extends Command
{
    public static $command = 'connect_command';

    public static $title = '';

    public static $pattern = "/^(\/start\s)(connect)-(.{10})$/";

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        preg_match(self::$pattern, $updates->getMessage()?->getCommand(), $matches);

        $telegram_chat = DB::getChat($updates->getChat()->getId());

        if (User::where('telegram_chat_id', $telegram_chat->id)->first()) {
            return $this->handleError("Этот телеграм аккаунт уже подключен к аккаунту на сайте.");
        }

        if (BotApi::getChat(['chat_id' => $updates->getChat()->getId()])->getResult()->getHasPrivateForwards()) {
            return $this->sendPrivacyInstructions($updates);
        }

        $user = User::firstWhere('telegram_token', $matches[3]);
        
        $user->notify(new VerifyTelegramConnection($telegram_chat->id));

        return BotApi::returnInline([
            'chat_id' => $updates->getChat()->getId(),
            'text' => "На ваш эмейл {$user->email} было отправлено письмо для подтверждения связи с ботом. Пожалуйста, подтвердите связь с ботом, нажав на кнопку в письме.",
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ]);
    }

    public function sendPrivacyInstructions(Update $updates)
    {
        try {
            $buttons = BotApi::inlineKeyboard([
                [array('Продолжить', self::$command, '')],
            ]);

            $text = implode("\n", [
                "*Ой ой*"."\n",
                "Мы не можем подтвердить связь поскольку твои настройки конфиденциальности не позволяют нам сослаться на тебя."."\n",
                "Сделай все как указанно в [инструкции](https://telegra.ph/Kak-razreshit-peresylku-soobshchenij-12-27) что бы исправить это."."\n",
                "*Настройки конфиденциальности вступят в силу в течении 5-ти минут, после этого нажми на кнопку «Продолжить»*",
            ]);

            return BotApi::editMessageText([
                'text'          => $text,
                'reply_markup'  => $buttons,
                'chat_id'       => $updates->getChat()->getId(),
                'message_id'    => $updates->getCallbackQuery()->getMessage()->getMessageId(),
                'parse_mode'    => "Markdown",
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