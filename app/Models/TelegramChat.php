<?php

namespace App\Models;

use Akuechler\Geoly;
use App\Bots\inzerko_bot\Facades\Inzerko;
use App\Enums\Status;
use Romanlazko\Telegram\App\BotApi;
use Romanlazko\Telegram\Models\TelegramChat as Model;
use Romanlazko\Telegram\Models\TelegramMessage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Laravolt\Avatar\Facade as Avatar;

class TelegramChat extends Model implements HasMedia
{
    use InteractsWithMedia, Geoly;

    // protected $casts = [
    //     'status' => Status::class,
    // ];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('avatar')
            ->singleFile()
            ->registerMediaConversions(function (Media $media) {
                $this
                    ->addMediaConversion('thumb')
                    ->keepOriginalImageFormat()
                    ->width(100)
                    ->height(100);
            });
    }

    public function messages()
    {
        return $this->hasMany(TelegramMessage::class, 'chat', 'id');
    }

    public function latestMessage()
    {
        return $this->hasOne(TelegramMessage::class, 'chat', 'id')->latestOfMany();
    }

    public function geo()
    {
        return $this->belongsTo(Geo::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_channel', 'telegram_chat_id', 'category_id');
    }

    public function getAvatarAttribute()
    {
        if ($this->getMedia('avatar', 'thumb')->isEmpty()) {
            if ($this->photo) {
                $photo_url = Inzerko::getPhoto(['file_id' => $this->photo]);

                $this->addMediaFromUrl($photo_url)->toMediaCollection('avatar');
            }
            else {
                $this->addMediaFromBase64(Avatar::create("$this->first_name $this->last_name"))->toMediaCollection('avatar');
            }
        }

        return $this->getFirstMedia('avatar', 'thumb');
    }
}
