<?php 

namespace App\Bots\inzerko_bot\Commands\UserCommands;

use App\Bots\inzerko_bot\Facades\Inzerko;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
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

        $validated = $this->validateRequest([
            'telegram_token' => $matches[3],
            'telegram_chat_id' => $telegram_chat->id
        ]);

        $this->getConversation()->update([
            'telegram_token' => $validated['telegram_token']
        ]);

        $buttons = BotApi::inlineKeyboard([
            [array("Да подключить", SendVerifyTelegramConnection::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')]
        ]);

        return Inzerko::returnInline([
            'chat_id' => $updates->getChat()->getId(),
            'text' => implode("\n", [
                "*Подключение телеграм аккаунта*"."\n",
                "Хотите подключить этот телеграм аккаунт к аккаунту на сайте *INZERKO.cz*?",
            ]),
            'parse_mode'    => 'Markdown',
            'reply_markup'  => $buttons,
            'message_id'    => $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ]);
    }

    protected function validateRequest(array $request): array
    {
        $validator = Validator::make(
            $request, 
            [
                'telegram_token' => 'exists:users,telegram_token|unique:users,telegram_token',
                'telegram_chat_id' => 'exists:telegram_chats,id|unique:users,telegram_chat_id',
            ], 
            [
                'telegram_token.exists' => 'Пользователь не найден',
                'telegram_token.unique' => 'Этот телеграм аккаунт уже подключен к аккаунту на сайте',
                'telegram_chat_id.exists' => 'Телеграм аккаунт не найден',
                'telegram_chat_id.unique' => 'Этот телеграм аккаунт уже подключен к аккаунту на сайте',
            ]
        );

        if ($validator->stopOnFirstFailure()->fails()) {
            throw new TelegramUserException($validator->errors()->first());
        }

        return $validator->validated();
    }
}