<?php 

namespace App\Bots\inzerko_bot\Commands\UserCommands\Profile;

use App\Bots\inzerko_bot\Commands\UserCommands\MenuCommand;
use App\Bots\inzerko_bot\Facades\Inzerko;
use App\Enums\ContactTypeEnum;
use App\Models\User;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Commands\Command;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class Profile extends Command
{
    public static $command = 'edit-profile';

    public static $title = [
        'en' => '👤 Profile',
        'ru' => '👤 Профиль'
    ];

    public static $usage = ['edit-profile'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $telegram_chat = DB::getChat($updates->getChat()->getId());

        $conversation = $this->getConversation();

        $user = User::firstWhere('telegram_chat_id', $telegram_chat->id);

        $this->getConversation()->update([
            'phone' => $conversation->notes['phone'] ?? $user?->contacts->firstWhere('type', ContactTypeEnum::PHONE)?->link,
            'email' => $conversation->notes['email'] ?? $user?->email,
            'languages' => $conversation->notes['languages'] ?? $user?->languages
        ]);

        $saveProfileCommand = $user ? UpdateProfile::class : StoreProfile::class;

        $buttons = BotApi::inlineKeyboard([
            [array('📩 Email: '. ($conversation->notes['email'] ?: 'обязательно'), Email::$command, '')],
            [array('☎️ Телефон: '. ($conversation->notes['phone'] ?: 'не обязательно'), Phone::$command, '')],
            [array(
                '🗣️ Языки: '. ($conversation->notes['languages'] ? implode(', ', $conversation->notes['languages']) : 'обязательно'),
                Languages::$command,
                $conversation->notes['languages'] ? ":".implode(':', $conversation->notes['languages']) : '',
            )],
            [
                array("👈 Назад", MenuCommand::$command, ''),
                array($saveProfileCommand::getTitle('ru'), $saveProfileCommand::$command, ''),
            ]
            
        ], 'languages');

        $text = implode("\n", [
            "*Ваш профиль:*"."\n",
        ]);

        return Inzerko::returnInline([
            'text'          =>  $text,
            'chat_id'       =>  $updates->getChat()->getId(),
            'reply_markup'  =>  $buttons,
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()?->getMessageId(),
        ]);
    }
}
