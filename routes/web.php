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


    Route::get('product_category', 'ProductCategoryController@index')->name('product_category.index');
    Route::get('product_category/create', 'ProductCategoryController@create')->name('product_category.create');
    Route::post('product_category/store', 'ProductCategoryController@store')->name('product_category.store');
    Route::get('product_category/{id}', 'ProductCategoryController@edit')->name('product_category.edit');
    Route::patch('product_category_update/{id}', 'ProductCategoryController@update')->name('product_category.update');
    Route::get('product_category_destroy/{id}', 'ProductCategoryController@destroy')->name('product_category.destroy');

    Route::get('product', 'ProductController@index')->name('product.index');
    Route::get('product/create', 'ProductController@create')->name('product.create');
    Route::post('product/store', 'ProductController@store')->name('product.store');
    Route::get('product/{id}', 'ProductController@edit')->name('product.edit');
    Route::patch('product_update/{id}', 'ProductController@update')->name('product.update');
    Route::get('product_destroy/{id}', 'ProductController@destroy')->name('product.destroy');

    // Route::get('product', 'ProductController@index')->name('product.index');
    // Route::get('product/create', 'ProductController@create')->name('product.create');
    // Route::post('product/store', 'ProductController@store')->name('product.store');
    // Route::get('product/{id}', 'ProductController@edit')->name('product.edit');
    // Route::patch('product_update/{id}', 'ProductController@update')->name('product.update');
    // Route::get('product_destroy/{id}', 'ProductController@destroy')->name('product.destroy');

    Route::get('actual_data', 'ActualDataController@index')->name('actual_data.index');
    Route::get('actual_data/create', 'ActualDataController@create')->name('actual_data.create');
    Route::post('actual_data/store', 'ActualDataController@store')->name('actual_data.store');
    Route::get('actual_data/{id}', 'ActualDataController@edit')->name('actual_data.edit');
    Route::patch('actual_data_update/{id}', 'ActualDataController@update')->name('actual_data.update');
    Route::get('actual_data_destroy/{id}', 'ActualDataController@destroy')->name('actual_data.destroy');
    Route::get('actual_data_month/{product_id}', 'ActualDataController@getMonthLeft')->name('actual_data.getmonth');

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

// Route::view('anjay', 'anjay');

Auth::routes();
