<?php

namespace App\Models\Attribute;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeSection extends Model
{
    use HasFactory; 
    use SoftDeletes;

    public $guarded = [];

    public $casts = [
        'alternames' => 'array',
    ];

    public function getNameAttribute(): ?string
    {
        return $this->alternames[app()->getLocale()] ?? $this->alternames['en'] ?? null;
    }
    
    //SCOPES

    public function scopeIsActive($query)
    {
        return $query->where('is_active', true);
    }
}
