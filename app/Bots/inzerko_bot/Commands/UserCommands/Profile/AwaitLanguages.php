<?php 

namespace App\Bots\inzerko_bot\Commands\UserCommands\Profile;

use Illuminate\Support\Facades\Validator;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class AwaitLanguages extends Command
{
    public static $expectation = 'await_languages';

    public static $pattern = '/^await_languages$/';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $validator = Validator::make(
            ['languages' => $updates->getInlineData()?->getLanguages()], 
            [
                'languages' => ['required', 'array'],
                'languages.*' => ['string', 'in:en,ru,cz'],
            ],
            [
                'languages.required' => 'Поле языков обязательно к заполнению',
                'email.email' => 'Некорректный e-mail',
            ]
        );

        if ($validator->stopOnFirstFailure()->fails()) {
            $this->handleError($validator->errors()->first());
            return $this->bot->executeCommand(Email::$command);
        }

        $validated = $validator->validated();

        $this->getConversation()->update([
            'email' => $validated['email'],
        ]);
        
        return $this->bot->executeCommand(Profile::$command);
    }
}
