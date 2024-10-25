<?php

namespace Database\Factories;

use App\Enums\Status;
use App\Livewire\Actions\Concerns\HasTypeOptions;
use App\Models\Announcement;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Geo;
use App\Models\TelegramChat;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Services\Actions\CategoryAttributeService;

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
                    $announcement->addMedia(storage_path('images/no-photo.jpg'))->preservingOriginal()->toMediaCollection('announcements', 's3');
                    $announcement->features()->createMany($this->getFeatures($announcement->categories->pluck('id')->toArray()));
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

    private function getFeatures(array $categories) : array
    {
        return CategoryAttributeService::forCreate($categories)
            ->map(function ($attribute) {
                if ($attribute->create_layout['type'] == 'from_to') {
                    return [
                        'attribute_id' => $attribute->id,
                        'translated_value' => [
                            'original' => [
                                'from' => fake()->numberBetween(100, 500),
                                'to' => fake()->numberBetween(500, 1000),
                            ],
                        ],
                    ];
                }

                if (in_array($attribute->create_layout['type'], array_keys(self::$type_options['text_fields']))) {
                    return [
                        'attribute_id' => $attribute->id,
                        'translated_value' => [
                            'original' => match (($attribute->create_layout['rules'] ?? null)? $attribute->create_layout['rules'][0]:null) {
                                'numeric' => fake()->numberBetween(0, 100),
                                default => fake()->sentence(12),
                            },
                        ]
                    ];
                }

                if ($attribute->create_layout['type'] == 'price') {
                    return [
                        'attribute_id' => $attribute->id,
                        'translated_value' => [
                            'original' => fake()->numberBetween(0, 100000),
                        ],
                        'attribute_option_id' => $attribute->attribute_options->where('is_null', '!=' ,true)->random()->id,
                    ];
                }

                if (in_array($attribute->create_layout['type'], array_keys(self::$type_options['fields_with_options']))) {
                    return [
                        'attribute_id' => $attribute->id,
                        'attribute_option_id' => $attribute->attribute_options->where('is_null', '!=' ,true)->random()->id,
                    ];
                }

                if (in_array($attribute->create_layout['type'], array_keys(self::$type_options['date']))) {
                    return [
                        'attribute_id' => $attribute->id,
                        'translated_value' => [
                            'original' => fake()->dateTime()->format('Y-m-d H:i:s'),
                        ],
                    ];
                }

                // if (in_array($attribute->create_layout['type'], array_keys(self::$type_options['date_time_picker']))) {
                //     return [
                //         'attribute_id' => $attribute->id,
                //         'translated_value' => [
                //             'original' => fake()->dateTime(),
                //         ],
                //     ];
                // }
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
