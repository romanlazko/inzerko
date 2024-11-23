<?php

namespace App\Models;

use App\Enums\Status;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use OwenIt\Auditing\Auditable as AuditingAuditable;
use OwenIt\Auditing\Contracts\Auditable;
use RalphJSmit\Laravel\SEO\Support\HasSEO;
use RalphJSmit\Laravel\SEO\Support\SEOData;
use App\Models\TelegramChat;
use App\Models\Traits\AnnouncementSearch;
use App\Models\Traits\AnnouncementModeration;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use App\Models\Traits\Statusable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Prunable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Collection;
use Stringable;

class Announcement extends Model implements HasMedia, Auditable
{
    use HasSlug;
    use HasFactory; 
    use SoftDeletes;
    use InteractsWithMedia; 
    use AuditingAuditable; 
    use HasSEO; 
    use AnnouncementSearch;
    use AnnouncementModeration; 
    use Statusable;
    use Prunable;

    protected $guarded = [];

    protected $fillable = [
        'user_id',
        'slug',
        'category_id',
        'geo_id',
        'current_status',
    ];

    protected $casts = [
        'current_status' => Status::class,
    ];

    protected static function booted(): void
    {
        static::created(function (Announcement $announcement) {
            $announcement->updateStatus(Status::created, ['message' => 'Announcement created']);
        });

        static::deleted(function (Announcement $announcement) {
            $announcement->votes()->delete();
            $announcement->features()->delete();
            $announcement->channels()->delete();
            $announcement->statuses()->delete();
        });

        static::forceDeleted(function (Announcement $announcement) {
            $announcement->votes()->forceDelete();
            $announcement->features()->forceDelete();
            $announcement->channels()->forceDelete();
            $announcement->statuses()->forceDelete();
        });

        static::restored(function (Announcement $announcement) {
            $announcement->user()->restore();
            $announcement->chat()->restore();
            $announcement->votes()->restore();
            $announcement->features()->restore();
            $announcement->category()->restore();
            $announcement->channels()->restore();
            $announcement->statuses()->restore();
        });
    }

    public function prunable(): Builder
    {
        return static::where('deleted_at', '<=', now()->subMonth());
    }

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function ($model) {
                return $model?->title ?? $model->uuid;
            })
            ->preventOverwrite()
            ->skipGenerateWhen(function () {
                return is_array($this->features) ?? $this->features?->isEmpty();
            })
            ->doNotGenerateSlugsOnCreate()
            ->saveSlugsTo('slug');
    }

    public function registerMediaConversions(Media $media = null): void
    {
        $this
            ->addMediaConversion('responsive-images')
            ->format('webp')
            ->withResponsiveImages();
        
        $this
            ->addMediaConversion('medium')
            ->format('webp')
            ->withResponsiveImages()
            ->width(250)
            ->height(250);

        $this
            ->addMediaConversion('thumb')
            ->format('webp')
            ->width(100)
            ->height(100);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('announcements')
            ->useFallbackUrl('/images/no-photo.jpg');
    }

    //RELATIONS

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function chat(): BelongsTo
    {
        return $this->belongsTo(TelegramChat::class, 'telegram_chat_id', 'id');
    }

    public function features(): HasMany
    {
        return $this->hasMany(Feature::class);
    }

    public function geo(): BelongsTo
    {
        return $this->belongsTo(Geo::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(Vote::class);
    }

    public function userVotes(): HasOne
    {
        return $this->votes()->one()->where('user_id', auth()->id());
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function channels(): HasMany
    {
        return $this->hasMany(AnnouncementChannel::class);;
    }

    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    //ATTRIBUTES

    public function getCategoriesAttribute(): Collection
    {
        return $this->category?->parentsAndSelf;
    }

    public function getSectionByName(string $name): Collection
    {
        return $this->features->groupBy('attribute.showSection.slug')
            ?->get($name)
            ?->sortBy('attribute.show_layout.order_number');
    }


    public function getGroupByName(string $name): Collection
    {
        return $this->features->groupBy('attribute.group.slug')
            ?->get($name)
            ?->sortBy('attribute.group_layout.order_number');

        return $group;
    }

    public function getTitleAttribute(): Stringable
    {
        $group = $this->getGroupByName('title');
        
        return str($group?->pluck('value')->implode(' '));
    }
    public function getPriceAttribute(): Stringable
    {
        $group = $this->getGroupByName('price');

        return str($group?->pluck('value')->implode(' '));
    }

    public function getDescriptionAttribute(): Stringable
    {
        $group = $this->getGroupByName('description');

        return str($group?->pluck('value')->implode(' '))->sanitizeHtml();
    }

    //SEO

    public function getDynamicSEOData(): SEOData
    {
        return new SEOData(
            title: $this->title,
            description: $this->description,
            author: $this->user?->name,
            image: url($this->getFirstMediaUrl('announcements')),
            url: url()->current(),
            enableTitleSuffix: true,
            site_name: config('app.name'),
            published_time: $this->created_at,
            modified_time: $this->updated_at,
            locale: app()->getLocale(),
            section: $this->categories->pluck('name')->implode(', '),
            tags: $this->categories->pluck('name')->toArray(),
            openGraphTitle: $this->title,
        );
    }
}
