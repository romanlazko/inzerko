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

class PublishAnnouncementJob implements ShouldQueue
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
        $announcement = Announcement::find($this->announcement_id)->load('channels', 'currentStatus');

        if (! $announcement->status->isAwaitPublication()) {
            throw new \Exception('Announcement is not available for publishing');
        }

        $this->publishOnTelegram($announcement);

        $announcement->published();
    }

    public function publishOnTelegram(Announcement $announcement)
    {
        $channels = $announcement->channels->filter(function ($channel) {
            return ! $channel->status?->isPublished();
        });

        foreach ($channels as $channel) {
            $channel->publish('dispatchSync');
        }
    }

    public function failed(\Error|\Throwable|\Exception $exception): void
    {
        Announcement::find($this->announcement_id)->publishingFailed($exception);
    }
}
