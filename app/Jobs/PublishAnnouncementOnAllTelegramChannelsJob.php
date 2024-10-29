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

class PublishAnnouncementOnAllTelegramChannelsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public $announcement_id)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $announcement = Announcement::where('id', $this->announcement_id)->with('channels', 'currentStatus')->first();

        if ($announcement->status->isAwaitTelegramPublication()) {
            if ($announcement->channels->isNotEmpty() AND $this->publish($announcement)) {
                $announcement->publishedOnTelegram([
                    'message' => 'Announcement has been published on all channels',
                    'channels' => $announcement->channels->map(fn ($channel) => $channel->telegram_chat->title)->toArray(),
                ]);
            }
            else {
                $announcement->publishedOnTelegram([
                    'message' => 'Announcement channels are empty',
                ]);
            }
        }
        
    }

    public function publish(Announcement $announcement)
    {
        $channels = $announcement->channels->filter(function ($channel) {
            return ! $channel->status?->isPublished();
        });

        foreach ($channels as $channel) {
            try {
                $response = $this->publishOnChannel($announcement, $channel->telegram_chat);

                if ($response->getOk()) {
                    $channel->published([
                        'channel' => $channel->telegram_chat->title,
                        'response' => $response->getOk(),
                        'message'  => 'Announcement has been published on channel',
                    ]);
                }
            } catch (\Error|\Throwable|\Exception $exception) {
                $channel->publishingFailed($exception);
                throw $exception;
            }
        }

        return true;
    }

    public function publishOnChannel(Announcement $announcement, TelegramChat $chat): Response
    {
        app()->setLocale('ru');

        $buttons = Inzerko::inlineKeyboardWithLink(
            array('text' => "Посмотреть объявление", 'url' => route('announcement.show', $announcement)),
        );

        return Inzerko::sendPhoto([
            'caption'                   => view('telegram.announcement.show', ['announcement' => $announcement])->render(),
            'chat_id'                   => $chat->chat_id,
            'photo'                     => $announcement->getFirstMediaUrl('announcements'),
            'parse_mode'                => 'HTML',
            'disable_web_page_preview'  => 'true',
            'reply_markup'              => $buttons,
        ]);
    }

    public function failed(\Error|\Throwable $exception): void
    {
        Announcement::where('id', $this->announcement_id)->first()->publishingFailed($exception);
    }
}
