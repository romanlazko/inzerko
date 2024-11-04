<?php

namespace App\Bots\pozor_baraholka_bot\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\App;

class BaraholkaCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [
    ];

    public function subcategories()
    {
        return $this->hasMany(BaraholkaSubcategory::class);
    }

    public function trans_name()
    {
        return __('baraholka::categories.'.$this->name.'.name');
    }
}
