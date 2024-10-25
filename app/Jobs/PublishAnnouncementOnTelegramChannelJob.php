<?php

namespace App\Jobs;

use App\Models\Announcement;
use App\Models\TelegramChat;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Enums\Status;
use Romanlazko\Telegram\App\Bot;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Entities\Response;
use App\Models\AnnouncementChannel;

class PublishAnnouncementOnTelegramChannelJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public $announcement_channel_id, public $lang = 'ru')
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $announcement_channel = AnnouncementChannel::where('id', $this->announcement_channel_id)->first();

        if ($announcement_channel->status->isAwaitTelegramPublication()) {

            $response = $this->publishOnChannel($announcement_channel->announcement, $announcement_channel->telegram_chat);

            if ($response->getOk()) {
                $announcement_channel->published([
                    'channel' => $announcement_channel->telegram_chat->title,
                    'response' => $response->getOk(),
                    'message'  => $response->getMessage(),
                ]);
            }
        }
    }

    public function publishOnChannel(Announcement $announcement, TelegramChat $chat): Response
    {
        app()->setLocale($this->lang);
        
        $bot = new Bot($chat->bot->token);

        $buttons = BotApi::inlineKeyboardWithLink(
            array('text' => "Посмотреть объявление", 'url' => route('announcement.show', $announcement)),
        );

        return $bot::sendPhoto([
            'caption'                   => view('telegram.announcement.show', ['announcement' => $announcement])->render(),
            'chat_id'                   => $chat->chat_id,
            'photo'                     => $announcement->getFirstMediaUrl('announcements'),
            'parse_mode'                => 'HTML',
            'disable_web_page_preview'  => 'true',
            'reply_markup'              => $buttons,
        ]);
    }

    public function failed(array|\Error|\Throwable|\Exception $exception): void
    {
        AnnouncementChannel::find($this->announcement_channel_id)->publishingFailed($exception);
    }
}
