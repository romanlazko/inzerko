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

class Announcement extends Model implements HasMedia, Auditable
{
    use HasSlug, HasFactory; 
    use SoftDeletes;
    use InteractsWithMedia; 
    use AuditingAuditable; 
    use HasSEO; 
    use AnnouncementSearch;
    use AnnouncementModeration; 
    use Statusable;

    protected $guarded = [];

    protected $fillable = [
        'user_id',
        'slug',
        'geo_id',
        'current_status',
        'category_id',
    ];

    protected $casts = [
        'current_status' => Status::class,
    ];

    protected static function booted(): void
    {
        static::created(function (Announcement $announcement) {
            $announcement->updateStatus(Status::created, ['message' => 'Announcement created']);
        });
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

    public function chat()
    {
        return $this->belongsTo(TelegramChat::class, 'telegram_chat_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function features()
    {
        return $this->hasMany(Feature::class);
    }

    public function geo()
    {
        return $this->belongsTo(Geo::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function userVotes()
    {
        return $this->votes()->one()->where('user_id', auth()->id());
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getCategoriesAttribute()
    {
        return $this->category?->parentsAndSelf;
    }

    public function channels()
    {
        return $this->hasMany(AnnouncementChannel::class);;
    }

    public function getFeatureByName(string $name)
    {
        return $this->features->firstWhere('attribute.name', $name);
    }

    public function getSectionByName(string $name)
    {
        return $this->features->groupBy('attribute.showSection.slug')
            ?->get($name)
            ?->sortBy('attribute.show_layout.order_number');
    }


    public function getGroupByName(string $name)
    {
        return $this->features->groupBy('attribute.group.slug')
            ?->get($name)
            ?->sortBy('attribute.group_layout.order_number');
    }

    public function getTitleAttribute()
    {
        $group = $this->getGroupByName('title');
        
        return $group?->pluck('value')->implode($this->getGroupSeparator($group));
    }
    public function getPriceAttribute()
    {
        $group = $this->getGroupByName('price');

        return $group?->pluck('value')->implode($this->getGroupSeparator($group));
    }

    public function getDescriptionAttribute()
    {
        $group = $this->getGroupByName('description');

        return str($group?->pluck('value')->implode($this->getGroupSeparator($group)))->sanitizeHtml();
    }

    private function getGroupSeparator($group)
    {
        return $group->first()?->attribute?->group?->separator . ' ';
    }

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

    public function remove()
    {
        $this->votes()->delete();
        $this->features()->delete();
        $this->channels()->delete();
        $this->categories()->detach();
        $this->delete();
    }
}
