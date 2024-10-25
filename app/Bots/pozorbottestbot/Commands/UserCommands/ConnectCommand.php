<?php 

namespace App\Bots\pozorbottestbot\Commands\UserCommands;

use App\Models\User;
use App\Notifications\TelegramConnect;
use App\Notifications\VerifyTelegramConnection;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

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

        User::firstWhere('telegram_token', $matches[3])?->notify(new VerifyTelegramConnection($telegram_chat->id));

        return BotApi::returnInline([
            'chat_id' => $updates->getChat()->getId(),
            'text' => "На ваш эмейл было отправлено письмо для подтверждения связи с ботом. Пожалуйста, подтвердите связь с ботом, нажав на кнопку в письме.",
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ]);
    }
}