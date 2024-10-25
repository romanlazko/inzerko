<?php

namespace App\Models;

use App\Models\Traits\CacheRelationship;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Staudenmeir\EloquentJsonRelations\HasJsonRelationships;

class Attribute extends Model
{
    use HasFactory; use SoftDeletes; use HasJsonRelationships; use CacheRelationship;

    public $guarded = [];

    public $casts = [
        'alterlabels' => 'array',
        'visible' => 'array',
        'hidden' => 'array',
        'rules' => 'array',
        'altersuffixes' => 'array',
        'filter_layout' => 'array',
        'create_layout' => 'array',
        'show_layout' => 'array',
        'group_layout' => 'array',
    ];

    public function getVisibleConditionAttribute()
    {
        return json_decode($this->attributes['visible'], true);
    }

    public function attribute_options()
    {
        return $this->hasMany(AttributeOption::class);
    }

    public function getAttributeOptionsAttribute()
    {
        return $this->cacheRelation('attribute_options');
    }

    public function getLabelAttribute()
    {
        return $this->alterlabels[app()->getLocale()] ?? $this->alterlabels['en'] ?? null;
    }

    public function getSuffixAttribute()
    {
        return $this->altersuffixes[app()->getLocale()] ?? $this->altersuffixes['en'] ?? null;
    }

    public function scopeFindByName($query, $name)
    {
        return $query->whereName($name)->first();
    }

    public function categories() 
    {
        return $this->belongsToMany(Category::class);
    }

    public function getCategoriesAttribute()
    {
        return $this->cacheRelation('categories');
    }

    public function filterSection()
    {
        return $this->belongsTo(AttributeSection::class, 'filter_layout->section_id');
    }

    public function getFilterSectionAttribute()
    {
        return $this->cacheRelation('filterSection');
    }

    public function createSection()
    {
        return $this->belongsTo(AttributeSection::class, 'create_layout->section_id');
    }

    public function getCreateSectionAttribute()
    {
        return $this->cacheRelation('createSection');
    }

    public function showSection()
    {
        return $this->belongsTo(AttributeSection::class, 'show_layout->section_id');
    }

    public function getShowSectionAttribute()
    {
        return $this->cacheRelation('showSection');
    }

    public function group()
    {
        return $this->belongsTo(AttributeGroup::class, 'group_layout->group_id');
    }

    public function getGroupAttribute()
    {
        return $this->cacheRelation('group');
    }

    public function sortings()
    {
        return $this->hasMany(Sorting::class);
    }
}
