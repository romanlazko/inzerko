<?php 

namespace App\Bots\inzerko_bot\Commands\UserCommands;

use App\Models\User;
use Illuminate\Support\Str;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class CreateProfile extends Command
{
    public static $command = 'create_profile';

    public static $title = '';

    public static $usage = ['create_profile'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $telegram_chat = DB::getChat($updates->getChat()->getId());

        $notes = $this->getConversation()->notes;
        
        $telegram_token = Str::random(8);

        $user = User::create([
            'name' => $updates->getFrom()->getFirstName() . ' ' . $updates->getFrom()->getLastName(),
            'email' => $notes['email'],
            'phone' => $notes['phone'],
            'locale' => $updates->getFrom()->getLanguageCode(),
            'telegram_chat_id' => $telegram_chat->id,
            'telegram_token' => $telegram_token,
        ]);

        $photo_url = BotApi::getPhoto(['file_id' => $telegram_chat->photo]);

        $user->addMediaFromUrl($photo_url)->toMediaCollection('avatar');

        return $this->bot->executeCommand(SendTelegramEmailVerificationNotification::$command);
    }
}
