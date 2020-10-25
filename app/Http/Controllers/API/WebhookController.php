<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Service\LineService;
use Illuminate\Http\Request;

class WebhookController extends Controller
{
    public function handle(Request $request){
        $test = new LineService();
        return 'fnbvnefb';
    }
}
