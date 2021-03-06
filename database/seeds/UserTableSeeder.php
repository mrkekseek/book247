<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
            'password'      => bcrypt('F\bn+uYX6S3}CeUj'),
            'country_id'    => 578,
            'status'        => 'active',
            'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        // Add system agent account
        DB::table('role_user')->insert([
            'user_id'   => 1,
            'role_id'   => 1,
        ]);

        // Add admin account
        DB::table('users')->insert([
            'id'            => 2,
            'first_name'    => 'System',
            'last_name'     => 'Agent',
            'gender'        => 'M',
            'username'      => 'sysagent',
            'email'         => 'agent@book247.net',
            'password'      => bcrypt(')w2C5nzMcA2V6bQ{'),
            'country_id'    => 578,
            'status'        => 'active',
            'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        // Add system agent account
        DB::table('role_user')->insert([
            'user_id'   => 2,
            'role_id'   => 1,
        ]);
    }
}
