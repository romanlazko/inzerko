<?php

namespace App\Jobs;

use App\Enums\Status;
use App\Facades\Deepl;
use App\Models\Announcement;
use App\Models\AnnouncementChannel;
use App\Models\TelegramChat;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Romanlazko\Telegram\App\Bot;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\App\Entities\Response;

class PublishAnnouncementJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public $announcement_id, public $lang = 'ru')
    {
        
    }

    public function handle(): void
    {
        $announcement = Announcement::where('id', $this->announcement_id)->with('channels')->first();

        if ($announcement->status->isAwaitPublication() AND $this->publishOnTelegram($announcement)) {
            $announcement->published();
        }
    }

    public function publishOnTelegram(Announcement $announcement): bool
    {
        if ($announcement->channels->isEmpty()) {
            return true;
        }

        $announcement_channels = $announcement->channels->filter(function ($channel) {
            return ! $channel->status?->isPublished();
        });
        
        foreach ($announcement_channels as $announcement_channel) {
            try {
                $response = $this->publishOnChannel($announcement, $announcement_channel);

                $announcement_channel->published([
                    'response' => $response->getOk(),
                    'response_description' => $response->getDescription(),
                ]);
            } catch (Exception $exception) {
                $announcement_channel->publishingFailed([
                    'exception_message' => $exception->getMessage(),
                    'exception_trace' => $exception->getTraceAsString(),
                    'exception_code' => $exception->getCode(),
                    'exception_file' => $exception->getFile(),
                    'exception_line' => $exception->getLine(),
                ]);
    
                throw new Exception($exception->getMessage());
            }
        }
        return true;
    }

    public function publishOnChannel(Announcement $announcement, AnnouncementChannel $channel): Response
    {
        app()->setLocale($this->lang);
        
        $bot = new Bot($channel->channel->bot->token);

        $buttons = BotApi::inlineKeyboardWithLink(
            array('text' => "Посмотреть объявление", 'url' => route('announcement.show', $announcement)),
        );

        return $bot::sendPhoto([
            'caption'                   => view('telegram.announcement.show', ['announcement' => $announcement])->render(),
            'chat_id'                   => $channel->channel->chat_id,
            'photo'                     => $announcement->getFirstMediaUrl('announcements'),
            'parse_mode'                => 'HTML',
            'disable_web_page_preview'  => 'true',
            'reply_markup'              => $buttons,
        ]);
    }

    public function failed(Exception $exception): void
    {
        Announcement::find($this->announcement_id)->publishingFailed([
            'exception_message' => $exception->getMessage(),
            'exception_trace' => $exception->getTraceAsString(),
            'exception_code' => $exception->getCode(),
            'exception_file' => $exception->getFile(),
            'exception_line' => $exception->getLine(),
        ]);
    }
}
