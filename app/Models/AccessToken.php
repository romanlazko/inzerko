<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Support\Facades\Hash;

class AccessToken extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'expires_at' => 'datetime',
    ];

    public function tokenable(): MorphTo
    {
        return $this->morphTo();
    }

    public static function findByToken($token): ?AccessToken
    {
        return static::hasToken($token)->first();
    }

    public function isExpired(): bool
    {
        return $this->expires_at < now();
    }

    public function scopeHasToken($query, $token)
    {
        return $query->where('token', $token)->where('expires_at', '>=', now());
    }
}
