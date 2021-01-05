<?php

namespace App\Models;

use App\models\Forecast;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Month;
use Carbon\Carbon;
use MonthSeeder;

class ActualData extends Model
{
    public $table = 'actual_data';

    public $fillable = [
        'actual',
        'product_id',
        'month_id',
        'year'
    ];

    public $with = [
        'product', 'month'
    ];

    public function product(){
        return $this->belongsTo(Product::class)->withTrashed();
    }

    public function month(){
        return $this->belongsTo(Month::class);
    }


    // protected static function booted(){
    //     static::created(function ($available_date) {
    //         $data = [
    //             ['available_date_id' => $available_date->id, 'hour' => '10.00', 'available_count' => 2, 'created_at' => Carbon::now()],
    //             ['available_date_id' => $available_date->id, 'hour' => '13.00', 'available_count' => 2, 'created_at' => Carbon::now()],
    //             ['available_date_id' => $available_date->id, 'hour' => '16.00', 'available_count' => 2, 'created_at' => Carbon::now()],
    //         ];
    //         Forecast::insert($data);
    //     });
    // }

}
