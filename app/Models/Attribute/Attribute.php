<?php

namespace App\Models\Attribute;

use App\Models\Category;
use App\Models\Sorting;
use App\Models\Traits\CacheRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class Attribute extends Model
{
    use HasFactory; 
    use SoftDeletes;
    use HasJsonRelationships; 
    use CacheRelationship;

    public $guarded = [];

    public $casts = [
        'alterlabels' => 'array',
        'visible' => 'array',
        'hidden' => 'array',
        'rules' => 'array',
        'altersuffixes' => 'array',
        'alterprefixes' => 'array',
        'filter_layout' => 'array',
        'create_layout' => 'array',
        'show_layout' => 'array',
        'group_layout' => 'array',
    ];

    protected static function booted(): void
    {
        static::deleted(function (Attribute $attribute) {
            $attribute->attribute_options()->delete();
        });

        static::forceDeleted(function (Attribute $attribute) {
            $attribute->attribute_options()->forceDelete();
        });

        static::restored(function (Attribute $attribute) {
            $attribute->attribute_options()->restore();
        });
    }

    //RELATIONS

    public function attribute_options(): HasMany
    {
        return $this->hasMany(AttributeOption::class);
    }

    public function getAttributeOptionsAttribute(): Collection
    {
        return $this->cacheRelation('attribute_options');
    }

    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }

    public function getCategoriesAttribute(): Collection
    {
        return $this->cacheRelation('categories');
    }

    public function filterSection(): BelongsTo
    {
        return $this->belongsTo(AttributeSection::class, 'filter_layout->section_id');
    }

    public function getFilterSectionAttribute(): ?AttributeSection
    {
        return $this->cacheRelation('filterSection');
    }

    public function createSection(): BelongsTo
    {
        return $this->belongsTo(AttributeSection::class, 'create_layout->section_id');
    }

    public function getCreateSectionAttribute(): ?AttributeSection
    {
        return $this->cacheRelation('createSection');
    }

    public function showSection(): BelongsTo
    {
        return $this->belongsTo(AttributeSection::class, 'show_layout->section_id');
    }

    public function getShowSectionAttribute(): ?AttributeSection
    {
        return $this->cacheRelation('showSection');
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(AttributeGroup::class, 'group_layout->group_id');
    }

    public function getGroupAttribute(): ?AttributeGroup
    {
        return $this->cacheRelation('group');
    }

    public function sortings(): HasMany
    {
        return $this->hasMany(Sorting::class);
    }

    public function getSortingsAttribute(): Collection
    {
        return $this->cacheRelation('sortings');
    }

    //ATTRIBUTES

    public function getLabelAttribute(): ?string
    {
        return $this->alterlabels[app()->getLocale()] ?? $this->alterlabels['en'] ?? null;
    }

    public function getPrefixAttribute(): ?string
    {
        return $this->alterprefixes[app()->getLocale()] ?? $this->alterprefixes['en'] ?? null;
    }

    public function getSuffixAttribute(): ?string
    {
        return $this->altersuffixes[app()->getLocale()] ?? $this->altersuffixes['en'] ?? null;
    }

    //SCOPES

    public function scopeIsActive($query)
    {
        return $query->where('is_active', true);
    }
}
