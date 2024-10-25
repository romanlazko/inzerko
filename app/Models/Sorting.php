<?php

namespace App\Models;

use App\Models\Traits\CacheRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

class Sorting extends Model
{
    use HasFactory; use SoftDeletes; use CacheRelationship;

    public $guarded = [];

    public $casts = [
        'alternames' => 'array',
    ];

    // protected static function booted(): void
    // {
    //     static::addGlobalScope('default', function (Builder $builder) {
    //         $builder->where('default', true);
    //     });
    // }

    public function getNameAttribute()
    {
        return $this->alternames[app()->getLocale()] ?? $this->alternames['en'] ?? null;
    }

    public function categories() 
    {
        return $this->belongsToMany(Category::class);
    }

    public function getCategoriesAttribute()
    {
        return $this->cacheRelation('categories');
    }

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function getAttributeAttribute()
    {
        return $this->cacheRelation('attribute');
    }

    public static function default()
    {
        return Cache::remember('default_sorting', config('cache.ttl'), fn () => static::firstWhere('is_default', true)) ?? null;
    }
}
