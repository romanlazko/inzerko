<?php

namespace Database\Factories;

use App\AttributeType\AttributeFactory;
use App\Livewire\Actions\Concerns\HasTypeOptions;
use App\Models\Announcement;
use App\Models\Category;
use App\Models\Geo;
use App\Models\TelegramChat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;
use App\Services\Actions\AttributesByCategoryService;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Announcement>
 */
class AnnouncementFactory extends Factory
{
    use HasTypeOptions;
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id'           => User::inRandomOrder()->first()->id,
            'geo_id'            => Geo::inRandomOrder()->first()->id,
            'created_at'        => now(),
            'updated_at'        => now(),
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Announcement $announcement) {
            try {
                DB::transaction(function () use ($announcement) {
                    $announcement->features()->createMany($this->getFeatures($announcement->category));
                    $announcement->channels()->createMany($this->getChannels($announcement));
                    $announcement->publish();
                });
            }

            catch (\Throwable $exception) {
                $announcement->delete();

                throw $exception;
            }
        });
    }

    private function getFeatures(Category $category) : array
    {   
        return AttributesByCategoryService::forCreate($category)
            ->map(fn ($attribute) => 
                AttributeFactory::getFakeData($attribute)
            )
            ->filter()
            ->all();
    }

    private function getChannels($announcement) : array
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
