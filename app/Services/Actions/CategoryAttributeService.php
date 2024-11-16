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
        return self::getAttributesByCategory($category);
    }

    public static function forSorting(Category|null $category)
    {
        return self::getAttributesByCategory($category)
            // ->load('sortings:id,alternames,attribute_id,order_number')
            ->pluck('sortings')
            ->flatten()
            ->sortBy('order_number');
    }

    public static function forCreate(Category|null $category)
    {
        return self::getAttributesByCategory($category);
                // ->load('createSection:id,order_number,alternames,is_visible');
    }

    public static function forUpdate(Category|null $category)
    {
        return self::getAttributesByCategory($category);
    }

    public static function getAttributesByCategory(Category|null $category)
    {
        $categories = $category?->getParentsAndSelf()?->pluck('id')->toArray();

        $cacheKey = ($category?->slug ?? 'default') . '_category_attributes';

        return Cache::remember($cacheKey, config('cache.ttl'), function () use ($categories) {
            return Attribute::with('attribute_options:id,alternames,attribute_id,is_default,is_null')
                ->when(!$categories, fn ($query) => $query->where('is_always_required', true))
                ->when($categories, fn ($query) => 
                    $query->whereHas('categories', fn (Builder $query) => 
                        $query->whereIn('category_id', $categories)
                    )
                )
                ->get();
        });
    }
}