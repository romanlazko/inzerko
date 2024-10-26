<?php 

namespace App\Bots\inzerko_bot\Commands\UserCommands;

use Illuminate\Support\Facades\Validator;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class AwaitEmail extends Command
{
    public static $expectation = 'await_email';

    public static $pattern = '/^await_email$/';

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $validator = Validator::make(
            ['email' => $updates->getMessage()?->getText()], 
            ['email' => 'required|email|unique:users,email',],
            [
                'email.unique' => 'Пользователь с таким почтовым адресом уже зарегистрирован',
                'email.required' => 'Поле e-mail обязательно к заполнению',
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
