<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\ActualData;

class Month extends Model
{
    public $fillable = [
        'month'
    ];

    public function actual_data(){
        return $this->hasMany(ActualData::class);
    }
}
