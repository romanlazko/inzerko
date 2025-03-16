<?php

namespace App\Models;

use Akuechler\Geoly;
use App\Bots\inzerko_bot\Facades\Inzerko;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Romanlazko\Telegram\Models\TelegramChat as Model;
use Romanlazko\Telegram\Models\TelegramMessage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Laravolt\Avatar\Facade as Avatar;

class TelegramChat extends Model implements HasMedia
{
    use InteractsWithMedia; 
    use Geoly;

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

    public function messages(): HasMany
    {
        return $this->hasMany(TelegramMessage::class, 'chat', 'id');
    }

    public function layouts()
    {
        return $this->morphToMany(HtmlLayout::class, 'html_layoutable');
    }

    public function getLayoutAttribute()
    {
        return $this->layouts()->first();
    }

    public function latestMessage(): HasOne
    {
        return $this->hasOne(TelegramMessage::class, 'chat', 'id')->latestOfMany();
    }

    public function geo(): BelongsTo
    {
        return $this->belongsTo(Geo::class);
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'category_channel', 'telegram_chat_id', 'category_id');
    }

    public function getAvatarAttribute(): string
    {
        if ($this->getMedia('avatar')->isEmpty()) {
            if ($this->photo) {
                $photo_url = Inzerko::getPhoto(['file_id' => $this->photo]);

                $this->addMediaFromUrl($photo_url)->toMediaCollection('avatar');
            }
            else {
                $this->addMediaFromBase64(Avatar::create("$this->first_name $this->last_name"))->toMediaCollection('avatar');
            }

            $this->load('media');
        }

        return $this->getFirstMediaUrl('avatar', 'thumb');
    }
}
