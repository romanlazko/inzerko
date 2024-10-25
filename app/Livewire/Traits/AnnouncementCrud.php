<?php

namespace App\Livewire\Traits;

use App\AttributeType\AttributeFactory;
use App\Models\Announcement;
use App\Models\Attribute;
use Illuminate\Database\Eloquent\Builder;
use App\Models\TelegramChat;
use Illuminate\Support\Facades\DB;
use App\Services\Actions\CategoryAttributeService;

trait AnnouncementCrud
{
    public function createAnnouncement(object $data): ?Announcement
    {
        return DB::transaction(function () use ($data) {
            $announcement = auth()->user()->announcements()->create([
                'geo_id' => $data->geo_id,
            ]);
    
            if ($announcement) {
                $announcement->categories()->sync($data->categories);
                $announcement->features()->createMany($this->getFeatures($data->categories, $data->attributes));
                $announcement->channels()->createMany($this->getChannels($announcement));
                
                if (isset($data->attachments) AND !empty($data->attachments)) {
                    foreach ($data->attachments as $attachment) {
                        $announcement->addMedia($attachment)->toMediaCollection('announcements', 's3');
                    }
                }
    
                $announcement->moderate();
            }

            return $announcement;
        });
    }

    private function getFeatures(array $categories, array $attributes) : array
    {
        if (empty($attributes)) {
            return [];
        }
        
        return CategoryAttributeService::forCreate($categories)
            ->map(function ($attribute) use ($attributes) {
                return isset($attributes[$attribute->name]) ? AttributeFactory::getCreateSchema($attribute, $attributes) : null;
            })
            ->filter()
            ->all();
    }

    private function getChannels($announcement) : array
    {
        $locationChannels = TelegramChat::whereHas('geo', fn ($query) => $query->radius($announcement->geo->latitude, $announcement->geo->longitude, 30))
            ->whereHas('categories', fn ($query) => $query->whereIn('category_id', $announcement->categories->pluck('id')))
            ->get();

        if ($locationChannels->isEmpty()) {
            $locationChannels = TelegramChat::whereHas('categories', fn ($query) => 
                $query->whereIn('category_id', $announcement->categories->pluck('id'))
            )
            ->get();
        }

        return $locationChannels->map(fn ($channel) => ['telegram_chat_id' => $channel->id])->all();
    }
}