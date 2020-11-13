<?php

namespace App\Repositories;

use App\Models\Month;

class MonthRepository{
    protected $month;

    public function __construct(Month $month){
        $this->month = $month;
    }

    public function getAllData(){
        return $this->month->all();
    }
}