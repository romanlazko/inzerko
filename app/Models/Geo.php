<?php

namespace App\Models;

use Akuechler\Geoly;
use Igaster\LaravelCities\Geo as Model;

class Geo extends Model
{
    use Geoly;

    protected $guarded = [];

    public function getNameAttribute()
    {
        return "{$this->attributes['country']}, {$this->attributes['name']}";
    }
}
