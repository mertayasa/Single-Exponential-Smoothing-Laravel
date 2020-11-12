<?php

namespace App\Repositories;

use App\Models\Product;

class ProductRepository{
    protected $product;

    public function __construct(Product $product){
        $this->product = $product;
    }

    public function getAllData(){
        return $this->product->all();
    }

    public function findById($id){
        return $this->product->find($id);
    }

    public function create($data){
        $save = $this->product->create($data);

        return $save;
    }

    public function update($data, $id){
        $product = $this->findById($id);

        $save = $product->update($data);

        return $save;
    }

}