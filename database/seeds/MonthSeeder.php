<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MonthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $month = [
            ['month' => 'Januari'],
            ['month' => 'Februari'],
            ['month' => 'Maret'],
            ['month' => 'April'],
            ['month' => 'Mei'],
            ['month' => 'Juni'],
            ['month' => 'Juli'],
            ['month' => 'Agustus'],
            ['month' => 'September'],
            ['month' => 'Oktober'],
            ['month' => 'November'],
            ['month' => 'Desember'],
        ];

        DB::table('months')->insert($month);
    }
}
