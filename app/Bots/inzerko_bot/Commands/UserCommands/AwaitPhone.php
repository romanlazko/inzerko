<?php 

namespace App\Bots\inzerko_bot\Commands\UserCommands;

use Illuminate\Support\Facades\Validator;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class AwaitPhone extends Command
{
    public static $expectation = 'await_phone';

    public static $pattern = '/^await_phone$/';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $validator = Validator::make(
            ['phone' => $updates->getMessage()?->getText()], 
            ['phone' => 'required|max:16|regex:/^(\+?\d{3}\s*)?\d{3}[\s-]?\d{3}[\s-]?\d{3}$/'], 
            [
                'phone.required' => 'Поле телефона обязательно к заполнению',
                'phone.max' => 'Слишком длинный номер телефона',
                'phone.regex' => 'Не верный формат номера телефона',
            ]
        );

        if ($validator->stopOnFirstFailure()->fails()) {
            $this->handleError($validator->errors()->first());
            return $this->bot->executeCommand(Phone::$command);
        }

        $validated = $validator->validated();

        $this->getConversation()->update([
            'phone' => $validated['phone']
        ]);
        
        return $this->bot->executeCommand(EditProfile::$command);
    }
}