<?php

namespace App\Models;

use App\AttributeType\AttributeFactory;
use App\Services\Actions\CategoryAttributeService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class Feature extends Model
{
    use HasFactory; 
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'translated_value' => 'array'
    ];

    protected $with = ['attribute', 'attribute_option'];

    //RELATIONS

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    public function announcement(): BelongsTo
    {
        return $this->belongsTo(Announcement::class);
    }

    public function attribute_option(): BelongsTo
    {
        return $this->belongsTo(AttributeOption::class);
    }

    //ATTRIBUTES

    public function setTranslatedValueAttribute($value): void
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

    public function getLabelAttribute(): ?string
    {
        return $this->attribute->label;
    }

    //SCOPES

    public function scopeForAnnouncementCard($query): Builder
    {
        $title_price_attributes = Cache::remember('title_price_attributes', config('cache.ttl'), function () {
            return Attribute::whereHas('group', fn ($query) => 
                $query->whereIn('slug', ['title', 'price'])
            )
            ->pluck('id');
        });

        return $query->whereIn('attribute_id', $title_price_attributes)
            ->with('attribute', 'attribute_option:id,alternames');
    }

    public function scopeForModerationCard($query): Builder
    {
        $title_price_attributes = Cache::remember('moderation_attributes', config('cache.ttl'), function () {
            return Attribute::whereHas('group', fn ($query) => 
                $query->whereIn('slug', ['title', 'description'])
            )
            ->pluck('id');
        });

        return $query->whereIn('attribute_id', $title_price_attributes)
            ->with('attribute', 'attribute_option:id,alternames');
    }
}