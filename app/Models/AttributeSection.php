<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeSection extends Model
{
    use HasFactory; use SoftDeletes;

    public $guarded = [];

    public $casts = [
        'alternames' => 'array',
    ];

    public function getNameAttribute()
    {
        return $this->alternames[app()->getLocale()] ?? $this->alternames['en'] ?? null;
    }

    public function attributes()
    {
        return $this->hasMany(Attribute::class);
    }
}
