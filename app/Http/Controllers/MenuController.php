<?php

namespace App\Http\Controllers;

use App\DataTables\MenuDataTable;
use App\Models\Menu;
use App\Repositories\MenuRepository;
use Exception;
use Illuminate\Http\Request;

class MenuController extends Controller
{

    protected $menuRepository;

    public function __construct(MenuRepository $menuRepo){
        $this->menuRepository = $menuRepo;
    }

    public function index(MenuDataTable $menuDataTable)
    {
        return $menuDataTable->render('menu.index');
    }

    public function getMenu(){
        return $this->menuRepository->getAllData()->pluck('menu_name', 'id');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        // return $data;

        try{
            $this->menuRepository->create($data);
        }catch(Exception $e){
            // return array(0, '500 Internal Server Error | Unable To Save Data');
            return array(0, $e->getMessage());
        }

        return array(1, 'Menu Berhasil Disimpan');

    }

    public function edit($id)
    {
        try{
            $menu = $this->menuRepository->findById($id);
            if($menu){
                return $menu;
            }else{
                return 'Menu Tidak Ditemukan';
            }
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    public function update(Request $request, $id)
    {
        $data = $request->all();

        try{
            $this->menuRepository->update($data, $id);
        }catch(Exception $e){
            return array(0, '500 Internal Server Error | Unable To Save Data');
            // return array(0, $e->getMessage());
        }

        return array(1, 'Menu Berhasil Diubah');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\menu  $menu
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $data_id = $request->all();

        if(isset($data_id['model_id'])){
            try{
                foreach($data_id['model_id'] as $data_id){
                    $menu = $this->menuRepository->findById($data_id);
                    if (empty($menu)) {
                        return array(0, 'Menu Tidak Ditemukan');
                    }
                    $menu->delete();
                }
            }catch(Exception $e){
                return array(0, '500 Internal Server Error | Cannot Delete Menu');
            }
            
            return array(1, 'Menu Berhasil Dihapus');
        }else{
            $menu = $this->menuRepository->findById($id);
            if($menu){
                $menu->delete();
            }else{
                return array(0, 'Menu Tidak Ditemukan');
            };
            return array(1, 'Menu Berhasil Dihapus');
        }
    }
}
