<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\ActualData;

class Menu extends Model
{
    use SoftDeletes;
    public $fillable = [
        'menu_name',
        // 'product_category_id'
    ];

    public function actual_data(){
        return $this->hasMany(ActualData::class);
    }
}
