<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Page extends Model
{
    use HasFactory; use HasSlug; use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'alternames' => 'array',
        'is_active' => 'boolean',
        'is_header' => 'boolean',
        'is_footer' => 'boolean',
    ];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function getNameAttribute()
    {
        return $this->alternames[app()->getLocale()] ?? $this->alternames['en'] ?? $this->title ?? null;
    }

    public function blocks()
    {
        return $this->hasMany(Block::class);
    }
}
