<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use SoftDeletes;
    public $fillable = [
        'category_name'
    ];

    public function product_category(){
        return $this->belongsTo(App\Models\ProductCategory::class);
    }
}
