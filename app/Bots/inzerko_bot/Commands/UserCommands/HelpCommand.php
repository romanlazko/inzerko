<?php 

namespace App\Bots\inzerko_bot\Commands\UserCommands;

use App\Bots\inzerko_bot\Facades\Inzerko;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class HelpCommand extends Command
{
    public static $command = '/help';

    public static $title = [
        'ru' => '❓ Помощь',
        'en' => '❓ Help'
    ];

    public static $usage = ['/help', 'help', 'Помощь', 'Help'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $buttons = Inzerko::inlineKeyboard([
            [array(MenuCommand::getTitle('en'), MenuCommand::$command, '')],
        ]);

        $text = implode("\n", [
            "Hi 👋",
            "Here is a list of available commands:"."\n",
            "/menu - 🏠 Menu,",
            "/start - 🏁 Start bot." 
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