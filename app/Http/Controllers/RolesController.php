<?php

namespace App\Http\Controllers;

use App\Permission;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests;
//use App\User;
use Auth;
use Validator;
//use App\Http\Controllers\Controller;
use \App\Role;
use DB;

class RolesController extends Controller
{
    /** List all user roles */
    public function all_users_roles(){
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }

        $all_roles = Role::All();

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End User'     => route('admin'),
            'User Roles'        => '',
        ];
        $text_parts  = [
            'title'     => 'User Roles',
            'subtitle'  => 'add/edit/view roles',
            'table_head_text1' => 'Backend User Roles List'
        ];
        $sidebar_link= 'admin-backend-user_roles';

        return view('admin/back_users/all_roles', [
            'roles'       => $all_roles,
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
        ]);
    }

    /** Add new user role */
    public function add_user_role(Request $request){
        $vars = $request->only('name', 'display_name', 'description');
        $vars['name'] = trim($vars['name']);
        $message = array(
            'name.unique' => 'Duplicate Role name in the database.',
        );

        $validator = Validator::make($vars, Role::rules('POST'), $message, Role::$attributeNames);

        if ($validator->fails()){
            return array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }

        try {
            Role::create($vars);
        } catch (Exception $e) {
            return Response::json(['error' => 'Role already exists.'], Response::HTTP_CONFLICT);
        }

        return $vars;
    }

    /** Update user role */
    public function update_user_role(Request $request){
        $message = array(
            'name.unique' => 'Duplicate Role name in the database.',
        );
        $vars = $request->only('name', 'display_name', 'description', 'dataID');
        $roleID = $vars['dataID'];

        $roleToUpdate = Role::find(array('id'=>$roleID))->first();
        if (!$roleToUpdate){
            return array(
                'success' => false,
                'errors'  => 'Something went wrong',
            );
        }
        else{
            $validator = Validator::make($vars, Role::rules('PUT', $roleID), $message, Role::$attributeNames);

            if ($validator->fails()){
                return array(
                    'success' => false,
                    'errors' => $validator->getMessageBag()->toArray()
                );
            }
        }

        try {
            $roleToUpdate->name = trim($vars['name']);
            $roleToUpdate->display_name = $vars['display_name'];
            $roleToUpdate->description = $vars['description'];
            $roleToUpdate->save();
        }
        catch (Exception $ex){
            return array(
                'success' => false,
                'errors'  => 'Error while updating fields. Please reload page then try again!',
            );
        }

        return array(
            'success' => true,
            'message' => 'Role updated successfully!',
        );
    }

    /** Delete user role */
    public function delete_user_role(Request $request){
        $vars = $request->only('dataID');

        $roleToUpdate = Role::find(array('id'=>$vars['dataID']))->first();
        if (!$roleToUpdate){
            return array(
                'success' => false,
                'errors'  => 'Something went wrong',
            );
        }

        try {
            $roleToUpdate->delete();
        }
        catch (Exception $ex){
            return array(
                'success' => false,
                'errors'  => 'Error while deleting. Please reload page then try again!',
            );
        }

        return array(
            'success' => true,
            'message' => 'Role deleted successfully!',
        );
    }

    // list permissions, manage existing ones, add new permissions
    public function list_permissions(){
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }

        $permissions = Permission::all();

        $assigned_to = array();
        $permission_roles = DB::table('permission_role')->get();
        foreach($permission_roles as $perm_role){
            $var_tmp = DB::table('roles')->where('id','=',$perm_role->role_id)->get();
            $assigned_to[$perm_role->permission_id][] = $var_tmp[0]->display_name;
        }

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End User'     => route('admin'),
            'Permissions'        => '',
        ];
        $text_parts  = [
            'title'     => 'All Permissions',
            'subtitle'  => 'add/edit/view permissions',
            'table_head_text1' => 'Backend Roles Permissions List'
        ];
        $sidebar_link= 'admin-backend-roles_permission';

        return view('admin/back_users/all_permissions', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'permissions' => $permissions,
            'assigned_to' => $assigned_to,
        ]);
    }

    public function add_permission(Request $request){
        $vars = $request->only('name', 'display_name', 'description');
        $vars['name'] = trim($vars['name']);
        $message = array(
            'name.unique' => 'Duplicate Permission name in the database.',
        );

        $validator = Validator::make($vars, Permission::rules('POST'), $message, Permission::$attributeNames);

        if ($validator->fails()){
            return array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }

        try {
            Permission::create($vars);
        } catch (Exception $e) {
            return Response::json(['error' => 'Role already exists.'], Response::HTTP_CONFLICT);
        }

        return $vars;
    }

    public function view_permission($id){
        $permission = Permission::find($id);
        $roles = Role::all(['id','display_name']);
        $assigned_to = array();

        $permission_roles = DB::table('permission_role')->where('permission_id',$id)->get();
        foreach($permission_roles as $perm_role){
            $assigned_to[$perm_role->role_id] = true;
        }

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End User'     => route('admin'),
            'Permissions'        => '',
        ];
        $text_parts  = [
            'title'     => 'All Permissions',
            'subtitle'  => 'add/edit/view permissions',
            'table_head_text1' => 'Backend Roles Permissions - '.$permission->display_name
        ];
        $sidebar_link= 'admin-backend-roles_permission';

        return view('admin/back_users/view_permission', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'permission' => $permission,
            'roles' => $roles,
            'assigned_to' => $assigned_to,
        ]);
    }

    public function update_permission(Request $request, $id){
        $permission = Permission::find($id);

        if(!$permission){
            return 'Error';
        }

        $vars = $request->only('name', 'display_name', 'description', 'assign_roles');
        $vars['name'] = trim($vars['name']);
        $message = array(
            'name.unique' => 'Duplicate Permission name in the database.',
        );

        $validator = Validator::make($vars, Permission::rules('PUT', $id), $message, Permission::$attributeNames);

        if ($validator->fails()){
            return array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }

        /** update permission details */
        $permission->name = $vars['name'];
        $permission->display_name = $vars['display_name'];
        $permission->description = $vars['description'];
        $permission->save();

        /** update permissions roles */
        $permission_roles = Role::all();
        foreach($permission_roles as $perm_role){
            $perm_role->detachPermission($id);

            if (in_array($perm_role->id, $vars['assign_roles'])){
                $perm_role->attachPermission($id);
            }
        }

        return 'Echo';
    }
}
