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
        $actual_data = $this->actualDataRepository->findByProductId($product_id)->orderby('created_at', 'DESC')->first();
        $count_last_month = $this->actualDataRepository->countLastMonth($actual_data['month_id'], $product_id);
        $all_month = $this->monthRepository->getAllData()->pluck('id')->toArray();
        $all_months = $this->monthRepository->getAllData()->pluck('id', 'month')->toArray();
        $group_year_in_actual_db = $this->actualDataRepository->findByProductId($product_id)->get()->groupby('year')->toArray();

        $final_month = [];
        if(count($group_year_in_actual_db) == 0){
            array_push($final_month, array('year' => 2020, 'data' => $all_month));
        }else{
            foreach($group_year_in_actual_db as $key => $group_year){
                if(count($group_year) < 12){
                    $count_month_temp = [];
                    foreach($group_year as $year){
                        array_push($count_month_temp, $year['month_id']);
                    }
                    $difference = array_diff($all_month, $count_month_temp);
                    foreach($difference as $month_diff){
                        $month = $this->monthRepository->findById($month_diff)->toArray();
                        array_push($final_month, array('year' => $key, 'data' => $month));
                    }
                }
            }
        }

        $last_year = array_key_last($group_year_in_actual_db);
        foreach($group_year_in_actual_db[$last_year] as $last){
            if($last['month_id'] == 12){
                foreach($all_months as $months){
                    $month = $this->monthRepository->findById($months)->toArray();
                    array_push($final_month, array('year' => $key+1, 'data' => $month));
                }
            }
        }

        return $final_month;
    }
}




// $month_in_actual_db = $this->actualDataRepository->findByProductId($product_id)->orderby('created_at', 'DESC')->pluck('month_id')->toArray();
// $difference = array_diff($all_month, $month_in_actual_db);

// $return_month = [];
// foreach($difference as $month_diff){
//     $month = $this->monthRepository->findById($month_diff)->toArray();
//     array_push($return_month, $month);
// }

// $init_year = 2020;

// if($count_last_month == 0){
//     $month = $this->monthRepository->findById(1)->toArray();
//     $month['year'] = $init_year;
//     return $month;
// }else if($count_last_month == 1){
//     if($actual_data['month_id'] == 12){
//         $month = $this->monthRepository->findById(1)->toArray();
//         $month['year'] = $init_year+1;
//         foreach($return_month as $re_month){
//             if($re_month < $month){
//                 array_push($month, $re_month);
//             }
//         }
//         return $month;
//     }else{
//         $month = $this->monthRepository->findById($actual_data['month_id']+1)->toArray();
//         $month['year'] = $init_year;
//         $final_month = [];
//         foreach($return_month as $re_month){
//             if($re_month['id'] < $month['id']){
//                 $re_month['year'] = $init_year;
//                 array_push($final_month, $re_month);
//             }
//         }
//         array_push($final_month, $month);
//         return $final_month;
//     }
// }else if($count_last_month == 2){
//     if($actual_data['month_id'] == 12){
//         $month = $this->monthRepository->findById(1);
//         $month['year'] = $init_year+2;
//         return $month;
//     }else{
//         $month = $this->monthRepository->findById($actual_data['month_id']+1);
//         $month['year'] = $init_year+1;
//         return $month;
//     }
// }else{
//     if($actual_data['month_id'] == 12){
//         $month = $this->monthRepository->findById(1);
//         $month['year'] = $init_year+$count_last_month;
//         return $month;
//     }else{
//         $month = $this->monthRepository->findById($actual_data['month_id']+1);
//         $month['year'] = $init_year+$count_last_month-1;
//         return $month;
//     }
// }