<?php

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
});

Route::get('split', function () {
    $int = "170030209";
    $split = str_split($int, strlen($int)/4);
    $arr = array(30, 10, 20);
    $test = array_search(30, $arr);

    if(strlen($test) == 0){
        return 'false';
    }else{
        return 'true';
    }

});

// Route::view('anjay', 'anjay');

Auth::routes();
