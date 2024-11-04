<?php 

namespace App\Bots\inzerko_bot\Commands\UserCommands;

use App\Bots\inzerko_bot\Commands\Command;
use App\Bots\inzerko_bot\Notifications\VerifyTelegramConnection;
use App\Models\User;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\DB;
use Romanlazko\Telegram\App\Entities\Response;
use Romanlazko\Telegram\App\Entities\Update;

class SendVerifyTelegramConnection extends Command
{
    public static $command = 'svtc';

    public static $title = [
        'ru' => 'Отправить письмо снова',
        'en' => 'Send email again',
    ];

    public static $usage = ['svtc'];

    protected $enabled = true;

    public function execute(Update $updates): Response
    {
        $telegram_chat      = DB::getChat($updates->getChat()->getId());
        $telegram_token     = $updates->getInlineData()->getTelegramToken();
        $user               = User::firstWhere('telegram_token', $telegram_token);

        if ($this->hasPrivateForwards()) {
            return $this->sendPrivacyInstructions(
                BotApi::inlineKeyboard([
                    [array('Продолжить', SendVerifyTelegramConnection::$command, $telegram_token)],
                ], 'telegram_token')
            );
        }

        BotApi::returnInline([
            'chat_id' => $updates->getChat()->getId(),
            'text' => 'Отправление письма...',
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()->getMessageId(),
        ]);
        
        if ($user->notify(new VerifyTelegramConnection($telegram_chat->id))) {
            BotApi::answerCallbackQuery([
                'callback_query_id' => $updates->getCallbackQuery()->getId(),
                'text' => 'Письмо было отправлено. Пожалуйста, подтвердите свой e-mail, перейдя по ссылке на письме.',
                'show_alert' => true
            ]);
        }

        $buttons = BotApi::inlineKeyboard([
            [array(SendVerifyTelegramConnection::getTitle('ru'), SendVerifyTelegramConnection::$command, '')],
            [array(MenuCommand::getTitle('ru'), MenuCommand::$command, '')]
        ]);

        return BotApi::returnInline([
            'chat_id' => $updates->getChat()->getId(),
            'text' => "На e-mail: *{$user->email}* было отправлено письмо для подтверждения связи с ботом. Пожалуйста, подтвердите связь с ботом, нажав на кнопку в письме.",
            'parse_mode'    =>  'Markdown',
            'message_id'    =>  $updates->getCallbackQuery()?->getMessage()?->getMessageId(),
            'reply_markup'  =>  $buttons
        ]);
    }
}