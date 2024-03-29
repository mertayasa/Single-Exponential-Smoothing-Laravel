<?php

use App\Models\ActualData;
use App\models\Forecast;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => 'auth'], function () {
    Route::get('/', 'DashboardController@index')->name('dashboard');


    Route::get('product_category', 'ProductCategoryController@index')->name('product_category.index');
    Route::get('product_category/create', 'ProductCategoryController@create')->name('product_category.create');
    Route::post('product_category/store', 'ProductCategoryController@store')->name('product_category.store');
    Route::get('product_category/{id}', 'ProductCategoryController@edit')->name('product_category.edit');
    Route::patch('product_category_update/{id}', 'ProductCategoryController@update')->name('product_category.update');
    Route::get('product_category_destroy/{id}', 'ProductCategoryController@destroy')->name('product_category.destroy');

    Route::get('menu', 'MenuController@index')->name('menu.index');
    Route::get('menu/get-menu', 'MenuController@getMenu')->name('menu.get_menu');
    Route::get('menu/create', 'MenuController@create')->name('menu.create');
    Route::post('menu/store', 'MenuController@store')->name('menu.store');
    Route::get('menu/{id}', 'MenuController@edit')->name('menu.edit');
    Route::patch('menu_update/{id}', 'MenuController@update')->name('menu.update');
    Route::get('menu_destroy/{id}', 'MenuController@destroy')->name('menu.destroy');

    Route::get('forecast', 'ForecastController@index')->name('forecast.index');
    Route::get('forecast/create', 'ForecastController@create')->name('forecast.create');
    Route::post('forecast/store', 'ForecastController@store')->name('forecast.store');
    Route::get('forecast/{id}', 'ForecastController@edit')->name('forecast.edit');
    Route::patch('forecast_update/{id}', 'ForecastController@update')->name('forecast.update');
    Route::get('forecast_destroy/{id}', 'ForecastController@destroy')->name('forecast.destroy');
    Route::get('forecast-detail/{product_id}', 'ForecastController@show')->name('forecast.show');

    Route::get('actual-data', 'ActualDataController@index')->name('actual_data.index');
    Route::get('actual-data/create', 'ActualDataController@create')->name('actual_data.create');
    Route::post('actual-data/store', 'ActualDataController@store')->name('actual_data.store');
    Route::get('actual-data/{id}', 'ActualDataController@edit')->name('actual_data.edit');
    Route::patch('actual-data-update/{id}', 'ActualDataController@update')->name('actual_data.update');
    Route::get('actual-data-destroy/{id}', 'ActualDataController@destroy')->name('actual_data.destroy');
    Route::get('actual-data-month/{product_id}', 'ActualDataController@getMonthLeft')->name('actual_data.getmonth');

    Route::get('forecast-export/{method}', 'ForecastController@exportOveral')->name('forecast.export_overal');
    Route::get('forecast-export/{method}/{product_id}', 'ForecastController@exportHistory')->name('forecast.export_histori');

});

Route::get('asdasd', function(){
        $actuals = ActualData::where('product_id', 1)->orderBy('year', 'ASC')->orderBy('month_id', 'ASC')->get()->toArray();

        Forecast::where('product_id', $actuals[0]['product_id'])->delete();

        $single_data = [];
        $forecast_temp = [];
        $forecast_02 = [];
        $forecast_05 = [];
        $forecast_08 = [];

        // Group all alpha
        $alphas = array(0.2, 0.5, 0.8);

        array_push($single_data, array('product_id' => $actuals[0]['product_id'], 'year' => $actuals[0]['year'],'month_id' => $actuals[0]['month_id'] , 'alpha' => 0, 'actual' => $actuals[0]['actual'], 'forecast' => $actuals[0]['actual']));
        array_push($single_data, array('product_id' => $actuals[0]['product_id'], 'year' => $actuals[0]['year'],'month_id' => $actuals[0]['month_id']+1 , 'alpha' => 0, 'actual' => $actuals[0]['actual'], 'forecast' => $actuals[0]['actual']));
        
        if(count($actuals) > 1){
            array_push($forecast_02, array('product_id' => $actuals[0]['product_id'], 'year' => $actuals[0]['year'],'month_id' => $actuals[0]['month_id'] , 'alpha' => $alphas[0], 'actual' => $actuals[0]['actual'], 'forecast' => $actuals[0]['actual']));
            
            array_push($forecast_05, array('product_id' => $actuals[0]['product_id'], 'year' => $actuals[0]['year'],'month_id' => $actuals[0]['month_id'] , 'alpha' => $alphas[1], 'actual' => $actuals[0]['actual'], 'forecast' => $actuals[0]['actual']));
            
            array_push($forecast_08, array('product_id' => $actuals[0]['product_id'], 'year' => $actuals[0]['year'],'month_id' => $actuals[0]['month_id'] , 'alpha' => $alphas[2], 'actual' => $actuals[0]['actual'], 'forecast' => $actuals[0]['actual']));
            
            array_push($forecast_02, array('product_id' => $actuals[0]['product_id'], 'year' => $actuals[1]['year'],'month_id' => $actuals[1]['month_id'] , 'alpha' => $alphas[0], 'actual' => $actuals[1]['actual'], 'forecast' => $actuals[0]['actual']));
            array_push($forecast_05, array('product_id' => $actuals[0]['product_id'], 'year' => $actuals[1]['year'],'month_id' => $actuals[1]['month_id'] , 'alpha' => $alphas[1], 'actual' => $actuals[1]['actual'], 'forecast' => $actuals[0]['actual']));
            array_push($forecast_08, array('product_id' => $actuals[0]['product_id'], 'year' => $actuals[1]['year'],'month_id' => $actuals[1]['month_id'] , 'alpha' => $alphas[2], 'actual' => $actuals[1]['actual'], 'forecast' => $actuals[0]['actual']));

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
                            array_push($forecast_temp, array('product_id' => $actuals[0]['product_id'], 'year' => $value['year'],'month_id' => $value['month_id'] , 'alpha' => $alpha, 'forecast' => $last, 'actual' => $actuals[$key]['actual']));
                        }
                        else{
                            $last = $last + (($alpha * ($actuals[$key-1]['actual'] - $last)));
                            array_push($forecast_temp, array('product_id' => $actuals[0]['product_id'], 'year' => $value['year'],'month_id' => $value['month_id'] , 'alpha' => $alpha, 'forecast' => $last, 'actual' => $actuals[$key]['actual']));
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

            // Find minimum value of mape
            $min = min($all_mape[0]['mape'], $all_mape[1]['mape'], $all_mape[2]['mape']);

            $end02 = end($forecast_02);
            $end05 = end($forecast_05);
            $end08 = end($forecast_08);

            // Forecast for next month
            $last = $end02['forecast'] + (($alphas[0] * ($end02['actual'] - $end02['forecast'])));
            $future_month02 = $end02['month_id'] == 12 ? 1 : $end02['month_id']+1;
            array_push($forecast_02, array('product_id' => $actuals[0]['product_id'], 'year' => $value['year'],'month_id' => $future_month02 , 'alpha' => $alphas[0], 'forecast' => $last));
            
            $last = $end05['forecast'] + (($alphas[1] * ($end05['actual'] - $end05['forecast'])));
            $future_month05 = $end05['month_id'] == 12 ? 1 : $end02['month_id']+1;
            array_push($forecast_05, array('product_id' => $actuals[0]['product_id'], 'year' => $value['year'],'month_id' => $future_month05 , 'alpha' => $alphas[1], 'forecast' => $last));
            
            $last = $end08['forecast'] + (($alphas[2] * ($end08['actual'] - $end08['forecast'])));
            $future_month08 = $end08['month_id'] == 12 ? 1 : $end02['month_id']+1;
            array_push($forecast_08, array('product_id' => $actuals[0]['product_id'], 'year' => $value['year'],'month_id' => $future_month08 , 'alpha' => $alphas[2], 'forecast' => $last));

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
        }

    return dump($final_forecast);

});

Route::get('anjay', function(){
    return view('test.index');
});

// Route::get('split', function () {
//     $int = "170030209";
//     $split = str_split($int, strlen($int)/4);
//     $arr = array(30, 10, 20);
//     $test = array_search(30, $arr);

//     if(strlen($test) == 0){
//         return 'false';
//     }else{
//         return 'true';
//     }

// });

Route::get('fcm', function () {
    $url = 'https://fcm.googleapis.com/fcm/send';

    $fields = array (
            "topic" => "subdivision",
            'notification' => array (
                "body" => "sdhlasdhlasdhaideabcsjcbakjc",
                "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                "sound" => "default",
                "title" => "postman"          
            ),
            'data' => array (
                "body" => "sdhlasdhlasdhaideabcsjcbakjc",
                "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                "sound" => "default",
                "title" => "postman" 
            )
    );
    $fields = json_encode ( $fields );

    $headers = array (
            'Authorization: key=' . "AAAAGxMtx-w:APA91bGoMNhAXrb3CQ9QHGAwULH-HN-44DMc0uY7mAB991R248BEafSszKZzXnIuWBzBRUqRi-U4fEvnxiU_KfgxT-4qUdmdnw2-kyX7kP_FNpC3DbJyIR5ugJtpnrutek6ebw39b9WV",
            'Content-Type: application/json'
    );

    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_URL, $url );
    curl_setopt ( $ch, CURLOPT_POST, true );
    curl_setopt ( $ch, CURLOPT_HTTPHEADER, $headers );
    curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, true );
    curl_setopt ( $ch, CURLOPT_POSTFIELDS, $fields );

    $result = curl_exec ( $ch );
    echo $result;
    curl_close ( $ch );
});

Auth::routes();
