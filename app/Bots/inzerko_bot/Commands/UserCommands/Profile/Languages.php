<?php 

namespace App\Bots\inzerko_bot\Commands\UserCommands\Profile;

use App\Bots\inzerko_bot\Commands\UserCommands\MenuCommand;
use App\Bots\inzerko_bot\Facades\Inzerko;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Languages extends Command
{
    public static $command = 'languages';

    public static $title = ['ru' => 'Языки', 'en' => 'Languages'];

    public static $usage = ['languages'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {   
        $buttons = BotApi::inlineCheckbox([
            [array('Русский', Languages::$command, 'ru')],
            [array('Английский', Languages::$command, 'en')],
            [array('Чешский', Languages::$command, 'cz')],
            [
                array("👈 Назад", Profile::$command, ''),
                array('Продолжить 👉', AwaitLanguages::$command, ''),
            ]
        ], 'languages');

        $data = [
            'text'          => "Укажите языки которыми вы владеете:",
            'chat_id'       => $updates->getChat()->getId(),
            'parse_mode'    => "Markdown",
            'reply_markup'  => $buttons,
            'message_id'    => $updates->getCallbackQuery()?->getMessage()?->getMessageId(),
        ];

        return Inzerko::returnInline($data);
    }




}
