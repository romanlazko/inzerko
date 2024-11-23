<?php

namespace App\Models;
use App\Enums\Status;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\Traits\Statusable;
use App\Models\Announcement;
use App\Models\TelegramChat;
use App\Jobs\PublishAnnouncementOnTelegramChannelJob;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Status as StatusModel;

class AnnouncementChannel extends Model
{
    use HasFactory; 
    use Statusable;
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'current_status' => Status::class,
        'info' => 'array',
    ];

    public function announcement(): BelongsTo
    {
        return $this->belongsTo(Announcement::class, 'announcement_id', 'id');
    }

    public function telegram_chat(): BelongsTo
    {
        return $this->belongsTo(TelegramChat::class, 'telegram_chat_id', 'id');
    }

    public function publish($dispatch = 'dispatch'): StatusModel
    {
        $result = $this->updateStatus(Status::await_publication);

        if ($result) {
            PublishAnnouncementOnTelegramChannelJob::$dispatch($this->id);
        }

        return $result;
    }

    public function published(array|\Throwable|\Error $info = []): StatusModel
    {
        return $this->updateStatus(Status::published, $info);
    }

    public function publishingFailed(array|\Throwable|\Error $info = []): StatusModel
    {
        return $this->updateStatus(Status::publishing_failed, $info);
    }

    public function scopeNoPublished($query): Builder
    {
        return $query->where('current_status', '!=', Status::published);
    }
}
