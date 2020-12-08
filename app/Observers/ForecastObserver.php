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
        Log::info('Created');
    }

    public function updated(ActualData $actualData){
        Log::info('Updated');
    }

    public function deleted(ActualData $actualData){
        Log::info('Deleted');
    }

    public function restored(ActualData $actualData){
        //
    }

    public function forceDeleted(ActualData $actualData){
        //
    }
}
