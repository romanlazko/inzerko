<?php

namespace App\Models;

use App\Models\Attribute\Attribute;
use App\Models\Traits\CacheRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

class Sorting extends Model
{
    use HasFactory; 
    use SoftDeletes; 
    use CacheRelationship;

    public $guarded = [];

    public $casts = [
        'alternames' => 'array',
    ];

    public function getNameAttribute(): ?string
    {
        return $this->alternames[app()->getLocale()] ?? $this->alternames['en'] ?? null;
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function getCategoriesAttribute(): Collection
    {
        return $this->cacheRelation('categories');
    }

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    public function getAttributeAttribute(): ?Attribute
    {
        return $this->cacheRelation('attribute');
    }

    public static function default(): ?Sorting
    {
        return Cache::remember('default_sorting', config('cache.ttl'), fn () => 
            static::firstWhere('is_default', true)
        ) ?? null;
    }
}
