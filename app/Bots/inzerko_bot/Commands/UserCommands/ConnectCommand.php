<?php 

namespace App\Bots\inzerko_bot\Commands\UserCommands;

use App\Bots\inzerko_bot\Facades\Inzerko;
use App\Models\User;
use Illuminate\Support\Facades\Log;
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

        Log::info('ConnectCommand Updates', json_decode($updates->getJson()));

        $buttons = BotApi::inlineKeyboard([
            [array("Да подключить", SendVerifyTelegramConnection::$command, $matches[3])],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')]
        ], 'telegram_token');

        Log::info('ConnectCommand buttons', $buttons);

        return Inzerko::returnInline([
            'chat_id' => $updates->getChat()->getId(),
            'text' => implode("\n", [
                "*Подключение телеграм аккаунта*"."\n",
                "*Телеграм код:* " . $matches[3],
                "Хотите подключить этот телеграм аккаунт к аккаунту на сайте *INZERKO.cz*?",
            ]),
            'parse_mode'    => 'Markdown',
            'reply_markup'  => $buttons,
            'message_id'    => $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ]);
    }
}