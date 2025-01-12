<?php

namespace App\AttributeType;

use App\Models\Feature;
use Filament\Support\Components\ViewComponent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class BaseAttributeType extends AbstractAttributeType
{
    protected function getSortQuery(Builder $query, $direction = 'asc'): Builder
    {
        return $query->select('sort.translated_value', 'announcements.*')->rightJoin('features as sort', function($join) {
                $join->on('announcements.id', '=', 'sort.announcement_id')
                    ->where('sort.attribute_id', $this->attribute->id);
            })
            ->orderByRaw('CAST(JSON_UNQUOTE(JSON_EXTRACT(sort.translated_value, "$.original")) AS UNSIGNED) ' . $direction);
    }

    protected function getFilterQuery(Builder $query): Builder
    {
        return $query->where('attribute_id', $this->attribute->id)
            ->when($this->attribute->attribute_options->isNotEmpty(), fn ($query) =>
                $query->where('attribute_option_id', $this->data[$this->attribute->name])
            )
            ->when($this->attribute->attribute_options->isEmpty(), fn ($query) =>
                $query->where('translated_value->original', $this->data[$this->attribute->name])
            );
    }

    protected function getSchema(): null|Collection|array
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

    protected function getFakeSchema(): null|Collection|array
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

    protected function getValue(Feature $feature = null): ?string
    {
        return $feature->attribute_option?->name ?? $feature->translated_value[app()->getLocale()] ?? $feature->translated_value['original'] ?? null;
    }

    protected function getOriginalValue(Feature $feature): mixed
    {
        if ($this->attribute->attribute_options->isNotEmpty()) {
            return $feature->attribute_option_id;
        }

        return $feature->translated_value['original'];
    }

    protected function getFilamentCreateComponent(): ?ViewComponent
    {
        return null;
    }

    protected function getFilamentFilterComponent(): ?ViewComponent
    {
        return null;
    }
}