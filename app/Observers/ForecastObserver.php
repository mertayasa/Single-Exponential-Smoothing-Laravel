<?php

namespace App\Observers;

use App\Models\ActualData;
use App\Models\Forecast;
use Illuminate\Support\Facades\Log;

class ForecastObserver{
    protected $forecast;

    public function __construct(Forecast $forecast){
        $this->forecast = $forecast;
    }

    public function created(ActualData $actualData){
        $this->forecast($actualData);
    }

    public function updated(ActualData $actualData){
        $this->forecast($actualData);
    }

    public function deleted(ActualData $actualData){
        $this->forecast($actualData);
    }

    public function restored(ActualData $actualData){
        //
    }

    public function forceDeleted(ActualData $actualData){
        //
    }



    public function forecast($actualData){
        $actuals = ActualData::where('menu_id', $actualData->menu_id)->orderBy('year', 'ASC')->orderBy('month_id', 'ASC')->get()->toArray();

        if($actuals){
            $this->forecast->where('menu_id', $actuals[0]['menu_id'])->delete();

            $single_data = [];
            $forecast_temp = [];
            $forecast_02 = [];
            $forecast_05 = [];
            $forecast_08 = [];

            // Group all alpha
            $alphas = array(0.2, 0.5, 0.8);

            array_push($single_data, array('menu_id' => $actuals[0]['menu_id'], 'year' => $actuals[0]['year'],'month_id' => $actuals[0]['month_id'] , 'alpha' => 0, 'actual' => $actuals[0]['actual'], 'forecast' => $actuals[0]['actual']));
            array_push($single_data, array('menu_id' => $actuals[0]['menu_id'], 'year' => $actuals[0]['year'],'month_id' => $actuals[0]['month_id']+1 , 'alpha' => 0, 'actual' => $actuals[0]['actual'], 'forecast' => $actuals[0]['actual']));
            
            if(count($actuals) > 1){
                array_push($forecast_02, array('menu_id' => $actuals[0]['menu_id'], 'year' => $actuals[0]['year'],'month_id' => $actuals[0]['month_id'] , 'alpha' => $alphas[0], 'actual' => $actuals[0]['actual'], 'forecast' => $actuals[0]['actual']));
                
                array_push($forecast_05, array('menu_id' => $actuals[0]['menu_id'], 'year' => $actuals[0]['year'],'month_id' => $actuals[0]['month_id'] , 'alpha' => $alphas[1], 'actual' => $actuals[0]['actual'], 'forecast' => $actuals[0]['actual']));
                
                array_push($forecast_08, array('menu_id' => $actuals[0]['menu_id'], 'year' => $actuals[0]['year'],'month_id' => $actuals[0]['month_id'] , 'alpha' => $alphas[2], 'actual' => $actuals[0]['actual'], 'forecast' => $actuals[0]['actual']));
                
                array_push($forecast_02, array('menu_id' => $actuals[0]['menu_id'], 'year' => $actuals[1]['year'],'month_id' => $actuals[1]['month_id'] , 'alpha' => $alphas[0], 'actual' => $actuals[1]['actual'], 'forecast' => $actuals[0]['actual']));
                array_push($forecast_05, array('menu_id' => $actuals[0]['menu_id'], 'year' => $actuals[1]['year'],'month_id' => $actuals[1]['month_id'] , 'alpha' => $alphas[1], 'actual' => $actuals[1]['actual'], 'forecast' => $actuals[0]['actual']));
                array_push($forecast_08, array('menu_id' => $actuals[0]['menu_id'], 'year' => $actuals[1]['year'],'month_id' => $actuals[1]['month_id'] , 'alpha' => $alphas[2], 'actual' => $actuals[1]['actual'], 'forecast' => $actuals[0]['actual']));

                // Init forecast for index 2
                $init_forecast = $actuals[0]['actual'];
                //  Log::info($init_forecast);

                // Store temporary last farecast
                $last = [];
                foreach($alphas as $alpha){
                    foreach($actuals as $key => $value){
                        if($key !== 0 && $key !== 1){
                            if($key == 2){
                                $last = $init_forecast + ($alpha * (($actuals[$key-1]['actual'] - $init_forecast)));
                                array_push($forecast_temp, array('menu_id' => $actuals[0]['menu_id'], 'year' => $value['year'],'month_id' => $value['month_id'] , 'alpha' => $alpha, 'forecast' => $last, 'actual' => $actuals[$key]['actual']));
                            }
                            else{
                                $last = $last + (($alpha * ($actuals[$key-1]['actual'] - $last)));
                                array_push($forecast_temp, array('menu_id' => $actuals[0]['menu_id'], 'year' => $value['year'],'month_id' => $value['month_id'] , 'alpha' => $alpha, 'forecast' => $last, 'actual' => $actuals[$key]['actual']));
                            }
                        }
                    }
                }
                // Log::info($last);

                // Group forecast based on alpha used
                foreach($forecast_temp as $fore_temp){
                    if($fore_temp['alpha'] == 0.2){
                        array_push($forecast_02, $fore_temp);
                    }else if($fore_temp['alpha'] == 0.5){
                        array_push($forecast_05, $fore_temp);
                    }else if($fore_temp['alpha'] == 0.8){
                        array_push($forecast_08, $fore_temp);
                    }
                }

                $mape_02 = [];
                $mape_05 = [];
                $mape_08 = [];

                $all_forecast = array('02' => $forecast_02, '05' => $forecast_05, '08' => $forecast_08);
                // Log::info($all_forecast);

                // Count |At - Ft| / At
                foreach($all_forecast as $all_fore){
                    foreach($all_fore as $key => $fore){
                        $mape = abs(($fore['actual'] - $fore['forecast']) / $fore['actual']);
                        if($fore['alpha'] ==  0.2){
                            if($key == 0){
                                $mape = 0;
                            }
                            array_push($mape_02, $mape);
                        }else if($fore['alpha'] ==  0.5){
                            if($key == 0){
                                $mape = 0;
                            }
                            array_push($mape_05, $mape);
                        }else if($fore['alpha'] ==  0.8){
                            if($key == 0){
                                $mape = 0;
                            }
                            array_push($mape_08, $mape);
                        }
                    }
                }

                // Count Final Mape
                $all_mape = array(
                    array('alpha' => 0.2, 'mape' => (array_sum($mape_02) / count($mape_02)) * 100), 
                    array('alpha' => 0.5, 'mape' => (array_sum($mape_05) / count($mape_05)) * 100), 
                    array('alpha' => 0.8, 'mape' => (array_sum($mape_08) / count($mape_08)) * 100)
                );

                Log::info(array('menu' => $actuals[0]['menu']['menu_name'], 'mape' => $all_mape));

                // Find minimum value of mape
                $min = min($all_mape[0]['mape'], $all_mape[1]['mape'], $all_mape[2]['mape']);

                $end02 = end($forecast_02);
                $end05 = end($forecast_05);
                $end08 = end($forecast_08);

                // Forecast for next month
                $last = $end02['forecast'] + (($alphas[0] * ($end02['actual'] - $end02['forecast'])));
                $future_month02 = $end02['month_id'] == 12 ? 1 : $end02['month_id']+1;
                $year02 = $end02['month_id'] == 12 ? $end02['year']+1 : $end02['year'];
                array_push($forecast_02, array('menu_id' => $actuals[0]['menu_id'], 'year' => $year02,'month_id' => $future_month02 , 'alpha' => $alphas[0], 'forecast' => $last));
                
                $last = $end05['forecast'] + (($alphas[1] * ($end05['actual'] - $end05['forecast'])));
                $future_month05 = $end05['month_id'] == 12 ? 1 : $end02['month_id']+1;
                $year05 = $end05['month_id'] == 12 ? $end05['year']+1 : $end05['year'];
                array_push($forecast_05, array('menu_id' => $actuals[0]['menu_id'], 'year' => $year05,'month_id' => $future_month05 , 'alpha' => $alphas[1], 'forecast' => $last));
                
                $last = $end08['forecast'] + (($alphas[2] * ($end08['actual'] - $end08['forecast'])));
                $future_month08 = $end08['month_id'] == 12 ? 1 : $end02['month_id']+1;
                $year08 = $end08['month_id'] == 12 ? $end08['year']+1 : $end08['year'];
                array_push($forecast_08, array('menu_id' => $actuals[0]['menu_id'], 'year' => $year08,'month_id' => $future_month08 , 'alpha' => $alphas[2], 'forecast' => $last));

                // Group fixed forecast
                $all_forecast_fix = array('02' => $forecast_02, '05' => $forecast_05, '08' => $forecast_08);

                $min = $all_mape[array_search($min, array_column($all_mape, 'mape'))]; // Find All Mape Index using minimun value from min
                $index_fix = str_replace('.', '', $min['alpha']);

                // Return all forecast based on minimum mape error
                $best_forecast = $all_forecast_fix[$index_fix];

                $final_forecast = [];
                foreach ($best_forecast as $best) {
                    unset($best['actual']);
                    array_push($final_forecast, $best);
                }

                Forecast::insert($final_forecast);
            }else{
                $final_forecast = [];
                foreach ($single_data as $single) {
                    unset($single['actual']);
                    array_push($final_forecast, $single);
                }

                Forecast::insert($final_forecast);
            }       
        }else{
            $forecast = Forecast::where('menu_id', $actualData->menu_id)->get();
            // Log::info($forecast);
            foreach($forecast as $fore){
                $fore->delete();
            }
        }
    }
}
