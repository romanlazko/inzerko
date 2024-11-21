<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeOption extends Model
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

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }
}
