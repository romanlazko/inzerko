<?php

namespace App\Models;

use App\AttributeType\AttributeFactory;
use App\Services\Actions\CategoryAttributeService;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class Feature extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'translated_value' => 'array'
    ];

    protected $with = [
        'attribute',
    ];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function announcement()
    {
        return $this->belongsTo(Announcement::class);
    }

    public function attribute_option()
    {
        return $this->belongsTo(AttributeOption::class);
    }

    public function setTranslatedValueAttribute($value)
    {
        $this->attributes['translated_value'] = json_encode($value, JSON_UNESCAPED_UNICODE);
    }

    public function getValueAttribute(): ?string
    {
        return AttributeFactory::getValueByFeature($this->attribute, $this);
    }

    public function getOriginalAttribute(): mixed
    {
        return AttributeFactory::getOriginalByFeature($this->attribute, $this);
    }

    public function getLabelAttribute()
    {
        return $this->attribute->label;
    }

    public function scopeForAnnouncementCard($query)
    {
        $title_price_attributes = Cache::remember('title_price_attributes', config('cache.ttl'), function () {
            return Attribute::whereHas('group', fn ($query) => 
                $query->whereIn('slug', ['title', 'price'])
            )
            ->pluck('id');
        });

        $query->with('attribute_option:id,alternames')
            ->whereIn('attribute_id', $title_price_attributes);
    }

    public function scopeForModerationCard($query)
    {
        $title_price_attributes = Cache::remember('moderation_attributes', config('cache.ttl'), function () {
            return Attribute::whereHas('group', fn ($query) => 
                $query->whereIn('slug', ['title', 'description'])
            )
            ->pluck('id');
        });

        $query->whereIn('attribute_id', $title_price_attributes);
    }
}