<?php

namespace App\Models\Attribute;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttributeGroup extends Model
{
    use HasFactory; 
    use SoftDeletes;

    public $guarded = [];

    public function attributes(): HasMany
    {
        return $this->hasMany(Attribute::class);
    }
}
