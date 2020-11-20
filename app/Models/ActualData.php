<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Month;
use MonthSeeder;

class ActualData extends Model
{
    public $table = 'actual_data';

    public $fillable = [
        'actual',
        'product_id',
        'month_id'
    ];

    public $with = [
        'product', 'month'
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function month(){
        return $this->belongsTo(Month::class);
    }
}
