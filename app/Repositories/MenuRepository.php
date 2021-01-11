<?php

namespace App\Repositories;

use App\Models\Menu;

class MenuRepository{
    protected $menu;

    public function __construct(Menu $menu){
        $this->menu = $menu;
    }

    public function getAllData(){
        return $this->menu->all();
    }

    public function findById($id){
        return $this->menu->find($id);
    }

    public function create($data){
        $save = $this->menu->create($data);

        return $save;
    }

    public function update($data, $id){
        $menu = $this->findById($id);

        $save = $menu->update($data);

        return $save;
    }

}