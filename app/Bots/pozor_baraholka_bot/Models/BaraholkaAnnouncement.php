<?php

namespace App\Bots\pozor_baraholka_bot\Models;

use App\Bots\pozor_baraholka_bot\Http\DataTransferObjects\Announcement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Romanlazko\Telegram\Models\TelegramChat;

class BaraholkaAnnouncement extends Model
{
    protected $connection = 'timeweb_mysql';

    use HasFactory; use SoftDeletes;

    protected $guarded = [];

    public function photos()
    {
        return $this->hasMany(BaraholkaAnnouncementPhoto::class, 'announcement_id', 'id');
    }

    public function chat()
    {
        return $this->belongsTo(TelegramChat::class, 'telegram_chat_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(BaraholkaCategory::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(BaraholkaSubcategory::class);
    }

    public function dto()
    {
        return Announcement::fromObject($this);
    }

    public function prepare()
    {
        return Announcement::fromObject($this)->prepare();
    }

    public function getPhotoAttribute()
    {
        return Announcement::fromObject($this)->photos;
    }
}
