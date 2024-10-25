<?php

namespace App\Models\Traits;

use App\AttributeType\AttributeFactory;
use App\Models\Sorting;
use App\Enums\Status;
use App\Models\Attribute;
use App\Models\Category;
use App\Models\Geo;

use App\Services\Actions\CategoryAttributeService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

use function JmesPath\search;

trait AnnouncementSearch
{
    public function scopeCategory($query, ?Category $category = null)
    {
        if (!$category) {
            return $query;
        }

        return $query->whereHas('categories', fn ($query) 
            => $query->where('category_id', $category->id)
                ->select('categories.id')
        );

        // return $query->leftJoin('announcement_category', 'announcement_category.announcement_id', '=', 'announcements.id')
        //     ->where('announcement_category.category_id', $category->id);
    }

    public function scopeGeo($query, $location = null)
    {
        if (!$location) {
            return $query;
        }

        return $query->whereHas('geo', function ($query) use ($location) {
            $query->radius($location['coordinates']['lat'], $location['coordinates']['lng'], (integer) $location['radius'] == 0 ? 30 : (integer) $location['radius']);
        });
    }

    public function scopeFilter($query, ?array $filters = null, ?Category $category = null)
    {
        return $query->where(function ($query) use ($filters, $category) {
            $filterAttributes = CategoryAttributeService::forFilter($category);

            foreach ($filterAttributes as $attribute) {
                $query->where(function($query) use ($attribute, $filters) {
                    AttributeFactory::applyFilterQuery($attribute, $filters, $query);
                });
            }
            
            return $query;
        });
    }

    public function scopeIsPublished($query)
    {
        return $query->where('current_status', Status::published);
    }

    public function scopeSearch($query, ?string $search = null)
    {
        if (!$search) {
            return $query;
        }
        
        return $query->whereHas('features', fn ($query) =>
            $query->where(fn ($query) => 
                $query->whereRaw('LOWER(features.translated_value) LIKE ?', ['%' . mb_strtolower($search) . '%'])
                    ->orWhereHas('attribute_option', fn ($query) => 
                        $query->whereRaw('LOWER(alternames) LIKE ?', ['%' . mb_strtolower($search) . '%'])
                    )
                )
            );
    }

    public function scopeSort($query, $sort = null)
    {
        $sort = Cache::remember($sort.'_sort_options', config('cache.ttl'), function () use ($sort) {
            return Sorting::findOr($sort, fn () => Sorting::firstWhere('default', true))->load('attribute');
        });

        return AttributeFactory::applySortQuery($sort->attribute, $query, $sort->direction);
    }
}