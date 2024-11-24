<?php

namespace App\Models\Traits;

use App\Enums\Status as StatusEnum;
use App\Models\Status as StatusModel;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Builder;

trait Statusable
{
    public function statuses(): MorphMany
    {
        return $this->morphMany(StatusModel::class, 'statusable');
    }

    public function currentStatus(): MorphOne
    {
        return $this->morphOne(StatusModel::class, 'statusable')->orderBy('id', 'desc')->latestOfMany();
    }

    public function getStatusAttribute(): ?StatusEnum
    {
        return $this->current_status;
    }

    public function scopeStatus($query, StatusEnum $status): Builder
    {
        return $query->where('current_status', $status);
    }

    public function updateStatus(int|string|StatusEnum $status, array|\Throwable|\Error $info = []): StatusModel
    {
        if (!($status instanceof StatusEnum)) {
            $status = StatusEnum::from($status);
        }

        if ($info instanceof \Throwable || $info instanceof \Error) {
            $info = [
                'message' => $info->getMessage(),
                'code' => $info->getCode(),
                'file' => $info->getFile(),
                'line' => $info->getLine(),
            ];
        }

        return $this->statuses()->create([
            'status' => $status,
            'info' => $info,
        ]);
    }
}