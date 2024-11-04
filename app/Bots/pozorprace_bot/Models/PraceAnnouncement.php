<?php

namespace App\Bots\pozorprace_bot\Models;

use App\Bots\pozorprace_bot\Models\TimewebTelegramChat;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PraceAnnouncement extends Model
{
    use HasFactory; use SoftDeletes;

    protected $connection = 'timeweb_mysql';

    protected $guarded = [];

    public function chat()
    {
        return $this->belongsTo(TimewebTelegramChat::class, 'chat', 'id');
    }
}
