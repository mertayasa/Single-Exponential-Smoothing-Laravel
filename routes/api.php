<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('fcm', function () {
    $url = 'https://fcm.googleapis.com/fcm/send';

    $field = array (
            "to" => '/topics/notice',
            'notification' => array (
                "body" => "Test Server",
                "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                "sound" => "default",
                "title" => "postman"          
            ),
            'data' => array (
                "body" => "Test Server",
                "click_action" => "FLUTTER_NOTIFICATION_CLICK",
                "sound" => "default",
                "title" => "postman" 
            )
    );
    $fields = json_encode ( $field );

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
    $anjay = curl_getinfo($ch);
    // return $anjay;
    return $result;
    curl_close ( $ch );
});

Route::post('webhook', 'API\WebhookController@handle');
