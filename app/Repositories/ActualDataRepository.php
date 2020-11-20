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

    public function findByProductId($product_id){
        $actual_data = ActualData::where('product_id', $product_id)->get();

        return $actual_data;
    }
}