<?php

namespace App\Repositories;

use App\Models\ActualData;

class ActualDataRepository{
    protected $actual_data;

    public function __construct(ActualData $actual_data){
        $this->actual_data = $actual_data;
    }

    public function getAllData(){
        return $this->actual_data->all();
    }

    public function findById($id){
        return $this->actual_data->find($id);
    }

    public function create($data){
        $save = $this->actual_data->create($data);

        return $save;
    }

    public function update($data, $id){
        $actual_data = $this->findById($id);

        $save = $actual_data->update($data);

        return $save;
    }

    public function findByMenuId($menu_id){
        $actual_data = ActualData::where('menu_id', $menu_id);

        return $actual_data;
    }

    public function countLastMonth($month_id, $menu_id){
        $actual_data = ActualData::where('month_id', $month_id)->where('menu_id', $menu_id)->get()->count();

        return $actual_data;
    }
}