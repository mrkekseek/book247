<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if (!\App\Permission::where('name','=','view_users')->get()->first()){
            DB::table('permissions')->insert([
                'id'            => 1,
                'name'          => 'view_users',
                'display_name'  => 'Display Users List',
                'description'   => 'this is related to the users list in the back end, not the members/front end users',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        if (!\App\Permission::where('name','=','roles-permission')->get()->first()){
            DB::table('permissions')->insert([
                'id'            => 2,
                'name'          => 'roles-permission',
                'display_name'  => 'Roles Access',
                'description'   => 'Can create, modify, delete roles',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        if (!\App\Permission::where('name','=','admin-permissions-management')->get()->first()){
            DB::table('permissions')->insert([
                'id'            => 3,
                'name'          => 'admin-permissions-management',
                'display_name'  => 'Back-end permissions management',
                'description'   => 'Have access to permission management in the backend',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        if (!\App\Permission::where('name','=','create-user')->get()->first()){
            DB::table('permissions')->insert([
                'id'            => 4,
                'name'          => 'create-user',
                'display_name'  => 'Create new users',
                'description'   => 'This permission will allow the creation of new employees with maximum role that they have.',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        if (!\App\Permission::where('name','=','view-clients')->get()->first()){
            DB::table('permissions')->insert([
                'id'            => 5,
                'name'          => 'view-clients',
                'display_name'  => 'View list of clients per approved store',
                'description'   => 'This role gets you permission to view list of clients for stores that you are assigned to',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        if (!\App\Permission::where('name','=','booking-change-update')->get()->first()){
            DB::table('permissions')->insert([
                'id'            => 6,
                'name'          => 'booking-change-update',
                'display_name'  => 'Modify existing bookings',
                'description'   => 'This is a function available for all back-end users',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        if (!\App\Permission::where('name','=','backend-permission')->get()->first()){
            DB::table('permissions')->insert([
                'id'            => 7,
                'name'          => 'backend-permission',
                'display_name'  => 'Permission to login to backend',
                'description'   => 'This is a default permission that everyone has, except the members and users of the front-end',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        if (!\App\Permission::where('name','=','members-management')->get()->first()){
            DB::table('permissions')->insert([
                'id'            => 8,
                'name'          => 'members-management',
                'display_name'  => 'Members Management',
                'description'   => 'Can update members information, can create members, can apply memberships to their accounts. ',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        if (!\App\Permission::where('name','=','manage-membership-plans')->get()->first()){
            DB::table('permissions')->insert([
                'id'            => 9,
                'name'          => 'manage-membership-plans',
                'display_name'  => 'No restrictions to Membership Management',
                'description'   => 'Create or modify existing memberships.',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        if (!\App\Permission::where('name','=','view-clients-list-all-clients')->get()->first()){
            DB::table('permissions')->insert([
                'id'            => 10,
                'name'          => 'view-clients-list-all-clients',
                'display_name'  => 'Access Clients List',
                'description'   => 'This impact access to the page + left side menu link visibility',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        if (!\App\Permission::where('name','=','view-clients-import-list')->get()->first()){
            DB::table('permissions')->insert([
                'id'            => 11,
                'name'          => 'view-clients-import-list',
                'display_name'  => 'Access Clients Import Page',
                'description'   => 'Can access the import page and use import functionality',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        if (!\App\Permission::where('name','=','view-membership-plans-add-new-plan')->get()->first()){
            DB::table('permissions')->insert([
                'id'            => 12,
                'name'          => 'view-membership-plans-add-new-plan',
                'display_name'  => 'Access Add new membership plan page',
                'description'   => 'A user have access to the membership plan add new functionality',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        if (!\App\Permission::where('name','=','view-calendar-products-add-new-products')->get()->first()){
            DB::table('permissions')->insert([
                'id'            => 13,
                'name'          => 'view-calendar-products-add-new-products',
                'display_name'  => 'Access Add new calendar product',
                'description'   => 'View add product page for calendar products',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        if (!\App\Permission::where('name','=','view-employees-menu')->get()->first()){
            DB::table('permissions')->insert([
                'id'            => 14,
                'name'          => 'view-employees-menu',
                'display_name'  => 'Access Employees Menu',
                'description'   => 'This will give access to all employees menu',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        if (!\App\Permission::where('name','=','view-shop-menu')->get()->first()){
            DB::table('permissions')->insert([
                'id'            => 15,
                'name'          => 'view-shop-menu',
                'display_name'  => 'View Shop Menu',
                'description'   => 'View shop menu and all sub-links',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        if (!\App\Permission::where('name','=','view-general-settings-menu')->get()->first()){
            DB::table('permissions')->insert([
                'id'            => 16,
                'name'          => 'view-general-settings-menu',
                'display_name'  => 'View General Settings Menu',
                'description'   => 'View general settings menu and all sub-links',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        if (!\App\Permission::where('name','=','manage-calendar-products')->get()->first()){
            DB::table('permissions')->insert([
                'id'            => 17,
                'name'          => 'manage-calendar-products',
                'display_name'  => 'Manage Calendar Products',
                'description'   => 'This will give access to edit part for products',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        if (!\App\Permission::where('name','=','create-calendar-products')->get()->first()){
            DB::table('permissions')->insert([
                'id'            => 18,
                'name'          => 'create-calendar-products',
                'display_name'  => 'Create Calendar Products',
                'description'   => 'This will give access to the calendar products creation',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        if (!\App\Permission::where('name','=','create-membership-plans')->get()->first()){
            DB::table('permissions')->insert([
                'id'            => 19,
                'name'          => 'create-membership-plans',
                'display_name'  => 'Create Membership Plans',
                'description'   => 'Gives access to membership plans creation form',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        if (!\App\Permission::where('name','=','manage-clients')->get()->first()){
            DB::table('permissions')->insert([
                'id'            => 20,
                'name'          => 'manage-clients',
                'display_name'  => 'Manage Clients',
                'description'   => 'Manage clients : registration, edit/update basic settings',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        if (!\App\Permission::where('name','=','manage-employees')->get()->first()){
            DB::table('permissions')->insert([
                'id'            => 21,
                'name'          => 'manage-employees',
                'display_name'  => 'Manage Employees',
                'description'   => 'Manage employees - add/view/edit.',
                'created_at'    => Carbon::now()->format('Y-m-d H:i:s')
            ]);
        }

        // now we add permissions to existing roles
        $permission_role = [
            // owner role
            ['permission_id'=>1,'role_id'=>1],
            ['permission_id'=>2,'role_id'=>1],
            ['permission_id'=>3,'role_id'=>1],
            ['permission_id'=>4,'role_id'=>1],
            ['permission_id'=>5,'role_id'=>1],
            ['permission_id'=>6,'role_id'=>1],
            ['permission_id'=>7,'role_id'=>1],
            ['permission_id'=>8,'role_id'=>1],
            ['permission_id'=>9,'role_id'=>1],
            ['permission_id'=>10,'role_id'=>1],
            ['permission_id'=>11,'role_id'=>1],
            ['permission_id'=>12,'role_id'=>1],
            ['permission_id'=>13,'role_id'=>1],
            ['permission_id'=>14,'role_id'=>1],
            ['permission_id'=>15,'role_id'=>1],
            ['permission_id'=>16,'role_id'=>1],
            ['permission_id'=>17,'role_id'=>1],
            ['permission_id'=>18,'role_id'=>1],
            ['permission_id'=>19,'role_id'=>1],
            ['permission_id'=>20,'role_id'=>1],

            // manager role
            ['permission_id'=>5,'role_id'=>2],
            ['permission_id'=>6,'role_id'=>2],
            ['permission_id'=>7,'role_id'=>2],
            ['permission_id'=>8,'role_id'=>2],
            ['permission_id'=>9,'role_id'=>2],
            ['permission_id'=>10,'role_id'=>2],
            ['permission_id'=>11,'role_id'=>2],
            ['permission_id'=>12,'role_id'=>2],
            ['permission_id'=>13,'role_id'=>2],
            ['permission_id'=>14,'role_id'=>2],
            ['permission_id'=>15,'role_id'=>2],
            ['permission_id'=>16,'role_id'=>2],
            ['permission_id'=>17,'role_id'=>2],
            ['permission_id'=>18,'role_id'=>2],
            ['permission_id'=>19,'role_id'=>2],
            ['permission_id'=>20,'role_id'=>2],

            // financial manager
            ['permission_id'=>1,'role_id'=>3],
            ['permission_id'=>3,'role_id'=>3],
            ['permission_id'=>5,'role_id'=>3],
            ['permission_id'=>6,'role_id'=>3],
            ['permission_id'=>7,'role_id'=>3],
            ['permission_id'=>8,'role_id'=>3],
            ['permission_id'=>9,'role_id'=>3],
            ['permission_id'=>10,'role_id'=>3],
            ['permission_id'=>11,'role_id'=>3],
            ['permission_id'=>20,'role_id'=>3],

            // simple employee
            ['permission_id'=>5,'role_id'=>4],
            ['permission_id'=>6,'role_id'=>4],
            ['permission_id'=>7,'role_id'=>4],
            ['permission_id'=>9,'role_id'=>4],
            ['permission_id'=>20,'role_id'=>4],

            // system agent
            ['permission_id'=>5,'role_id'=>7],
            ['permission_id'=>6,'role_id'=>7],
            ['permission_id'=>8,'role_id'=>7],
            ['permission_id'=>9,'role_id'=>7],
            ['permission_id'=>20,'role_id'=>7],
        ];
        foreach($permission_role as $pr){
            if (!DB::table('permission_role')->where('permission_id','=',$pr['permission_id'])->where('role_id','=',$pr['role_id'])->get()){
                // not found, make insert
                DB::table('permission_role')->insert([
                    'permission_id' => $pr['permission_id'],
                    'role_id'       => $pr['role_id'],
                ]);
            }
        }
    }
}