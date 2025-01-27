<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\Status as StatusEnum;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends Model
{
    use HasFactory; 
    use SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'status' => StatusEnum::class,
        'info' => 'array',
    ];

    protected static function booted(): void
    {
        static::creating(function ($model) {
            $model->statusable->update([
                'current_status' => $model->status,
                'updated_at' => now(),
            ]);
        });

        static::updating(function ($model) {
            $model->statusable->update([
                'current_status' => $model->status,
                'updated_at' => now(),
            ]);
        });
    }

    public function statusable(): MorphTo
    {
        return $this->morphTo();
    }
}
