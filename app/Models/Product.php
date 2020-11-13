<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ProductCategory;

class Product extends Model
{
    use SoftDeletes;
    public $fillable = [
        'product_name',
        'product_category_id'
    ];

    public $with = [
        'product_category'
    ];

    public function product_category(){
        return $this->belongsTo(ProductCategory::class);
    }
}
