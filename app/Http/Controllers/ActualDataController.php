<?php

namespace App\Http\Controllers;

use App\Models\ActualData;
use App\Repositories\ActualDataRepository;
use App\Repositories\MonthRepository;
use App\Repositories\ProductRepository;
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
    public function index()
    {
        $product = $this->productRepository->getAllData()->pluck('product_name', 'id');
        $product->prepend('Select Product');
        $month = $this->monthRepository->getAllData()->pluck('month', 'id');
        $month->prepend('Select Month');
        return view('actual_data.index', compact('product', 'month'));
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
            $this->productRepository->create($data);
        }catch(Exception $e){
            // return array(0, '500 Internal Server Error | Unable To Save Data');
            return array(0, $e->getMessage());
        }

        return array(1, 'Product saved successfully');

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
            $product = $this->productRepository->findById($id);
            if($product){
                return $product;
            }else{
                return 'Product Not Found';
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
            $this->productRepository->update($data, $id);
        }catch(Exception $e){
            return array(0, '500 Internal Server Error | Unable To Save Data');
            // return array(0, $e->getMessage());
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
                    $product = $this->productRepository->findById($data_id);
                    if (empty($product)) {
                        return array(0, 'Product not found');
                    }
                    $product->delete();
                }
            }catch(Exception $e){
                return array(0, '500 Internal Server Error | Cannot Delete Product');
            }
            
            return array(1, 'Product delete successfully');
        }else{
            $product = $this->productRepository->findById($id);
            if($product){
                $product->delete();
            }else{
                return array(0, 'Product not found');
            };
            return array(1, 'Product delete successfully');
        }
    }
}
