<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Models\ActualData;
use Faker\Generator as Faker;

$factory->define(ActualData::class, function (Faker $faker) {
    return [
        'actual' => $faker->numberBetween(810, 930),
        'product_id' => 13,
        'month_id' => $faker->unique()->numberBetween(1,10),
        'year' => 2020
    ];
});
