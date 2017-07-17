<?php

namespace App\Http\Controllers\Federation;

use App\Http\Controllers\BackEndUserController as Base;
use App\OptimizeSearchMembers;
use App\Permission;
use App\PersonalDetail;
use App\ProfessionalDetail;
use App\Address;
use App\ShopLocations;
use App\ShopResourceCategory;
use App\UserMembership;
use App\UserSettings;
use App\UserAvatars;
use App\UserDocuments;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use Validator;
use Auth;
use Hash;
use Storage;
use DB;
use Mockery\CountValidator\Exception;
use \App\Role;
use Webpatser\Countries\Countries;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

class
BackEndUserController extends Base
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */



    public function index()
    {
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }
        elseif (!$user->can('manage-employees')){
            /*return [
                'success'   => false,
                'errors'    => 'You don\'t have permission to access this page',
                'title'     => 'Permission Error'];*/
            return redirect()->intended(route('admin/error/permission_denied'));
        }

        $back_users = User::
            whereHas('roles', function($query){
            $query->where('name', '=', 'employee');
        })->orWhereHas('roles', function($query){
            $query->where('name', '=', 'finance ');
        })->orWhereHas('roles', function($query){
            $query->where('name', '=', 'manager');
        })->orWhereHas('roles', function($query){
            $query->where('name', '=', 'owner');
        })->get();

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
        $countries = Countries::orderBy('name', 'asc')->get();

        return view('admin/back_users/federation/all_list', [
            'users' => $back_users,
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'all_roles'   => $all_roles,
            'countries' => $countries,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }
        elseif (!$user->can('manage-employees') && $user->id!=$id){
            /*return [
                'success'   => false,
                'errors'    => 'You don\'t have permission to access this page',
                'title'     => 'Permission Error'];*/
            return redirect()->intended(route('admin/error/permission_denied'));
        }

        $back_user = User::with('roles')->find($id);
        if ($back_user->is_back_user() === false){
            return redirect()->intended(route('admin/error/not_found'));
        }

        @$userRole = $back_user->roles[0];
        if (!$userRole){
            $defaultRole = Role::where('name','employee')->get();
            $userRole = $defaultRole[0];
        }
        $permissions = Permission::all();

        $userProfessional = $back_user->ProfessionalDetail;
        if (!isset($userProfessional)){
            $userProfessional = new ProfessionalDetail();
        }

        $userPersonal = $back_user->PersonalDetail;
        if (isset($userPersonal)) {
            $userPersonal->dob_format = Carbon::createFromFormat('Y-m-d', $userPersonal->date_of_birth)->format('d-m-Y');
            $userPersonal->dob_to_show = Carbon::createFromFormat('Y-m-d', $userPersonal->date_of_birth)->format('d M Y');
        }
        else{
            $userPersonal = new PersonalDetail();
        }

        $personalAddress = Address::find($userPersonal->address_id);
        if (!isset($personalAddress)){
            $personalAddress = new Address();
        }

        $roles = Role::all();
        $countries = Countries::orderBy('name')->get();
        $userCountry = Countries::find($back_user->country_id);

        $avatar = $back_user->get_avatar_image();

        $userDocuments = UserDocuments::where('user_id','=',$id)->where('category','=','account_documents')->get();

        $locations  = ShopLocations::orderBy('name')->get();
        $activities = ShopResourceCategory::orderBy('name')->get();
        $settings   = UserSettings::get_general_settings($back_user->id, ['settings_preferred_location','settings_preferred_activity']);

        $text_parts  = [
            'title'     => 'Back-End Users',
            'subtitle'  => $back_user->first_name.' '.$back_user->middle_name.' '.$back_user->last_name,
            'table_head_text1' => 'Backend User List'
        ];
        $sidebar_link= 'admin-backend-user_details_view';

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End Users'    => route('admin/back_users'),
            $back_user->first_name.' '.$back_user->middle_name.' '.$back_user->last_name => '',
        ];

        return view('admin/back_users/federation/user_details', [
            'user'      => $back_user,
            'userRole'  => $userRole,
            'professional' => $userProfessional,
            'personal'  => $userPersonal,
            'personalAddress' => $personalAddress,
            'countryDetails' => $userCountry,
            'countries' => $countries,
            'roles'     => $roles,
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'avatar'      => $avatar['avatar_base64'],
            'permissions' => $permissions,
            'documents'   => $userDocuments,
            'locations'     => $locations,
            'activities'    => $activities,
            'settings'      => $settings
        ]);
    }

}
