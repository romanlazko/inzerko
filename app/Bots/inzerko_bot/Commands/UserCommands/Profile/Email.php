<?php 

namespace App\Bots\inzerko_bot\Commands\UserCommands\Profile;

use App\Bots\inzerko_bot\Commands\UserCommands\MenuCommand;
use App\Bots\inzerko_bot\Facades\Inzerko;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Email extends Command
{
    public static $command = 'email';

    public static $title = '';

    public static $usage = ['email'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $updates->getFrom()->setExpectation(AwaitEmail::$expectation);
        
        $buttons = Inzerko::inlineKeyboard([
            [
                array("👈 Назад", Profile::$command, ''),
                array(MenuCommand::getTitle('ru'), MenuCommand::$command, ''),
            ]
        ]);

        $data = [
            'text'          => "Напишите свой e-mail:",
            'chat_id'       => $updates->getChat()->getId(),
            'parse_mode'    => "Markdown",
            'reply_markup'  => $buttons,
            'message_id'    => $updates->getCallbackQuery()?->getMessage()?->getMessageId(),
        ];

        return Inzerko::returnInline($data);
    }




}
