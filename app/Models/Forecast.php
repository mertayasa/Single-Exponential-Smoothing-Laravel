<?php

namespace App\models;

use Illuminate\Database\Eloquent\Model;

class Forecast extends Model
{
    protected $fillable = [
        'product_id',
        'month_id',
        'forecast',
        'alpha',
        'year',
    ];

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function month(){
        return $this->belongsTo(Month::class);
    }
    
}
