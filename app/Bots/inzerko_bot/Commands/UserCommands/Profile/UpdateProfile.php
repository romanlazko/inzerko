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
use Illuminate\Support\Facades\Validator;

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

        $validator = $this->validator($notes);

        if ($validator->stopOnFirstFailure()->fails()) {
            return Inzerko::answerCallbackQuery([
                'callback_query_id' => $updates->getCallbackQuery()->getId(),
                'text' => $validator->errors()->first(),
                'show_alert' => true
            ]);
        }

        $validated = $validator->validated();

        $user = ProfileService::update(
            user: $user,
            name: $updates->getFrom()->getFirstName() . ' ' . $updates->getFrom()->getLastName(),
            email: $validated['email'],
            locale: $updates->getFrom()->getLanguageCode(),
            communication_settings: [
                'telegram' => [
                    'phone' => $validated['phone'],
                    'visible' => true,
                ],
                'languages' => $validated['languages']
            ]
        );

        ProfileService::addMedia($user, Inzerko::getPhoto(['file_id' => $telegram_chat->photo]));
        
        return $this->bot->executeCommand(CreateAnnouncement::$command);
    }

    private function validator(array $data)
    {
        return Validator::make(
            $data, 
            [
                'email' => 'required|email',
                'phone' => 'required|phone:AUTO',
                'languages' => ['required', 'array'],
                'languages.*' => ['string', 'in:en,ru,cz'],
            ],
            [
                'email.required' => 'Поле e-mail обязательно к заполнению',
                'phone.required' => 'Поле телефона обязательно к заполнению',
                'languages.required' => 'Поле языков обязательно к заполнению',
            ]
        );
    }
}
