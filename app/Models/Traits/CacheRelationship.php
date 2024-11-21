<?php

namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

trait CacheRelationship
{

    public function cacheRelation($relation_name, $ttl = null): mixed
    {
        if ($this->relationLoaded($relation_name)) {
            return $this->getRelationValue($relation_name);
        }
    
        // Get the relation from the cache, or load it from the datasource and set to the cache
        $relation = Cache::remember($this->getRelationCacheKey($relation_name), $ttl ?? config('cache.ttl'), function () use ($relation_name) {
            return $this->getRelationValue($relation_name);
        });
    
        // Set the relation to the current instance of model
        $this->setRelation($relation_name, $relation);
    
        return $relation;
    }

    protected function getRelationCacheKey($relation_name): string
    {
        return sprintf(strtolower(get_class($this)) . '-%d-' . $relation_name, $this->id);
    }

    public function clearRelateionCache($relation_name): bool
    {
        return Cache::forget($this->getRelationCacheKey($relation_name));
    }
}