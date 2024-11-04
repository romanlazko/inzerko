<?php 

namespace App\Bots\pozor_baraholka_bot\Commands\UserCommands;

use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class SaveCategory extends Command
{
    public static $command = 'save_category';

    public static $usage = ['save_category'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $category_id = $updates->getInlineData()->getCategoryId();

        if (!$category_id) {
            return BotApi::answerCallbackQuery([
                'callback_query_id' => $updates->getCallbackQuery()->getId(),
                'text'              => "Нужно выбрать хотя бы одну категорию",
                'show_alert'        => true
            ]);
        }

        $this->getConversation()->update([
            'category_id' => $category_id
        ]);
            
        return $this->bot->executeCommand(Subcategory::$command);
    }
}
