<?php 

namespace App\Bots\inzerko_bot\Commands\UserCommands\Profile;

use App\Bots\inzerko_bot\Commands\UserCommands\CreateAnnouncement;
use App\Bots\inzerko_bot\Facades\Inzerko;
use App\Models\User;
use App\Services\ProfileService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;
use Laravolt\Avatar\Facade as Avatar;

class StoreProfile extends Command
{
    public static $command = 'store_profile';

    public static $title = [
        'en' => 'Store profile',
        'ru' => 'Создать профиль',
        'cs' => 'Ulozit profil',
    ];

    public static $usage = ['store_profile'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $telegram_chat = DB::getChat($updates->getChat()->getId());

        $notes = $this->getConversation()->notes;

        $validator = $this->validator($notes);

        if ($validator->stopOnFirstFailure()->fails()) {
            return Inzerko::answerCallbackQuery([
                'callback_query_id' => $updates->getCallbackQuery()->getId(),
                'text' => $validator->errors()->first(),
                'show_alert' => true
            ]);
        }

        $validated = $validator->validated();

        $user = ProfileService::create(
            name: $updates->getFrom()->getFirstName() . ' ' . $updates->getFrom()->getLastName(),
            email: $validated['email'],
            locale: $updates->getFrom()->getLanguageCode(),
            telegram_chat_id: $telegram_chat->id,
            telegram_token: Str::random(8),
            communication: [
                'telegram' => [
                    'phone' => $notes['phone'],
                    'visible' => true,
                ]
            ]
        );

        if ($telegram_chat->photo) {
            $photo_url = Inzerko::getPhoto(['file_id' => $telegram_chat->photo]);

            $user->addMediaFromUrl($photo_url)->toMediaCollection('avatar');
        }
        else {
            $user->addMediaFromBase64(Avatar::create("$telegram_chat->first_name $telegram_chat->last_name"))->toMediaCollection('avatar');
        }

        return $this->bot->executeCommand(CreateAnnouncement::$command);
    }

    private function validator(array $data)
    {
        return Validator::make(
            $data, 
            [
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|phone:AUTO',
            ],
            [
                'email.required' => 'Поле e-mail обязательно к заполнению',
                'phone.required' => 'Поле телефона обязательно к заполнению',
            ]
        );
    }
}
