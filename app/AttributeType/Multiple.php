<?php

namespace App\AttributeType;

use App\Models\Feature;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

abstract class Multiple extends BaseAttributeType
{
    protected function getFilterQuery(Builder $query): Builder
    {
        return $query->where('attribute_id', $this->attribute->id)->where(function ($query) {
            foreach ($this->data[$this->attribute->name] as $key => $value) {
                $query->whereRaw("json_contains(`translated_value`, '$value', '$.\"attribute_option_ids\"')");
            }
        });
    }

    protected function getValue(Feature $feature = null): ?string
    {
        return $this->attribute->attribute_options->whereIn('id', $feature->attribute_options)?->pluck('name')->implode(', ');
    }

    protected function getFakeSchema(): null|Collection|array
    {
        return [
            'attribute_id' => $this->attribute->id,
            'translated_value' => [
                'attribute_option_ids' => $this->attribute->attribute_options->where('is_null', '!=' ,true)->random(2)->pluck('id'),
            ],
        ];
    }

    protected function getSchema(): null|Collection|array
    {
        return [
            'attribute_id' => $this->attribute->id,
            'translated_value' => [
                'attribute_option_ids' => $this->data[$this->attribute->name],
            ],
        ];
    }
}