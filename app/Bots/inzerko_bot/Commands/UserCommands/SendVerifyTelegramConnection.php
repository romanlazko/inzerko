<?php 

namespace App\Bots\inzerko_bot\Commands\UserCommands;

use App\Bots\inzerko_bot\Commands\Command;
use App\Bots\inzerko_bot\Facades\Inzerko;
use App\Bots\inzerko_bot\Notifications\VerifyTelegramConnection;
use App\Models\User;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class SendVerifyTelegramConnection extends Command
{
    public static $command = 'svtc';

    public static $title = [
        'ru' => '🔄 Отправить письмо снова',
        'en' => '🔄 Send email again',
    ];

    public static $usage = ['svtc'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $telegram_chat      = DB::getChat($updates->getChat()->getId());
        $user               = User::findByToken($this->getConversation()->notes['telegram_token']);

        if (! $updates->getChat()->getUsername()) {
            return $this->sendUsernameRequiredInstructions(
                Inzerko::inlineKeyboard([
                    [array('Продолжить', self::$command, '')],
                ])
            );
        }

        Inzerko::returnInline([
            'chat_id' => $updates->getChat()->getId(),
            'text' => 'Отправление письма...',
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ]);
        
        $user->notify(new VerifyTelegramConnection($telegram_chat->id));

        Inzerko::answerCallbackQuery([
            'callback_query_id' => $updates->getCallbackQuery()->getId(),
            'text' => 'Письмо было отправлено. Пожалуйста, подтвердите свой e-mail, перейдя по ссылке в письме.',
            'show_alert' => true
        ]);

        $buttons = Inzerko::inlineKeyboard([
            [array(SendVerifyTelegramConnection::getTitle('ru'), SendVerifyTelegramConnection::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')]
        ]);

        $text = implode("\n", [
            "*Подтверждение связи с ботом*"."\n",
            "На e-mail: *{$user->email}* было отправлено письмо для подтверждения связи с ботом. Пожалуйста, подтвердите связь с ботом, нажав на кнопку в письме."."\n",
            "_Письмо обычно приходит через несколько минут._"
        ]);

        return Inzerko::returnInline([
            'chat_id' => $updates->getChat()->getId(),
            'text' => $text,
            'parse_mode' => 'Markdown',
            'message_id' => $updates->getCallbackQuery()?->getMessage()?->getMessageId(),
            'reply_markup' => $buttons
        ]);
    }
}