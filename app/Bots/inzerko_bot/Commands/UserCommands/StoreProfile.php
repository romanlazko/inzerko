<?php 

namespace App\Bots\inzerko_bot\Commands\UserCommands;

use App\Models\User;
use App\Services\ProfileService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

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

        $validator = $this->validator([
            'email' => $notes['email'],
            'phone' => $notes['phone'],
        ]);

        if ($validator->stopOnFirstFailure()->fails()) {
            $this->handleError($validator->errors()->first());
            return $this->bot->executeCommand(Profile::$command);
        }

        $validated = $validator->validated();

        $user = ProfileService::create(
            name: $updates->getFrom()->getFirstName() . ' ' . $updates->getFrom()->getLastName(),
            email: $validated['email'],
            phone: $validated['phone'],
            locale: $updates->getFrom()->getLanguageCode(),
            telegram_chat_id: $telegram_chat->id,
            telegram_token: Str::random(8)
        );

        $photo_url = BotApi::getPhoto(['file_id' => $telegram_chat->photo]);

        $user->addMediaFromUrl($photo_url)->toMediaCollection('avatar');

        return $this->bot->executeCommand(CreateAnnouncement::$command);
    }

    private function validator(array $data)
    {
        return Validator::make(
            $data, 
            [
                'email' => 'required|email|unique:users,email',
                'phone' => 'required|max:16|regex:/^(\+?\d{3}\s*)?\d{3}[\s-]?\d{3}[\s-]?\d{3}$/',
            ],
            [
                'email.required' => 'Поле e-mail обязательно к заполнению',
                'phone.required' => 'Поле телефона обязательно к заполнению',
            ]
        );
    }
}
