<?php

namespace App\Jobs;

use App\Models\Announcement;
use App\Models\User;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Romanlazko\Telegram\App\Bot;
use Romanlazko\Telegram\App\BotApi;

class SendMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $announcement;
    private $recipient;
    /**
     * Create a new job instance.
     */
    public function __construct(private int $announcement_id, private string $message, private string $recipient_id)
    {
        $this->recipient = User::find($this->recipient_id);
        $this->announcement = Announcement::find($this->announcement_id);
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->sendMessageToTelegram(); 
        $this->sendMessageToEmail();
    }

    public function sendMessageToTelegram()
    {
        $bot = new Bot('5981959980:AAHtBsJcUuXBfuR6FVgFfNh31r2jQwlF8io');

        $text = implode("\n", [
            "*You have a new message about the announcement:*"."\n",
            "{$this->announcement->title}"."\n",
            "{$this->message}"
        ]);

        $bot::sendMessage([
            'text'                      => $text,
            'chat_id'                   => '544883527',
            'parse_mode'                => 'HTML',
            'disable_web_page_preview'  => 'true',
            'parse_mode'                => 'Markdown',
        ]);
    }

    public function sendMessageToEmail()
    {
        // $this->recipient->notify(new \App\Notifications\TelegramAnnouncement($this->announcement, $this->message));
    }

    public function failed(Exception $exception)
    {
        
    }
}
