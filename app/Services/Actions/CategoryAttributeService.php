<?php 

namespace App\Services\Actions;

use App\Models\Attribute;
use App\Models\Category;
use App\Models\Sorting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CategoryAttributeService
{
    public static function forFilter(Category|null $category)
    {
        $cacheKey = ($category?->slug ?? 'default') . '_filter_attributes';

        $categories = $category?->getParentsAndSelf()->pluck('id')->toArray();

        return Cache::remember($cacheKey, config('cache.ttl'), function () use ($categories) {
            return self::getAttributesByCategories($categories)
                ->load('filterSection:id,order_number,alternames');
        });
    }

    public static function forSorting(Category|null $category)
    {
        $cacheKey = ($category?->slug ?? 'default') . '_sorting_attributes';

        $categories = $category?->getParentsAndSelf()->pluck('id')->toArray();

        return Cache::remember($cacheKey, config('cache.ttl'), function () use ($categories) {
            return self::getAttributesByCategories($categories)
                ->load('sortings:id,alternames,attribute_id,order_number')
                ->pluck('sortings')
                ->flatten()
                ->sortBy('order_number');
        });
    }

    public static function forCreate($categories)
    {
        $cacheKey = implode('_', $categories) . '_create_attributes';

        return Cache::remember($cacheKey, config('cache.ttl'), function () use ($categories) {
            return self::getAttributesByCategories($categories)
                ->load('createSection:id,order_number,alternames,is_visible');
        });
    }

    public static function forShow(Category $category, $categories)
    {
        $cacheKey = ($category?->slug ?? 'default') . '_show_attributes';

        return Cache::remember($cacheKey, config('cache.ttl'), function () use ($categories) {
            return Attribute::with('attribute_options:id,alternames,attribute_id,is_default,is_null', 'showSection:id,order_number')
                ->whereHas('categories', fn (Builder $query) => 
                    $query->whereIn('category_id', $categories)
                )
                ->get();
        });
    }

    public static function getAttributesByCategories(array|null $categories)
    {
        return Attribute::with('attribute_options:id,alternames,attribute_id,is_default,is_null')
            ->when(!$categories, fn ($query) => $query->where('is_always_required', true))
            ->when($categories, fn ($query) => 
                $query->whereHas('categories', fn (Builder $query) => 
                    $query->whereIn('category_id', $categories)
                )
            )
            ->get();
    }
}