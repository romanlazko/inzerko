<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class HtmlLayout extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasSlug;

    protected $guarded = [];

    public function getSlugOptions() : SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function renderBlade(?array $data = null): HtmlString
    {
        return new HtmlString(Blade::render(<<<BLADE
            $this->blade
        BLADE, $data));
    }
}
