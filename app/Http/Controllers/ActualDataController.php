<?php

namespace App\Http\Controllers;

use App\DataTables\ActualDataDataTable;
use App\Models\ActualData;
use App\Repositories\ActualDataRepository;
use App\Repositories\MonthRepository;
use App\Repositories\ProductRepository;
use Carbon\Carbon;
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

    public function index(ActualDataDataTable $actualDataDataTable, Request $request){
        $product = $this->productRepository->getAllData()->pluck('product_name', 'id');
        $product->prepend('Pilih Produk', '');
        $actualData = $this->actualDataRepository->getAllData();
        // dd($actualData);
        return $actualDataDataTable->render('actual_data.index', compact('product'));
    }

    public function store(Request $request){
        $data = $request->all();

        try{
            $this->actualDataRepository->create($data);
        }catch(Exception $e){
            // return array(0, '500 Internal Server Error | Unable To Save Data');
            return array(0, $e->getMessage());
        }

        return array(1, 'Aktual data berhasil ditambahkan');

    }

    public function edit($id){
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

    public function update(Request $request, $id){
        $data = $request->all();

        try{
            $this->actualDataRepository->update($data, $id);
        }catch(Exception $e){
            // return array(0, '500 Internal Server Error | Unable To Save Data');
            return array(0, $e->getMessage());
        }

        return array(1, 'Category updated successfully');

    }

    public function destroy(Request $request, $id){
        $data_id = $request->all();

        if(isset($data_id['model_id'])){
            try{
                foreach($data_id['model_id'] as $data_id){
                    $actual = $this->actualDataRepository->findById($data_id);
                    if (empty($actual)) {
                        return array(0, 'Data tidak ditemukan');
                    }
                    $actual->delete();
                }
            }catch(Exception $e){
                return array(0, '500 Internal Server Error | Gagal menghapus data');
            }
            
            return array(1, 'Data berhasil dihapus');
        }else{
            $actual = $this->actualDataRepository->findById($id);
            if($actual){
                $actual->delete();
            }else{
                return array(0, 'Data tidak ditemukan');
            };
            return array(1, 'Data berhasil dihapus');
        }
    }

    public function getMonthLeft($product_id){
        $actualData = $this->actualDataRepository->findByProductId($product_id)->orderby('created_at', 'DESC')->first();
        $countLastMonth = $this->actualDataRepository->countLastMonth($actualData['month_id'], $product_id);
        $allMonth = $this->monthRepository->getAllData()->pluck('id')->toArray();
        $actualData2 = $this->actualDataRepository->findByProductId($product_id)->orderby('created_at', 'DESC')->pluck('month_id')->toArray();
        $difference = array_diff($allMonth, $actualData2);

        $return_month = [];
        foreach($difference as $month_diff){
            $month = $this->monthRepository->findById($month_diff)->toArray();
            array_push($return_month, $month);
        }

        // return $return_month;

        
        $init_year = 2020;

        // return $actualData;

        if($countLastMonth == 0){
            $month = $this->monthRepository->findById(1)->toArray();
            $month['year'] = $init_year;
            return $month;
        }else if($countLastMonth == 1){
            if($actualData['month_id'] == 12){
                $month = $this->monthRepository->findById(1)->toArray();
                $month['year'] = $init_year+1;
                foreach($return_month as $re_month){
                    if($re_month < $month){
                        array_push($month, $re_month);
                    }
                }
                return $month;
            }else{
                $month = $this->monthRepository->findById($actualData['month_id']+1)->toArray();
                $month['year'] = $init_year;
                $final_month = [];
                foreach($return_month as $re_month){
                    if($re_month['id'] < $month['id']){
                        $re_month['year'] = $init_year;
                        array_push($final_month, $re_month);
                    }
                }
                array_push($final_month, $month);
                return $final_month;
            }
        }else if($countLastMonth == 2){
            if($actualData['month_id'] == 12){
                $month = $this->monthRepository->findById(1);
                $month['year'] = $init_year+2;
                return $month;
            }else{
                $month = $this->monthRepository->findById($actualData['month_id']+1);
                $month['year'] = $init_year+1;
                return $month;
            }
        }else{
            if($actualData['month_id'] == 12){
                $month = $this->monthRepository->findById(1);
                $month['year'] = $init_year+$countLastMonth;
                return $month;
            }else{
                $month = $this->monthRepository->findById($actualData['month_id']+1);
                $month['year'] = $init_year+$countLastMonth-1;
                return $month;
            }
        }

    }
}

// if($actualData->max('month_id') == 12){
//     return $allMonth;
// }else{
//     $index = $actualData->max('month_id');
//     return array($allMonth[$index]);
//     // array_splice($allMonth, array_search($actualData->month_id, $allMonth ), 1);
// }
// // foreach($actualData as $actual){
// //     if($actual->month_id < 12){
// //         array_splice($allMonth, array_search($actual->month_id, $allMonth ), 1);
// //     }else{
// //         return $allMonth;
// //     }
// // }