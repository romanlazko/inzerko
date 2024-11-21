<?php

namespace App\Models;

use App\Models\Traits\CacheRelationship;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
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
    use HasFactory; 
    use HasSlug; 
    use SoftDeletes; 
    use InteractsWithMedia; 
    use HasSEO; 
    use CacheRelationship;

    protected $guarded = [];

    protected $casts = [
        'alternames' => 'array',
        'is_active' => 'boolean',
        'has_attachments' => 'boolean',
    ];

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
            ->useDisk('s3_categories')
            ->useFallbackUrl('/images/no-photo.jpg')
            ->singleFile();
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('thumb')
            ->format('webp')
            ->width(70)
            ->height(70);
    }

    //RELATIONS

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function getChildrenAttribute(): Collection
    {
        return $this->cacheRelation('children');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }

    public function getParentAttribute(): ?Category
    {
        return $this->cacheRelation('parent');
    }

    public function siblings(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id', 'parent_id');
    }

    public function getSiblingsAttribute(): Collection
    {
        return $this->cacheRelation('siblings');
    }

    public function channels(): BelongsToMany
    {
        return $this->belongsToMany(TelegramChat::class, 'category_channel', 'category_id', 'telegram_chat_id');
    }

    public function getChannelsAttribute(): Collection
    {
        return $this->cacheRelation('channels');
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class);
    }

    public function getAttributesAttribute(): Collection
    {
        return $this->cacheRelation('attributes');
    }

    //ATTRIBUTES

    public function getNameAttribute(): ?string
    {
        return $this->alternames[app()->getLocale()] ?? $this->alternames['en'] ?? null;
    }

    public function getParentsAndSelfAttribute(): Collection
    {
        $cacheKey = $this?->slug.'_category_parents_and_self';
        
        return Cache::remember($cacheKey, config('cache.ttl'), function () {
            return collect([
                $this,
                ...$this->parent?->parentsAndSelf ?? []
            ]);
        });
    }

    public function getChildrenAndSelfAttribute(): Collection
    {
        $cacheKey = $this?->slug.'_category_children_and_self';

        return Cache::remember($cacheKey, config('cache.ttl'), function () {
            return collect([
                $this,
                ...$this->children->map(fn ($child) => $child->childrenAndSelf)->flatten()
            ]);
        });
    }

    //SCOPES

    public function scopeIsActive($query): Builder
    {
        return $query->where('is_active', true);
    }

    //SEO  

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
