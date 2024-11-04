<?php

namespace App\Bots\pozor_baraholka_bot\Http\DataTransferObjects;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Romanlazko\Telegram\App\Config;

class Announcement
{
    public function __construct(
        public ?int $id = null,
        public ?int $user_id = null,
        public ?int $chat = null,
        public ?string $city = null,
        public ?string $type = null,
        public ?string $title = null,
        public ?string $caption = null,
        public ?int $cost = null,
        public ?string $category = null,
        public ?int $category_id = null,
        public ?int $subcategory_id = null,
        public ?string $condition = null,
        public ?int $views = null,
        public ?string $status = null,
        public Collection|array|null $photos = null,
        public ?Carbon $created_at = null,
        public ?Carbon $updated_at = null,
    )
    {

    }

    public static function fromObject(object $data): Announcement
    {
        return new self(
            city: $data->city ?? null,
            type: $data->type ?? null,
            title: $data->title ?? null,
            caption: $data->caption ?? null,
            cost: $data->cost ?? null,
            category: $data->category ?? null,
            condition: $data->condition ?? null,
        );
    }
    
    public function prepare(): string
    {
        $categoryArr = [
            'clothes'       => '#одежда',
            'accessories'   => '#аксессуары',
            'for_home'      => '#для_дома',
            'electronics'   => '#электроника',
            'sport'         => '#спорт',
            'furniture'     => '#мебель',
            'books'         => '#книги',
            'games'         => '#игры',
            'auto'          => '#авто_мото',
            'property'      => '#недвижимость',
            'animals'       => '#животные',
            'other'         => '#прочее',
        ];

        $text = [];

        if ($this->type) {
            $text[] = $this->type === 'buy' ? '#куплю' : '#продам';
        }

        if ($this->title) {
            $text[] = "<b>{$this->title}</b>";
        }

        if ($this->caption) {
            $text[] = $this->caption;
        }

        if ($this->condition) {
            $text[] = "<i>Состояние:</i> " . ($this->condition === 'new' ? 'Новое' : 'Б/у');
        }

        if ($this->cost) {
            $text[] = "<i>Стоимость:</i> {$this->cost} CZK";
        }

        if ($this->category && isset($categoryArr[$this->category])) {
            $text[] = $categoryArr[$this->category];
        }

        return implode("\n\n", $text);
    }
}
