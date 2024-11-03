<?php

namespace App\Models;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\Statusable;
use App\Models\Announcement;
use App\Models\TelegramChat;
use App\Jobs\PublishAnnouncementOnTelegramChannelJob;

class AnnouncementChannel extends Model
{
    use HasFactory;
    use Statusable;

    protected $guarded = [];

    protected $casts = [
        'current_status' => Status::class,
        'info' => 'array',
    ];

    public function announcement()
    {
        return $this->belongsTo(Announcement::class, 'announcement_id', 'id');
    }

    public function telegram_chat()
    {
        return $this->belongsTo(TelegramChat::class, 'telegram_chat_id', 'id');
    }

    public function publish($dispatch = 'dispatch')
    {
        $result = $this->updateStatus(Status::await_publication);

        if ($result) {
            PublishAnnouncementOnTelegramChannelJob::$dispatch($this->id);
        }

        return $result;
    }

    // public function publishSync()
    // {
    //     $result = $this->updateStatus(Status::await_publication);

    //     if ($result) {
    //         PublishAnnouncementOnTelegramChannelJob::dispatchSync($this->id);
    //     }

    //     return $result;
    // }

    public function published(array|\Throwable|\Error $info = [])
    {
        return $this->updateStatus(Status::published, $info);
    }

    public function publishingFailed(array|\Throwable|\Error $info = [])
    {
        return $this->updateStatus(Status::publishing_failed, $info);
    }

    public function scopeNoPublished($query)
    {
        return $query->where('current_status', '!=', Status::published);
    }
}
