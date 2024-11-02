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

    public function execute(Update $updates): Response
    {
        preg_match(self::$pattern, $updates->getMessage()?->getCommand(), $matches);

        $telegram_chat = DB::getChat($updates->getChat()->getId());

        if (User::where('telegram_chat_id', $telegram_chat->id)->first()) {
            return $this->handleError("Этот телеграм аккаунт уже подключен к аккаунту на сайте.");
        }

        $buttons = BotApi::inlineKeyboard([
            [array(SendVerifyTelegramConnection::getTitle('ru'), SendVerifyTelegramConnection::$command, $matches[3])],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')]
        ], 'telegram_token');

        return BotApi::returnInline([
            'chat_id' => $updates->getChat()->getId(),
            'text' => implode("\n", [
                "*Прежде чем продолжить, пожалуйста, подтвердите свой e-mail*"."\n",
            ]),
            'parse_mode'    =>  'Markdown',
            'reply_markup'  => $buttons,
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ]);
    }
}