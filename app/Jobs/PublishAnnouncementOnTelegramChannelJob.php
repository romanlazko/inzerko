<?php

namespace App\Jobs;

use App\Bots\inzerko_bot\Facades\Inzerko;
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
        $announcement_channel = AnnouncementChannel::find($this->announcement_channel_id)->load('currentStatus', 'announcement', 'telegram_chat');

        if (! $announcement_channel->status->isAwaitPublication()) {
            throw new \Exception('Announcement is not available for publishing on Channel');
        }

        $response = $this->publishOnChannel($announcement_channel->announcement, $announcement_channel->telegram_chat);

        if ($response->getOk()) {
            $announcement_channel->published([
                'channel' => $announcement_channel->telegram_chat->title,
                'response' => $response->getOk(),
                'message'  => $response->getMessage(),
            ]);
        }
    }

    public function publishOnChannel(Announcement $announcement, TelegramChat $chat): Response
    {
        app()->setLocale($this->lang);

        $buttons = BotApi::inlineKeyboardWithLink(
            array('text' => "Посмотреть объявление", 'web_app' => ['url' => route('announcement.show', $announcement)]),
        );

        // $buttons = Inzerko::inlineKeyboardWithLink(
        //     array('text' => "Посмотреть объявление", 'url' => route('announcement.show', $announcement)),
        // );

        return Inzerko::sendPhoto([
            'caption'                   => view('inzerko_bot::announcement.show', ['announcement' => $announcement])->render(),
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
