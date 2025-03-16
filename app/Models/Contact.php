<?php

namespace App\Models;

use App\Enums\ContactTypeEnum;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $guarded = [];

    protected $casts = [
        'type' => ContactTypeEnum::class,
    ];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getUrlAttribute()
    {
        return $this->type->getPrefix() . $this->link;
    }

    public function getIconAttribute()
    {
        return $this->type->getIcon();
    }

    public function getColorAttribute()
    {
        return $this->type->getColor();
    }

    public function scopeType($query, ContactTypeEnum $type)
    {
        return $query->where('type', $type);
    }
}
