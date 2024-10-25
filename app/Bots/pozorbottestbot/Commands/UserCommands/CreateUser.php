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

class CreateUser extends Command
{
    public static $command = 'create_user';

    public static $title = '';

    public static $usage = ['create_user'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $telegram_chat = DB::getChat($updates->getChat()->getId());

        $notes = $this->getConversation()->notes;

        $password = Str::random(8);
        $telegram_token = Str::random(8);

        $user = User::create([
            'name' => $updates->getFrom()->getFirstName() . ' ' . $updates->getFrom()->getLastName(),
            'email' => $notes['email'],
            'phone' => $notes['phone'],
            'password' => Hash::make($password),
            'telegram_token' => $telegram_token,
        ]);

        $photo_url = BotApi::getPhoto(['file_id' => $telegram_chat->photo]);

        $user->addMediaFromUrl($photo_url)->toMediaCollection('avatar');

        return $this->bot->executeCommand(SendTelegramEmailVerificationNotification::$command);
    }
}
