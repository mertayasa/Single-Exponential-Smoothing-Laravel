<?php

namespace App\Repositories;

use App\Models\ProductCategory;

class ProductCategoryRepository{
    protected $productCategory;

    public function __construct(ProductCategory $productCategory){
        $this->productCategory = $productCategory;
    }

    public function getAllData(){
        return $this->productCategory->all();
    }

    public function findById($id){
        return $this->productCategory->find($id);
    }

    public function create($data){
        $save = $this->productCategory->create($data);

        return $save;
    }

    public function update($data, $id){
        $productCategory = $this->findById($id);

        $save = $productCategory->update($data);

        return $save;
    }

}