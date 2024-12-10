<?php 

namespace App\Bots\inzerko_bot\Commands\UserCommands\Profile;

use App\Bots\inzerko_bot\Commands\UserCommands\MenuCommand;
use App\Bots\inzerko_bot\Facades\Inzerko;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Phone extends Command
{
    public static $command = 'phone';

    public static $usage = ['phone'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $updates->getFrom()->setExpectation(AwaitPhone::$expectation);

        $buttons = Inzerko::inlineKeyboard([
            [
                array("👈 Назад", Profile::$command, ''),
                array(MenuCommand::getTitle('ru'), MenuCommand::$command, ''),
            ]
        ]);

        $text = implode("\n", [
            "*Напишите свой номер телефона на который зарегистрирован Ваш Telegram аккаунт:*"."\n",
		    "*Пример*: +420 777 123 487",
        ]);

        return Inzerko::returnInline([
            'text'          =>  $text,
            'chat_id'       =>  $updates->getChat()->getId(),
            'reply_markup'  =>  $buttons,
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()?->getMessageId(),
        ]);
    }
}
