<?php 

namespace App\Bots\pozor_baraholka_bot\Commands\UserCommands;

use App\Bots\pozor_baraholka_bot\Models\BaraholkaCategory;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Category extends Command
{
    public static $command = 'category';

    public static $title = '';

    public static $usage = ['category'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $notes = $this->getConversation()->notes;

        $buttons = BaraholkaCategory::where('is_active', true)
            ->get()
            ->map(function ($category) {
                return array(__($category->trans_name()), SaveCategory::$command, $category->id);
            })
            ->chunk(3)
            ->toArray();

        $buttons = BotApi::inlineKeyboard([
            ...$buttons,
            [
                array("👈 Назад", $notes['next'] === 'title' ? Cost::$command : Photo::$command, ''),
                array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')
            ],
        ], 'category_id');

        $data = [
            'text'          => "Выбери к какой *категории* относится товар(ы).",
            'chat_id'       => $updates->getChat()->getId(),
            'message_id'    => $updates->getCallbackQuery()?->getMessage()?->getMessageId(),
            'parse_mode'    => "Markdown",
            'reply_markup'  => $buttons
        ];

        return BotApi::returnInline($data);
    }
}
