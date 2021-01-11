<?php

namespace App\models;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Model;

class Forecast extends Model
{
    protected $fillable = [
        'menu_id',
        'month_id',
        'forecast',
        'alpha',
        'year',
    ];

    protected $with = [
        'menu',
        'month'
    ];

    public function menu(){
        return $this->belongsTo(Menu::class)->withTrashed();
    }

    public function month(){
        return $this->belongsTo(Month::class);
    }
    
}
