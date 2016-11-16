<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('membership_restriction_types')->insert([
            'id'            => 1,
            'name'          => 'view_users',
            'display_name'  => 'Display Users List',
            'description'   => 'this is related to the users list in the back end, not the members/front end users',
            'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('membership_restriction_types')->insert([
            'id'            => 2,
            'name'          => 'roles-permission',
            'display_name'  => 'Roles Access',
            'description'   => 'Can create, modify, delete roles',
            'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('membership_restriction_types')->insert([
            'id'            => 3,
            'name'          => 'admin-permissions-management',
            'display_name'  => 'Back-end permissions management',
            'description'   => 'Have access to permission management in the backend',
            'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('membership_restriction_types')->insert([
            'id'            => 4,
            'name'          => 'create-user',
            'display_name'  => 'Create new users',
            'description'   => 'This permission will allow the creation of new employees with maximum role that they have.',
            'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('membership_restriction_types')->insert([
            'id'            => 5,
            'name'          => 'view-clients',
            'display_name'  => 'View list of clients per approved store',
            'description'   => 'This role gets you permission to view list of clients for stores that you are assigned to',
            'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('membership_restriction_types')->insert([
            'id'            => 6,
            'name'          => 'booking-change-update',
            'display_name'  => 'Modify existing bookings',
            'description'   => 'This is a function available for all back-end users',
            'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('membership_restriction_types')->insert([
            'id'            => 7,
            'name'          => 'backend-permission',
            'display_name'  => 'Permission to login to backend',
            'description'   => 'This is a default permission that everyone has, except the members and users of the front-end',
            'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('membership_restriction_types')->insert([
            'id'            => 8,
            'name'          => 'members-management',
            'display_name'  => 'Members Management',
            'description'   => 'Can update members information, can create members, can apply memberships to their accounts. ',
            'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
        ]);
        DB::table('membership_restriction_types')->insert([
            'id'            => 9,
            'name'          => 'manage-membership-plans',
            'display_name'  => 'No restrictions to Membership Management',
            'description'   => 'Create or modify existing memberships.',
            'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
        ]);
    }
}
