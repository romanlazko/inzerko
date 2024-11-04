<?php

namespace App\Bots\pozor_baraholka_bot\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BaraholkaSubcategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(BaraholkaCategory::class, 'baraholka_category_id', 'id');
    }

    public function trans_name()
    {
        return __('baraholka::categories.'.$this->category?->name.'.subcategories.'.$this->name);
    }
}
