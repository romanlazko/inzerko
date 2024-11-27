<?php 

namespace App\Bots\inzerko_bot\Commands\UserCommands\Profile;

use App\Bots\inzerko_bot\Commands\UserCommands\CreateAnnouncement;
use App\Bots\inzerko_bot\Facades\Inzerko;
use App\Models\User;
use App\Services\ProfileService;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class UpdateProfile extends Command
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

        $user = ProfileService::update(
            user: $user,
            name: $updates->getFrom()->getFirstName() . ' ' . $updates->getFrom()->getLastName(),
            email: $notes['email'] ?? null,
            locale: $updates->getFrom()->getLanguageCode(),
            communication_settings: $notes['phone'] ? [
                'telegram' => [
                    'phone' => $notes['phone'],
                    'visible' => true,
                ]
            ] : null
        );

        ProfileService::addMedia($user, Inzerko::getPhoto(['file_id' => $telegram_chat->photo]));
        
        return $this->bot->executeCommand(CreateAnnouncement::$command);
    }
}
