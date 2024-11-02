<?php 

namespace App\Bots\inzerko_bot\Commands\UserCommands;

use Romanlazko\Telegram\App\BotApi;
use App\Bots\pozor_baraholka_bot\Models\BaraholkaAnnouncement;
use App\Models\TelegramChat;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Commands\UserCommands\AdvertisementCommand;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;
use Romanlazko\Telegram\Exceptions\TelegramUserException;

class GetContact extends Command
{
    public static $command = 'get_contact';

    public static $title = '';

    public static $pattern = "/^(\/start\s)(contact)-(\d+)$/";

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        preg_match(static::$pattern, $updates->getMessage()?->getCommand(), $matches);

        $buttons = BotApi::inlineKeyboardWithLink([
            'text'  => "👤 Контакт на автора", 
            'url'   => "tg://user?id={$matches[3]}"
        ]);

        $text = [
            "<b>Вот контакт на автора объявления:</b>",
        ];

        return BotApi::sendMessage([
            'text'                      => implode("\n\n", $text),
            'reply_markup'              => $buttons,
            'chat_id'                   => $this->updates->getChat()->getId(),
            'parse_mode'                => 'HTML',
            'disable_web_page_preview'  => true,
        ]);
    }
}