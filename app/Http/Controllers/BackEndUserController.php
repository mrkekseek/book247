<?php

namespace App\Http\Controllers;

use App\ProfessionalDetail;
use Illuminate\Http\Response;
use Mockery\CountValidator\Exception;
use Validator;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use Auth;
use \App\Role;
use Webpatser\Countries\Countries;

class BackEndUserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function index()
    {
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }

        /*$owner = new Role();
        $owner->name         = 'owner';
        $owner->display_name = 'Project Owner'; // optional
        $owner->description  = 'User is the owner of a given project'; // optional
        $owner->save();

        $admin = new Role();
        $admin->name         = 'admin';
        $admin->display_name = 'User Administrator'; // optional
        $admin->description  = 'User is allowed to manage and edit other users'; // optional
        $admin->save();*/

        $back_users = User::all();

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End User'     => route('admin'),
            'All Backend Users' => '',
        ];
        $text_parts  = [
            'title'     => 'Back-End Users',
            'subtitle'  => 'view all users',
            'table_head_text1' => 'Backend User List'
        ];
        $sidebar_link= 'admin-backend-all_users';

        $all_roles = Role::orderBy('name')->get();
        //xdebug_var_dump($all_roles);

        return view('admin/back_users/all_list', [
            'users' => $back_users,
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'all_roles'   => $all_roles,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $vars = $request->only('first_name', 'middle_name', 'last_name', 'email', 'user_type', 'username', 'password', 'user_type');
        $messages = array(
            'email.unique' => 'Please use an email that is not in the database',
        );
        $attributeNames = array(
            'email' => 'Email address',
            'username' => 'Username',
            'first_name' => 'First Name',
            'last_name' => 'Last Name',
            'password'  => 'Password',
        );
        $validator = Validator::make($vars, [
            'first_name' => 'required|min:4|max:150',
            'last_name' => 'required|min:4|max:150',
            'username' => 'required|min:6|max:30|unique:users,username',
            'password' => 'required|min:8',
            'email' => 'required|email|email|unique:users',
            'user_type' => 'required|exists:roles,id',
        ], $messages, $attributeNames);

        if ($validator->fails()){
            //return $validator->errors()->all();
            return array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }

        $credentials = $vars;
        $credentials['password'] = bcrypt($credentials['password']);
        try {
            $user = User::create($credentials);
            // attach the roles to the new created user
            $user->attachRole($vars['user_type']);

        } catch (Exception $e) {
            return Response::json(['error' => 'User already exists.'], Response::HTTP_CONFLICT);
        }

        return $vars;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }
        $back_user = User::with('roles')->find($id);
//xdebug_var_dump($back_user);
        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End User'     => route('admin'),
            'All Backend Users' => '',
        ];
        $text_parts  = [
            'title'     => 'Back-End Users',
            'subtitle'  => 'view all users',
            'table_head_text1' => 'Backend User List'
        ];
        $sidebar_link= 'admin-backend-all_users';

        $userRole = $back_user->roles[0];
        $userProfessional = $back_user->ProfessionalDetail;

        $roles = Role::all();
        $countries = Countries::orderBy('name')->get();

        return view('admin/back_users/user_details', [
            'user'      => $back_user,
            'userRole'  => $userRole,
            'professional' => $userProfessional,
            'countries' => $countries,
            'roles'     => $roles,
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_account_info(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }
        $vars = $request->only('accountDescription', 'accountJobTitle', 'accountProfession', 'accountEmail', 'accountUsername', 'employeeRole');
        $userVars = array('username'=>$vars["accountUsername"], 'email'=>$vars["accountEmail"]);
        $user = User::find($id);

        $validator = Validator::make($userVars, [
            'username' => 'required|min:6|max:30|unique:users,username, '.$id.', id',
            'email' => 'required|email|email|unique:users, email, '.$id.', id',
        ]);

        if ($validator->fails()){
            //return array(
            //    'success' => false,
            //    'errors' => $validator->getMessageBag()->toArray()
            //);
        }
        else{
            $user->username = $vars["accountUsername"];
            $user->email    = $vars["accountEmail"];
            $user->save();

            $user->attachRole($vars['employeeRole']);
        }

        $professionData = array('job_title'=>$vars['accountJobTitle'], 'profession'=>$vars['accountProfession'], 'description'=>$vars['accountDescription'], 'user_id'=>$id);
        $professionalDetails = ProfessionalDetail::firstOrNew(array('user_id'=>$id));
        $professionalDetails->job_title   = $professionData['job_title'];
        $professionalDetails->profession  = $professionData['profession'];
        $professionalDetails->description = $professionData['description'];
        $professionalDetails->save();

        return "bine";
    }

    public function update_account_avatar(Request $request, $id)
    {
        //
    }

    public function update_account_permission(Request $request, $id)
    {
        //
    }

    public function update_personal_info(Request $request, $id)
    {
        //
    }

    public function update_personal_address(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

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
}
