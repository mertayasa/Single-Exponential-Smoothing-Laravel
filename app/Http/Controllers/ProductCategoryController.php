<?php

namespace App\Http\Controllers;

use App\DataTables\CategoryDataTable;
use App\Models\ProductCategory;
use App\Repositories\ProductCategoryRepository;
use Exception;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    protected $productCategoryRepository;

    public function __construct(ProductCategoryRepository $productCategoryRepo){
        $this->productCategoryRepository = $productCategoryRepo;
    }

    public function index(CategoryDataTable $categoryDataTable)
    {
        return $categoryDataTable->render('product_category.index');
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

        try{
            $this->productCategoryRepository->create($data);
        }catch(Exception $e){
            // return array(0, '500 Internal Server Error | Unable To Save Data');
            return array(0, $e->getMessage());
        }

        return array(1, 'Kategori berhasil ditambahkan');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try{
            $product = $this->productCategoryRepository->findById($id);
            if($product){
                return $product;
            }else{
                return 'Kategori tidak ditemukan';
            }
        }catch(Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();

        try{
            $this->productCategoryRepository->update($data, $id);
        }catch(Exception $e){
            return array(0, '500 Internal Server Error | Unable To Save Data');
            // return array(0, $e->getMessage());
        }

        return array(1, 'Berhasil ubah kategori');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $data_id = $request->all();

        if(isset($data_id['model_id'])){
            try{
                foreach($data_id['model_id'] as $data_id){
                    $category = $this->productCategoryRepository->findById($data_id);
                    if (empty($category)) {
                        return array(0, 'Kategori tidak ditemukan');
                    }
                    $category->delete();
                }
            }catch(Exception $e){
                return array(0, '500 Internal Server Error | Gagal menghapus kategori');
            }
            
            return array(1, 'Kategori berhasil dihapus');
        }else{
            $category = $this->productCategoryRepository->findById($id);
            if($category){
                $category->delete();
            }else{
                return array(0, 'Kategori tidak ditemukan');
            };
            return array(1, 'Berhasil hapus kategori');
        }
    }
}
