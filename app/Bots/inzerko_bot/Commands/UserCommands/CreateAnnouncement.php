<?php 

namespace App\Bots\inzerko_bot\Commands\UserCommands;

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
            return $this->bot->executeCommand(EditProfile::$command);
        }

        if (! $user->hasVerifiedEmail()) {
            return $this->bot->executeCommand(EmailNotVerified::$command);
        }

        app()->setLocale($updates->getFrom()->getLanguageCode());

        $url = env('APP_URL').URL::signedRoute('inzerko_bot.auth', ['email' => $user->email, 'telegram_chat_id' => $telegram_chat->id, 'route' => 'inzerko_bot.announcements.create'], null, false);

        $buttons = BotApi::inlineKeyboardWithLink(
            array('text' => "Опубликовать объявление", 'web_app' => ['url' => $url]),
            [
                [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
            ],
        );

        return BotApi::returnInline([
            'text'          => "Правила публикации: тут будет ссылка на правила публикации",
            'chat_id'       => $updates->getChat()->getId(),
            'parse_mode'    => "Markdown",
            'reply_markup'  => $buttons,
            'message_id'    => $updates->getCallbackQuery()?->getMessage()?->getMessageId(),
        ]);
    }
}
