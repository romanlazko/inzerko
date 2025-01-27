<?php 

namespace App\Bots\pozor_baraholka_bot\Commands\UserCommands;

use Romanlazko\Telegram\App\BotApi;
use App\Bots\pozor_baraholka_bot\Models\BaraholkaAnnouncement;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Commands\UserCommands\AdvertisementCommand;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;
use Romanlazko\Telegram\Exceptions\TelegramUserException;

class GetOwnerContact extends Command
{
    public static $command = 'get_owner_contact';

    public static $title = '';

    public static $pattern = "/^(\/start\s)(announcement)=(\d+)$/";

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        preg_match(static::$pattern, $updates->getMessage()?->getCommand(), $matches);

        $announcement = BaraholkaAnnouncement::findOr($matches[3] ?? null, function () {
            throw new TelegramUserException("Объявление не найдено");
        });

        if ($announcement->status !== 'published') {
            throw new TelegramUserException("Объявление уже не актуально.");
        }

        $announcement->increment('views');
        
        return $this->sendAnnouncementContact($announcement);
    }

    private function sendAnnouncementContact(BaraholkaAnnouncement $announcement)
    {
        $buttons = BotApi::inlineKeyboardWithLink([
            'text'  => "👤 Контакт на автора", 
            'url'   => "tg://user?id={$announcement->chat()->first()->chat_id}"
        ]);

        $text = [
            "<b>Вот контакт на автора объявления:</b>",
            $this->prepare($announcement)
        ];

        return BotApi::sendMessage([
            'text'                      => implode("\n\n", $text),
            'reply_markup'              => $buttons,
            'chat_id'                   => $this->updates->getChat()->getId(),
            'parse_mode'                => 'HTML',
            'disable_web_page_preview'  => true,
        ]);
    }

    private function prepare(BaraholkaAnnouncement $announcement): string
    {
        $categoryArr = [
            'clothes'       => '#одежда',
            'accessories'   => '#аксессуары',
            'for_home'      => '#для_дома',
            'electronics'   => '#электроника',
            'sport'         => '#спорт',
            'furniture'     => '#мебель',
            'books'         => '#книги',
            'games'         => '#игры',
            'auto'          => '#авто_мото',
            'property'      => '#недвижимость',
            'animals'       => '#животные',
            'other'         => '#прочее',
        ];

        $text = [];

        if ($announcement->type) {
            $text[] = $announcement->type === 'buy' ? '#куплю' : '#продам';
        }

        if ($announcement->title) {
            $text[] = "<b>{$announcement->title}</b>";
        }

        if ($announcement->caption) {
            $text[] = $announcement->caption;
        }

        if ($announcement->condition) {
            $text[] = "<i>Состояние:</i> " . ($announcement->condition === 'new' ? 'Новое' : 'Б/у');
        }

        if ($announcement->cost) {
            $text[] = "<i>Стоимость:</i> {$announcement->cost} CZK";
        }

        if ($announcement->category && isset($categoryArr[$announcement->category])) {
            $text[] = $categoryArr[$announcement->category];
        }

        return implode("\n\n", $text);
    }
}
