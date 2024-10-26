<?php 

namespace App\Bots\inzerko_bot\Commands\UserCommands;

use App\Models\User;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class SaveProfile extends Command
{
    public static $command = 'save_profile';

    public static $title = [
        'en' => 'Save profile',
        'ru' => 'Сохранить профиль',
    ];

    public static $usage = ['save_profile'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $telegram_chat = DB::getChat($updates->getChat()->getId());

        $user = User::firstWhere('telegram_chat_id', $telegram_chat->id);

        if (! $user) {
            return $this->bot->executeCommand(StoreProfile::$command);
        }
        
        return $this->bot->executeCommand(UpdateProfile::$command);
    }
}
