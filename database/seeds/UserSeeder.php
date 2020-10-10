<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->delete();
        \DB::table('users')->insert(array(
            0 => array(
                'name' => 'admin',
                'email' => 'admin@demo.com',
                'password' => bcrypt('asdasd'),
                'user_role_id' => '1',
                'created_at' => \Carbon\Carbon::now(),
            ),
        ));
    }
}
