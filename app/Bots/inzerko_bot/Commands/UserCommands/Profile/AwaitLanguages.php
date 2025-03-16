<?php 

namespace App\Bots\inzerko_bot\Commands\UserCommands\Profile;

use Illuminate\Support\Facades\Validator;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;
use App\Bots\inzerko_bot\Facades\Inzerko;

class AwaitLanguages extends Command
{
    public static $command = 'await_languages';

    public static $title = ['ru' => 'Языки', 'en' => 'Languages'];

    public static $usage = ['await_languages'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $validator = Validator::make(
            ['languages' => array_values(array_filter(explode(':', $updates->getInlineData()->getLanguages())))], 
            [
                'languages' => ['required', 'array'],
                'languages.*' => ['string', 'in:en,ru,cz'],
            ],
            [
                'languages.required' => 'Поле языков обязательно к заполнению',
            ]
        );

        if ($validator->stopOnFirstFailure()->fails()) {
            return Inzerko::answerCallbackQuery([
                'callback_query_id' => $updates->getCallbackQuery()->getId(),
                'text' => $validator->errors()->first(),
                'show_alert' => true
            ]);
        }

        $validated = $validator->validated();

        $this->getConversation()->update([
            'languages' => array_values($validated['languages']),
        ]);
        
        return $this->bot->executeCommand(Profile::$command);
    }
}
