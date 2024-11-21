<?php

namespace App\Models\Messanger;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Thread extends Model
{
    use HasFactory; 
    use SoftDeletes;

    protected $guarded = [];

    protected static function booted(): void
    {
        static::deleted(function (Thread $thread) {
            $thread->messages()->delete();
        });

        static::forceDeleted(function (Thread $thread) {
            $thread->messages()->forceDelete();
        });

        static::restored(function (Thread $thread) {
            $thread->messages()->restore();
        });
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function latestMessage()
    {
        return $this->hasOne(Message::class)->latestOfMany();
    }

    public function getRecipientAttribute()
    {
        return $this->users->where('id', '!=', auth()->user()->id)->first();
    }
}
