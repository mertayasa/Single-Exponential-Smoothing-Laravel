<?php

namespace App\Http\Controllers;

use App\DataTables\ActualDataDataTable;
use App\Exports\OveralForecastExport;
use App\models\Forecast;
use App\Repositories\ActualDataRepository;
use App\Repositories\MonthRepository;
use App\Repositories\MenuRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class ActualDataController extends Controller
{

    protected $menuRepository;
    protected $actualDataRepository;
    protected $monthRepository;

    public function __construct(MenuRepository $menuRepo, ActualDataRepository $actualDataRepo, MonthRepository $monthRepo){
        $this->menuRepository = $menuRepo;
        $this->actualDataRepository = $actualDataRepo;
        $this->monthRepository = $monthRepo;
    }

    public function index(ActualDataDataTable $actualDataDataTable, Request $request){
        $menu = $this->menuRepository->getAllData()->pluck('menu_name', 'id');
        $menu->prepend('Pilih Menu', '');
        $actualData = $this->actualDataRepository->getAllData();
        // dd($actualData);
        return $actualDataDataTable->render('actual_data.index', compact('menu'));
    }

    public function store(Request $request){
        $data = $request->all();

        try{
            $this->actualDataRepository->create($data);
        }catch(Exception $e){
            // return array(0, '500 Internal Server Error | Unable To Save Data');
            Log::info($e->getMessage());
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

    public function getMonthLeft($menu_id){
        $all_month = $this->monthRepository->getAllData()->pluck('id')->toArray();
        $all_months = $this->monthRepository->getAllData()->pluck('id', 'month')->toArray();
        $group_year_in_actual_db = $this->actualDataRepository->findByMenuId($menu_id)->orderby('month_id', 'ASC')->get()->groupby('year')->toArray();

        $final_month = [];
        if(count($group_year_in_actual_db) == 0){
            foreach($all_months as $months){
                $month = $this->monthRepository->findById($months)->toArray();
                array_push($final_month, array('year' => 2020, 'data' => $month));
            }
            return $final_month;
        }else{
            foreach($group_year_in_actual_db as $key => $group_year){
                if(count($group_year) < 12){
                    $count_month_temp = [];
                    foreach($group_year as $year){
                        array_push($count_month_temp, $year['month_id']);
                    }
                    $difference = array_diff($all_month, $count_month_temp);
                    // return $difference;
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