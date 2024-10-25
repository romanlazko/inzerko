<?php 

namespace App\Bots\pozorbottestbot\Commands\UserCommands;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
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

        $notes = $this->getConversation()->notes;

        $user = User::firstWhere('telegram_chat_id', $telegram_chat->id);

        if (! $user) {
            return $this->bot->executeCommand(CreateProfile::$command);
        }

        $user->fill([
            'name' => $updates->getFrom()->getFirstName() . ' ' . $updates->getFrom()->getLastName(),
            'email' => $notes['email'],
            'phone' => $notes['phone'],
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        
        $user->save();

        $photo_url = BotApi::getPhoto(['file_id' => $telegram_chat->photo]);

        $user->addMediaFromUrl($photo_url)->toMediaCollection('avatar');
        
        return $this->bot->executeCommand(CreateAnnouncement::$command);
    }
}
