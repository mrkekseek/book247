<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'id'            => 1,
                'name'          => 'owner',
                'display_name'  => 'Business Owner/Partner',
                'description'   => 'This user role has full rights over the account',
            ],
            [
                'id'            => 2,
                'name'          => 'manager',
                'display_name'  => 'Business Manager',
                'description'   => 'This user role has restrictions over the finance part of the account',
            ],
            [
                'id'            => 3,
                'name'          => 'finance',
                'display_name'  => 'Finance Accountant',
                'description'   => 'This user role has restrictins to the administrative part of the account',
            ],
            [
                'id'            => 4,
                'name'          => 'employee',
                'display_name'  => 'Employee',
                'description'   => 'This user role has most of the restrictions and can do only the basic actions',
            ],
            [
                'id'            => 5,
                'name'          => 'front-member',
                'display_name'  => 'Registered User with Membership',
                'description'   => 'A registered front end user with membership',
            ],
            [
                'id'            => 6,
                'name'          => 'front-user',
                'display_name'  => 'Registered User without Membership',
                'description'   => 'A registered front end user without membership, on a pay-as-you-go status',
            ],
            [
                'id'            => 7,
                'name'          => 'system-agent',
                'display_name'  => 'System Agent',
                'description'   => 'Sends messages to users/members - the automated process',
            ]
        ];


        foreach ($roles as $role) {;
            if (DB::table('roles')->where('id',$role['id'])->first()) {
                DB::table('roles')->where('id',$role['id'])->update([
                    'name'          => $role['name'],
                    'display_name'  => $role['display_name'],
                    'description'   => $role['description'],
                ]);
            } else {
                DB::table('roles')->insert([
                    'id'            => $role['id'],
                    'name'          => $role['name'],
                    'display_name'  => $role['display_name'],
                    'description'   => $role['description'],
                ]);
            }

        }

    }
}
