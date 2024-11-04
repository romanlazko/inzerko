<?php 

namespace App\Bots\pozorprace_bot\Commands\UserCommands;

use Romanlazko\Telegram\App\BotApi;
use App\Bots\pozorprace_bot\Models\PraceAnnouncement;
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

        $announcement = PraceAnnouncement::findOr($matches[3] ?? null, function () {
            throw new TelegramUserException("Объявление не найдено");
        });

        if ($announcement->status === 'irrelevant') {
            throw new TelegramUserException("Объявление уже не актуально.");
        }

        if ($announcement->status === 'published') {
            $announcement->increment('views');
        }
        
        return $this->sendAnnouncementContact($announcement);
    }

    private function sendAnnouncementContact(PraceAnnouncement $announcement)
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

    private function prepare(PraceAnnouncement $announcement) 
    {
        $text = [];

        if ($announcement->type) {
            $text[] = $announcement->type === 'propose' ? "#предлагаю_работу" : "#ищу_работу";
        }

        if ($announcement->title) {
            $text[] = "<b>{$announcement->title}</b>";
        }

        if ($announcement->caption) {
            $text[] = $announcement->caption;
        }

        if ($announcement->additional_info) {
            $text[] = $announcement->additional_info;
        }

        if ($announcement->salary_type AND $announcement->salary) {
            $salary_type_arr = [
                'hour' => "в час",
                'day' => "в день",
                'month' => "в месяц",
                'ex_post' => "за выполненную работу",
            ];
            $text[] = "<i>Предлагаемая оплата:</i> {$announcement->salary} CZK {$salary_type_arr[$announcement->salary_type]}";
        }

        if ($announcement->education) {
            $education_arr = [
                'not_required' => "Не требуется",
                'secondary' => "Среднее",
                'higher' => "Высшее",
                'special' => "Специальное",
            ];
            $text[] = "<i>Требуемое образование:</i> {$education_arr[$announcement->education]}";
        }

        if ($announcement->workplace) {
            $text[] = "<i>Место работы:</i> {$announcement->workplace}";
        }

        return implode("\n\n", $text);
    }
}
