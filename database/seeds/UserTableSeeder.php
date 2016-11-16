<?php

use Illuminate\Database\Seeder;
use App\User as User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //factory(User::class, 20)->create();

        // Add admin account
        DB::table('users')->insert([
            'id'            => 1,
            'first_name'    => 'Root',
            'last_name'     => 'Administrator',
            'gender'        => 'M',
            'username'      => 'administrator',
            'email'         => 'email@book247.net',
            'password'      => bcrypt('test!@#$'),
            'country_id'    => 578,
            'status'        => 'active',
            'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        // Add system agent account
        DB::table('role_user')->insert([
            'user_id'   => 1,
            'role_id'   => 1,
        ]);

    }
}
