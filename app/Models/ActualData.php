<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class ActualData extends Model
{
    public $table = 'actual_data';

    public $fillable = [
        'month',
        'actual',
        'forecast',
        'product_id'
    ];

    public $with = [
        'product'
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }
}
