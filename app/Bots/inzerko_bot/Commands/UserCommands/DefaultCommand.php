<?php 

namespace App\Bots\inzerko_bot\Commands\UserCommands;

use App\Bots\inzerko_bot\Facades\Inzerko;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class DefaultCommand extends Command
{
    public static $command = 'default';

    public static $usage = ['default', '/default'];

    public static $title = [
        'ru' => '❓ Помощь',
        'en' => '❓ Help'
    ];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = Inzerko::inlineKeyboard([
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')],
        ]);

        $text = implode("\n", [
            "Здесь список доступных команд:"."\n",
            "/menu - 🏠 Главное меню,",
            "/start - 🏁 Запустить бота."."\n",
            "Возникли проблемы? 🤔",
            "Обращайтесь в поддержку *@inzerko_support*"
        ]);

        return Inzerko::returnInline([
            'text'          =>  $text,
            'chat_id'       =>  $updates->getChat()->getId(),
            'reply_markup'  =>  $buttons,
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ]);
    }
}
