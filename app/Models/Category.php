<?php

namespace App\Models;

use App\Models\Traits\CacheRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Category extends Model implements HasMedia
{
    use HasFactory; use HasSlug; use SoftDeletes; use InteractsWithMedia; use HasSEO; use CacheRelationship;

    protected $guarded = [];

    protected $casts = [
        'alternames' => 'array',
        'is_active' => 'boolean',
    ];

    public static function all($columns = ['*'])
    {
        return Cache::remember('categories', config('cache.ttl'), function () {
            return parent::all();
        });
    }

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('slug')
            ->saveSlugsTo('slug');
    }

    public function registerMediaCollections(): void
    {
        $this
            ->addMediaCollection('categories')
            ->useDisk('aws_categories')
            ->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('thumb')
            ->format('webp')
            ->width(100)
            ->height(100);
    }

    public function announcements()
    {
        return $this->belongsToMany(Announcement::class);
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function getChildrenAttribute()
    {
        return $this->cacheRelation('children');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }

    public function getParentAttribute()
    {
        return $this->cacheRelation('parent');
    }

    public function siblings()
    {
        return $this->hasMany(Category::class, 'parent_id', 'parent_id');
    }

    public function getSiblingsAttribute()
    {
        return $this->cacheRelation('siblings');
    }

    public function channels()
    {
        return $this->belongsToMany(TelegramChat::class, 'category_channel', 'category_id', 'telegram_chat_id');
    }

    public function getChannelsAttribute()
    {
        return $this->cacheRelation('channels');
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class);
    }

    public function getAttributesAttribute()
    {
        return $this->cacheRelation('attributes');
    }

    public function getNameAttribute()
    {
        return $this->alternames[app()->getLocale()] ?? $this->alternames['en'] ?? null;
    }

    public function getNameWithAnnouncementCountAttribute()
    {
        return $this->name . ' (' . $this->announcements->count() . ')';
    }

    public function getParentsAndSelf()
    {
        return Cache::remember($this?->slug.'_category_parents_and_self', config('cache.ttl'), fn () => 
            collect([
                $this,
                ...$this->parent?->getParentsAndSelf() ?? []
            ])
        );
    }

    public function scopeIsActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getDynamicSEOData(): SEOData
    {
        return Cache::remember($this?->slug.'_category_seo_data', config('cache.ttl'), fn () => 
            new SEOData(
                title: $this->name,
                description: $this->name,
                image: $this->getFirstMediaUrl('categories'),
                url: url()->current(),
                enableTitleSuffix: true,
                site_name: config('app.name'),
                published_time: $this->created_at,
                modified_time: $this->updated_at,
                locale: app()->getLocale(),
                section: $this->children->pluck('name')->implode(', '),
                tags: $this->children->pluck('name')->toArray(),
            )
        );
    }
}
