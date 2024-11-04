<?php 

namespace App\Bots\pozor_baraholka_bot\Commands\UserCommands;

use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class SaveSubcategory extends Command
{
    public static $command = 'save_subcategory';

    public static $usage = ['save_subcategory'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $subcategory_id = $updates->getInlineData()->getSubcategoryId();

        if (!$subcategory_id) {
            return BotApi::answerCallbackQuery([
                'callback_query_id' => $updates->getCallbackQuery()->getId(),
                'text'              => "Нужно выбрать хотя бы одну под категорию",
                'show_alert'        => true
            ]);
        }

        $this->getConversation()->update([
            'subcategory_id' => $subcategory_id
        ]);
            
        return $this->bot->executeCommand(Caption::$command);
    }
}
