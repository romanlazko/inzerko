<?php 

namespace App\Bots\inzerko_bot\Commands\UserCommands;

use App\Models\User;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;
use Romanlazko\Telegram\Exceptions\TelegramException;

class ConnectCommand extends Command
{
    public static $command = 'connect_command';

    public static $title = '';

    public static $pattern = "/^(\/start\s)(connect)-(.{10})$/";

    protected $enabled = true;

    public $usage = ['connect_command'];

    public function execute(Update $updates): Response
    {
        preg_match(self::$pattern, $updates->getMessage()?->getCommand(), $matches);

        $telegram_chat = DB::getChat($updates->getChat()->getId());

        if (User::where('telegram_chat_id', $telegram_chat->id)->first()) {
            return $this->handleError("Этот телеграм аккаунт уже подключен к аккаунту на сайте.");
        }

        if (BotApi::getChat(['chat_id' => $updates->getChat()->getId()])->getResult()->getHasPrivateForwards()) {
            return $this->sendPrivacyInstructions($updates, $matches[3]);
        }

        return $this->bot->executeCommand(SendVerifyTelegramConnection::$command);
    }

    public function sendPrivacyInstructions(Update $updates, string $telegram_token)
    {
        try {
            $buttons = BotApi::inlineKeyboard([
                [array('Продолжить', self::$command, $telegram_token)],
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