<?php 

namespace App\Services\Actions;

use App\Models\Attribute\Attribute;
use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;

class AttributesByCategoryService
{
    public static function forFilter(Category|null $category)
    {
        return self::getAttributesByCategory($category);
    }

    public static function forSorting(Category|null $category)
    {
        return self::getAttributesByCategory($category)
            ->pluck('sortings')
            ->flatten()
            ->where('is_active', true)
            ->sortBy('order_number');
    }

    public static function forCreate(Category|null $category)
    {
        return self::getAttributesByCategory($category);
    }

    public static function forUpdate(Category|null $category)
    {
        return self::getAttributesByCategory($category);
    }

    public static function getAttributesByCategory(Category|null $category)
    {
        $categories = $category
            ?->parentsAndSelf
            ?->where('is_active', true)
            ?->pluck('id')
            ?->toArray();

        $cacheKey = $category->getCacheKey('attributes');

        return Cache::remember($cacheKey, config('cache.ttl'), function () use ($categories) {
            return Attribute::when(!$categories, fn ($query) => $query->where('is_always_required', true))
                ->when($categories, fn ($query) => 
                    $query->whereHas('categories', fn (Builder $query) => 
                        $query->whereIn('category_id', $categories)
                    )
                )
                ->isActive()
                ->get();
        });
    }
}