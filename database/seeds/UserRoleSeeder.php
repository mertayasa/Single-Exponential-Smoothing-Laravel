<?php

use Illuminate\Database\Seeder;

class UserRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('user_roles')->delete();
        \DB::table('user_roles')->insert(array(
            0 => array(
                'id' => '1',
                'role_name' => 'admin'
            ),
            1 => array(
                'id' => '2',
                'role_name' => 'editor'
            ),
        ));
    }
}
