<?php

namespace App\Models\Traits;

use App\Enums\Status as StatusEnum;
use App\Models\Status as StatusModel;


trait Statusable
{
    public function statuses()
    {
        return $this->morphMany(StatusModel::class, 'statusable');
    }

    public function currentStatus()
    {
        return $this->morphOne(StatusModel::class, 'statusable')->orderBy('id', 'desc')->latestOfMany();
    }

    public function getStatusAttribute(): StatusEnum
    {
        return $this->current_status;
    }

    public function scopeStatus($query, StatusEnum $status)
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