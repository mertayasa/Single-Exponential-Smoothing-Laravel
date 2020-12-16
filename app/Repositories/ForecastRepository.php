<?php

namespace App\Repositories;

use App\Models\Forecast;

class ForecastRepository{
    protected $forecast;

    public function __construct(Forecast $forecast){
        $this->forecast = $forecast;
    }

    public function groupData(){
        $all_data = $this->forecast->orderBy('id', 'DESC')->get()->groupBy('product_id')->toArray();

        $temp = [];
        foreach($all_data as $data){
            array_push($temp, $data[0]);
        }

        return $temp;
    }

    public function findByProductId($product_id){
        $forecast = $this->forecast->where('product_id', $product_id)->get();

        return $forecast;
    }
}