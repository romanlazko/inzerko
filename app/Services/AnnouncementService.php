<?php 

namespace App\Services;

use App\AttributeType\AttributeFactory;
use App\Models\Announcement;
use App\Models\Category;
use App\Models\TelegramChat;
use App\Services\Actions\AttributesByCategoryService;
use Illuminate\Support\Facades\DB;

class AnnouncementService
{
    public static function store(object $data): ?Announcement
    {
        return DB::transaction(function () use ($data) {
            $announcement = auth()->user()->announcements()->create([
                'geo_id' => $data->geo_id,
                'category_id' => $data->category_id,
            ]);
    
            if ($announcement) {
                $announcement->features()->createMany(self::getFeatures($announcement->category, $data->attributes));
                $announcement->channels()->createMany(self::getChannels($announcement));
    
                $announcement->maskContacts();
            }

            return $announcement;
        });
    }

    public static function update(Announcement $announcement, object $data): ?Announcement
    {
        return DB::transaction(function () use ($data, $announcement) {
            $announcement = tap($announcement, fn ($announcement) => $announcement->update([
                'geo_id' => $data->geo_id,
                'category_id' => $data->category_id,
            ]));

            $announcement->features()->forceDelete();
            $announcement->channels()->forceDelete();

            if ($announcement) {
                $announcement->features()->createMany(self::getFeatures($announcement->category, $data->attributes));
                $announcement->channels()->createMany(self::getChannels($announcement));
    
                $announcement->maskContacts();
            }

            return $announcement;
        });
    }

    private static function getFeatures(Category $category, array $attributes) : array
    {
        return AttributesByCategoryService::forCreate($category)
            ->map(fn ($attribute) => 
                AttributeFactory::getCreateSchema($attribute, $attributes)
            )
            ->filter()
            ->all();
    }

    private static function getChannels($announcement) : array
    {
        $locationChannels = TelegramChat::whereHas('geo', fn ($query) => $query->radius($announcement->geo->latitude, $announcement->geo->longitude, 30))
            ->whereHas('categories', fn ($query) => $query->whereIn('category_id', $announcement->categories?->pluck('id')))
            ->get();

        if ($locationChannels->isEmpty()) {
            $locationChannels = TelegramChat::whereHas('categories', fn ($query) => 
                $query->whereIn('category_id', $announcement->categories?->pluck('id'))
            )
            ->get();
        }

        return $locationChannels->map(fn ($channel) => ['telegram_chat_id' => $channel->id])->all();
    }
}