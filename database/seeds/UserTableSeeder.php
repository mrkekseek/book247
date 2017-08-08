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
            'username'      => 'email@book247.net',
            'email'         => 'email@book247.net',
            'password'      => bcrypt('F\bn+uYX6S3}CeUj'),
            'country_id'    => 578,
            'status'        => 'active',
            'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        // add personal details for the default owner
        DB::table('personal_details')->insert([
            'user_id'       => 1,
            'personal_email'=> 'email@book247.net',
            'mobile_number' => '123123123123123',
            'date_of_birth' => Carbon::now(),
            'about_info'    => 'default owner ID',
            'bank_acc_no'   => '',
            'social_sec_no' => '',
            'customer_number'   => 1
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
            'username'      => 'agent@book247.net',
            'email'         => 'agent@book247.net',
            'password'      => bcrypt(')w2C5nzMcA2V6bQ{'),
            'country_id'    => 578,
            'status'        => 'active',
            'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
        ]);

        // add personal details for System Agent
        DB::table('personal_details')->insert([
            'user_id'       => 2,
            'personal_email'=> 'agent@book247.net',
            'mobile_number' => '456456456456456',
            'date_of_birth' => Carbon::now(),
            'about_info'    => 'default system user',
            'bank_acc_no'   => '',
            'social_sec_no' => '',
            'customer_number'   => 2
        ]);

        // Add system agent account
        DB::table('role_user')->insert([
            'user_id'   => 2,
            'role_id'   => 1,
        ]);
    }
}
