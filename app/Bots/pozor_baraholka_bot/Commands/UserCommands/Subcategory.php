<?php 

namespace App\Bots\pozor_baraholka_bot\Commands\UserCommands;

use App\Bots\pozor_baraholka_bot\Models\BaraholkaSubcategory;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Subcategory extends Command
{
    public static $command = 'subcategory';

    public static $title = '';

    public static $usage = ['subcategory'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $notes = $this->getConversation()->notes;

        $buttons = BaraholkaSubcategory::where('is_active', true)->where('baraholka_category_id', $notes['category_id'])
            ->get()
            ->map(function ($subcategory) {
                return array(__($subcategory->trans_name()), SaveSubcategory::$command, $subcategory->id);
            })
            ->chunk(2)
            ->toArray();

        if (empty($buttons)) {
            return $this->bot->executeCommand(Caption::$command);
        }

        $buttons = BotApi::inlineKeyboard([
            ...$buttons,
            [
                array("👈 Назад", Category::$command, ''),
                array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')
            ],
        ], 'subcategory_id');

        $data = [
            'text'          => "Выбери к какой *под категории* относится товар(ы).",
            'chat_id'       => $updates->getChat()->getId(),
            'message_id'    => $updates->getCallbackQuery()?->getMessage()?->getMessageId(),
            'parse_mode'    => "Markdown",
            'reply_markup'  => $buttons
        ];

        return BotApi::returnInline($data);
    }
}
