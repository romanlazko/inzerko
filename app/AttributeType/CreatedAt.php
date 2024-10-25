<?php

namespace App\AttributeType;

use App\Models\Feature;
use Carbon\Carbon;
use Filament\Forms\Components\DateTimePicker as ComponentsDateTimePicker;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Support\Components\ViewComponent;
use Illuminate\Database\Eloquent\Builder;

class CreatedAt extends BaseAttributeType
{
    protected function getSortQuery(Builder $query, $direction = 'asc') : Builder
    {
        return $query->orderBy('created_at', $direction);
    }
}