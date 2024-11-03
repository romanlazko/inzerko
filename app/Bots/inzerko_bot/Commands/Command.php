<?php 

namespace App\Bots\inzerko_bot\Commands;

use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command as CommandsCommand;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\Exceptions\TelegramException;

abstract class Command extends CommandsCommand
{
    protected function hasPrivateForwards(): bool
    {
        return BotApi::getChat(['chat_id' => $this->updates->getChat()->getId()])->getResult()->getHasPrivateForwards();
    }

    protected function sendPrivacyInstructions(array $buttons = []): Response
    {
        try {
            $text = implode("\n", [
                "*Ой ой*"."\n",
                "Мы не можем подтвердить связь поскольку твои настройки конфиденциальности не позволяют нам сослаться на тебя."."\n",
                "Сделай все как указанно в [инструкции](https://telegra.ph/Kak-razreshit-peresylku-soobshchenij-12-27) что бы исправить это."."\n",
                "*Настройки конфиденциальности вступят в силу в течении 5-ти минут, после этого нажми на кнопку «Продолжить»*",
            ]);

            return BotApi::returnInline([
                'text'          => $text,
                'reply_markup'  => $buttons,
                'chat_id'       => $this->updates->getChat()->getId(),
                'parse_mode'    => "Markdown",
                'message_id'    => $this->updates->getCallbackQuery()?->getMessage()?->getMessageId(),
            ]);
        }
        catch(TelegramException $e){
            return BotApi::answerCallbackQuery([
                'callback_query_id' => $this->updates->getCallbackQuery()->getId(),
                'text'              => "Настройки еще не вступили в силу. Подождите 5 минут после изменения настроек и попробуйте еще раз.",
                'show_alert'        => true
            ]);
        }
    }
}
