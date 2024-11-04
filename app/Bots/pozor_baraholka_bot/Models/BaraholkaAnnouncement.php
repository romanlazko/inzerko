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

    public function chat()
    {
        return $this->belongsTo(TimewebTelegramChat::class, 'chat', 'id');
    }

    public function prepare()
    {
        return Announcement::fromObject($this)->prepare();
    }
}
