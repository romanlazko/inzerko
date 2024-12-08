<?php 

namespace App\Bots\inzerko_bot\Commands\UserCommands;

use App\Bots\inzerko_bot\Commands\UserCommands\Profile\Profile;
use App\Bots\inzerko_bot\Facades\Inzerko;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class CreateAnnouncement extends Command
{
    public static $command = 'create_announcement';

    public static $title = [
        'en' => 'Create Announcement',
        'ru' => 'Создать объявление',
    ];

    public static $usage = ['create_announcement'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $telegram_chat = DB::getChat($updates->getChat()->getId());

        $user = User::firstWhere('telegram_chat_id', $telegram_chat->id);

        if (! $user) {
            return $this->bot->executeCommand(Profile::$command);
        }

        if (! $user->hasVerifiedEmail()) {
            return $this->bot->executeCommand(SendTelegramEmailVerificationNotification::$command);
        }

        $url = URL::temporarySignedRoute('inzerko_bot.auth', 600, [
            'token' => $user->createAccessToken('create-announcement')->token,
        ]);

        $buttons = Inzerko::inlineKeyboardWithLink(
            array('text' => "Опубликовать объявление", 'web_app' => ['url' => $url]),
            [
                [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
            ],
        );

        return Inzerko::returnInline([
            'text'          => "Правила публикации: [https://inzerko.cz/page/podminky-vyuzivani-sluzeb-serveru-inzerkocz](тут)",
            'chat_id'       => $updates->getChat()->getId(),
            'parse_mode'    => "Markdown",
            'reply_markup'  => $buttons,
            'message_id'    => $updates->getCallbackQuery()?->getMessage()?->getMessageId(),
        ]);
    }
}
