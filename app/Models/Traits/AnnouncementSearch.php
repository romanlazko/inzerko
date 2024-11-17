<?php

namespace App\Models\Traits;

use App\AttributeType\AttributeFactory;
use App\Models\Sorting;
use App\Enums\Status;
use App\Models\Category;

use App\Services\Actions\CategoryAttributeService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Cache;

trait AnnouncementSearch
{
    public function scopeCategory($query, ?Category $category = null)
    {
        if (!$category) {
            return $query;
        }

        return $query->whereIn('category_id', $category?->childrenAndSelf->pluck('id'));
    }

    public function scopeGeo($query, $location = null)
    {
        if (!$location) {
            return $query;
        }

        return $query->whereHas('geo', function ($query) use ($location) {
            $query->radius($location['coordinates']['lat'] ?? null, $location['coordinates']['lng'] ?? null, (integer) $location['radius'] == 0 ? 30 : (integer) $location['radius']);
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
                    ->orWhereRaw('MATCH(translated_value) AGAINST(? IN NATURAL LANGUAGE MODE)', [mb_strtolower($search)])
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