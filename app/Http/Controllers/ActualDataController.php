<?php

namespace App\Http\Controllers;

use App\DataTables\ActualDataDataTable;
use App\Models\ActualData;
use App\Repositories\ActualDataRepository;
use App\Repositories\MonthRepository;
use App\Repositories\ProductRepository;
use Exception;
use Illuminate\Http\Request;

class ActualDataController extends Controller
{

    protected $productRepository;
    protected $actualDataRepository;
    protected $monthRepository;

    public function __construct(ProductRepository $productRepo, ActualDataRepository $actualDataRepo, MonthRepository $monthRepo){
        $this->productRepository = $productRepo;
        $this->actualDataRepository = $actualDataRepo;
        $this->monthRepository = $monthRepo;
    }

    // public function index(ProductDataTable $productDataTable)
    public function index(ActualDataDataTable $actualDataDataTable)
    {
        $product = $this->productRepository->getAllData()->pluck('product_name', 'id');
        $product->prepend('Pilih Produk', 0);
        return $actualDataDataTable->render('actual_data.index', compact('product'));
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
            $this->actualDataRepository->create($data);
        }catch(Exception $e){
            // return array(0, '500 Internal Server Error | Unable To Save Data');
            return array(0, $e->getMessage());
        }

        return array(1, 'Aktual data berhasil ditambahkan');

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
            $actual = $this->actualDataRepository->findById($id);
            if($actual){
                return $actual;
            }else{
                return 'Data Aktual Tidak Ditemukan';
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
            $this->actualDataRepository->update($data, $id);
        }catch(Exception $e){
            // return array(0, '500 Internal Server Error | Unable To Save Data');
            return array(0, $e->getMessage());
        }

        return array(1, 'Category updated successfully');

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
                    $product = $this->actualDataRepository->findById($data_id);
                    if (empty($product)) {
                        return array(0, 'Data tidak ditemukan');
                    }
                    $product->delete();
                }
            }catch(Exception $e){
                return array(0, '500 Internal Server Error | Gagal menghapus data');
            }
            
            return array(1, 'Data berhasil dihapus');
        }else{
            $product = $this->actualDataRepository->findById($id);
            if($product){
                $product->delete();
            }else{
                return array(0, 'Data tidak ditemukan');
            };
            return array(1, 'Data berhasil dihapus');
        }
    }

    public function getMonthLeft($product_id){
        $actualData = $this->actualDataRepository->findByProductId($product_id);
        $allMonth = $this->monthRepository->getAllData()->toArray();
        foreach($actualData as $actual){
            array_splice($allMonth, array_search($actual->month_id, $allMonth ), 1);
        }
        
        return $allMonth;
    }
}
