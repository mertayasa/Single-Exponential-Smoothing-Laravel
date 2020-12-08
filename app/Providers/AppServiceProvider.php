<?php

namespace App\Providers;

use App\Models\ActualData;
use App\Observers\ForecastObserver;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider{
    
    public function register(){
        //
    }

    public function boot(){
        ActualData::observe(ForecastObserver::class);
    }
}
