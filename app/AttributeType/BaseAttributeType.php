<?php

namespace App\AttributeType;

use Filament\Forms\Get;
use Filament\Support\Components\ViewComponent;
use Illuminate\Database\Eloquent\Builder;

class BaseAttributeType extends AbstractAttributeType
{
    protected function getSortQuery(Builder $query, $direction = 'asc') : Builder
    {
        return $query->select('sort.translated_value', 'announcements.*')->rightJoin('features as sort', function($join) {
                $join->on('announcements.id', '=', 'sort.announcement_id')
                    ->where('sort.attribute_id', $this->attribute->id);
            })
            ->orderByRaw('CAST(JSON_UNQUOTE(JSON_EXTRACT(sort.translated_value, "$.original")) AS UNSIGNED) ' . $direction);

        return $query;
    }

    protected function getFilterQuery(Builder $query) : Builder
    {
        return $query;
    }

    protected function getFeatureValue(null|string|array $translated_value = null): ?string
    {
        if (is_array($translated_value)) {
            return implode('-', array_filter($translated_value));
        }

        return $translated_value;
    }

    protected function getFilamentCreateComponent(Get $get = null): ?ViewComponent
    {
        return null;
    }

    protected function getFilamentFilterComponent(Get $get = null): ?ViewComponent
    {
        return null;
    }
}