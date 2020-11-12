<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;
    public $fillable = [
        'product_name',
        'product_category_id'
    ];

    public function product_category(){
        return $this->belongsTo(App\Models\ProductCategory::class);
    }
}
