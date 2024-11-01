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
    }

    protected function getFilterQuery(Builder $query) : Builder
    {
        return $query;
    }

    protected function schema(): array
    {
        if ($this->attribute->attribute_options->isNotEmpty()) {
            return [
                'attribute_id' => $this->attribute->id,
                'attribute_option_id' => $this->data[$this->attribute->name],
            ];
        }

        return [
            'attribute_id' => $this->attribute->id,
            'translated_value'        => [
                'original' => $this->data[$this->attribute->name]
            ],
        ];
    }

    protected function fakeData(): array
    {
        if ($this->attribute->attribute_options->isNotEmpty()) {
            return [
                'attribute_id' => $this->attribute->id,
                'attribute_option_id' => $this->attribute->attribute_options->where('is_null', '!=' ,true)->random()->id,
            ];
        }

        return [
            'attribute_id' => $this->attribute->id,
            'translated_value'        => [
                'original' => match (($this->attribute->create_layout['rules'] ?? null) ? $this->attribute->create_layout['rules'][0] : null) {
                    'numeric' => fake()->numberBetween(0, 100),
                    default => fake()->sentence(12),
                },
            ],
        ];
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