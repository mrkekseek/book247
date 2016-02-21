<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name'          => 'owner',
            'display_name'  => 'Business Owner/Partner',
            'description'   => 'This user role has full rights over the account',
        ]);

        DB::table('roles')->insert([
            'name'          => 'manager',
            'display_name'  => 'Business Manager',
            'description'   => 'This user role has restrictions over the finance part of the account',
        ]);

        DB::table('roles')->insert([
            'name'          => 'finance',
            'display_name'  => 'Finance Accountant',
            'description'   => 'This user role has restrictins to the administrative part of the account',
        ]);

        DB::table('roles')->insert([
            'name'          => 'employee',
            'display_name'  => 'Employee',
            'description'   => 'This user role has most of the restrictions and can do only the basic actions',
        ]);
    }
}
