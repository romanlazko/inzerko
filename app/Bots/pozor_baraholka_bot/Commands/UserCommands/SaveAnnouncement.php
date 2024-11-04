<?php 

namespace App\Bots\pozor_baraholka_bot\Commands\UserCommands;

use App\Bots\pozor_baraholka_bot\Http\DataTransferObjects\Announcement;
use App\Bots\pozor_baraholka_bot\Http\Services\BaraholkaAnnouncementService;
use App\Bots\pozor_baraholka_bot\Models\BaraholkaAnnouncement;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class SaveAnnouncement extends Command
{
    public static $command = 'save_announcement';

    public static $usage = ['save_announcement'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $notes   = $this->getConversation()->notes;

        (new BaraholkaAnnouncementService($this->bot))->storeAnnouncement(
            Announcement::fromObject((object) [
                'telegram_chat_id'  => DB::getChat($updates->getChat()->getId())->id,
                ...$notes
            ])
        );
        
        return $this->bot->executeCommand(Published::$command);
    }
}