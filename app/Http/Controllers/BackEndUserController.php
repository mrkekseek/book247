<?php

namespace App\Http\Controllers;

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
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return [
                'success' => false,
                'errors'  => 'Error while trying to authenticate. Login first then use this function.',
                'title'   => 'Not logged in'];
        }
        elseif (!$user->can('manage-employees')){
            return [
                'success'   => false,
                'errors'    => 'You don\'t have permission to access this page',
                'title'     => 'Permission Error'];
            /*return redirect()->intended(route('admin/error/permission_denied'));*/
        }

        $vars = $request->only('first_name', 'middle_name', 'last_name', 'email', 'user_type', 'username', 'password', 'user_type');

        if (!isset($vars['middle_name'])){
            $vars['middle_name'] = '';
        }

        if (!isset($vars['username'])){
            $vars['username'] = $vars['email'];
        }

        if (!isset($vars['user_type'])){
            $vars['user_type'] = Role::where('name','=','employee')->get()->first()->id;
        }

        if ($vars['password']==""){
            $vars['password'] = substr(bcrypt(str_random(12)),0,8);
        }

        if (!isset($vars['country_id'])){
            $vars['country_id'] = Config::get('constants.globalWebsite.defaultCountryId');
        }
        
        $credentials = [
            'first_name'    => $vars['first_name'],
            'middle_name'   => $vars['middle_name'],
            'last_name'     => $vars['last_name'],
            'username'      => $vars['email'],
            'email'         => $vars['email'],
            'password'      => $vars['password'],
            'password_api'      => $vars['password'],
            'country_id'    => $vars['country_id'],
            'status'        => 'active',
            'user_type'     => $vars['user_type']
        ];
        $validator = Validator::make($credentials, User::rules('POST'), User::$messages, User::$attributeNames);
        if ($validator->fails()){
            //return $validator->errors()->all();
            return array(
                'success'   => false,
                'title'     => 'Error validating',
                'errors'    => $validator->getMessageBag()->toArray()
            );
        }

        $credentials['password'] = bcrypt($credentials['password']);
        try {
            $user = User::create($credentials);
            // attach the roles to the new created user
            $user->attachRole($vars['user_type']);

            return [
                'success'   => true,
                'title'     => 'User Created',
                'message'   => 'New backend user created and will be available in the list after the page refreshes.'
            ];
        }
        catch (Exception $e) {
            return [
                'success'   => false,
                'title'     => 'User already exists',
                'errors'    => 'Please check the errors and fix them.'
            ];
        }
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

        return view('admin/back_users/user_details', [
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
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $vars = $request->only( 'accountDescription', 'accountJobTitle', 'accountProfession', 'accountEmail', 'accountUsername', 'employeeRole',
                                'settings_preferred_location', 'settings_preferred_activity');
        $userVars = array('username'=>$vars["accountUsername"], 'email'=>$vars["accountEmail"]);
        $userCh = User::with('roles')->find($id);

        $validator = Validator::make($userVars, [
            'username' => 'required|min:6|max:30|unique:users,username,'.$id.',id',
            'email' => 'required|email|email|unique:users,email,'.$id.',id',
        ]);

        if ($validator->fails()){
            //return array(
            //    'success' => false,
            //    'errors' => $validator->getMessageBag()->toArray()
            //);
        }
        else{
            $userCh->username = $vars["accountUsername"];
            $userCh->email    = $vars["accountEmail"];
            @$userCh->detachRoles($userCh->roles);
            $userCh->attachRole($vars['employeeRole']);
            $userCh->save();
        }

        $professionData = array('job_title'=>$vars['accountJobTitle'], 'profession'=>$vars['accountProfession'], 'description'=>$vars['accountDescription'], 'user_id'=>$id);
        $professionalDetails = ProfessionalDetail::firstOrNew(array('user_id'=>$id));
        $professionalDetails->job_title   = $professionData['job_title'];
        $professionalDetails->profession  = $professionData['profession'];
        $professionalDetails->description = $professionData['description'];
        $professionalDetails->save();

        // general settings related to default location
        $fillable = ['user_id'   => $userCh->id,'var_name'  => 'settings_preferred_location'];
        $storeSetting = UserSettings::firstOrNew($fillable);
        $storeSetting->var_value = $vars['settings_preferred_location'];
        $storeSetting->save();
        // general settings related to default activity
        $fillable = ['user_id'   => $userCh->id,'var_name'  => 'settings_preferred_activity'];
        $storeSetting = UserSettings::firstOrNew($fillable);
        $storeSetting->var_value = $vars['settings_preferred_activity'];
        $storeSetting->save();

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
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $vars = $request->only('about_info', 'country_id', 'date_of_birth', 'first_name', 'last_name', 'middle_name', 'gender', 'mobile_number', 'personal_email', 'bank_acc_no', 'social_sec_no');

        $userVars = [
            'first_name'    => $vars["first_name"],
            'last_name'     => $vars["last_name"],
            'middle_name'   => $vars["middle_name"],
            'country_id'    => $vars["country_id"],
            'date_of_birth' => $vars["date_of_birth"],
            'gender'        => $vars['gender']
        ];
        $userCh = User::find($id);

        $updateRules = [
            'first_name'=> 'required|min:4|max:150',
            'last_name' => 'required|min:4|max:150',
            'gender'    => 'in:M,F'
        ];
        $validator = Validator::make($userVars, $updateRules, User::$messages, User::$attributeNames);
        if ($validator->fails()){
            return array(
                'success' => false,
                'errors' => $validator->getMessageBag()->toArray()
            );
        }
        else{
            $userCh->first_name  = $vars["first_name"];
            $userCh->last_name   = $vars["last_name"];
            $userCh->middle_name = $vars["middle_name"];
            $userCh->country_id  = $vars["country_id"];
            $userCh->gender      = $vars["gender"];
            $userCh->save();
        }

        $personalData = array(  'personal_email'=> $vars['personal_email'],
                                'mobile_number' => $vars['mobile_number'],
                                'date_of_birth' => Carbon::createFromFormat('d-m-Y', $vars['date_of_birth'])->toDateString(),
                                'bank_acc_no'   => $vars['bank_acc_no'],
                                'social_sec_no' => $vars['social_sec_no'],
                                'about_info'    => $vars['about_info'],
                                'user_id'       => $id);
        $validator = Validator::make($personalData, PersonalDetail::rules('PUT',$id), PersonalDetail::$messages, PersonalDetail::$attributeNames);
        if ($validator->fails()){
            return array(
                'success'   => false,
                'title'     => 'You have some errors',
                'errors'    => $validator->getMessageBag()->toArray()
            );
        }
        else{
            $personalDetails = PersonalDetail::firstOrNew(array('user_id'=>$id));
            $personalDetails->fill($personalData);
            $personalDetails->save();
        }

        return array(
            'success'   => true,
            'title'     => 'Personal details updated',
            'message'   => 'All information were updated and saved'
        );
    }

    public function update_personal_address(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $userPersonal = PersonalDetail::find($id);

        $vars = $request->only('address1', 'address2', 'city', 'country_id', 'postal_code', 'region');
        $validator = Validator::make($vars, [
            'address1'    => 'required|min:5|max:150',
            'city'        => 'required|min:3|max:150',
            'region'      => 'required|min:2',
            'postal_code' => 'required|min:2',
            'country_id'  => 'required|exists:countries,id',
        ]);

        if ($validator->fails()){
            //return array(
            //    'success' => false,
            //    'errors' => $validator->getMessageBag()->toArray()
            //);
        }
        else{
            if ( !isset($userPersonal) || $userPersonal->address_id==0 ){
                $personalAddress = new Address();
                $userPersonal = new PersonalDetail();
                $userPersonal->user_id = $id;
            }
            else {
                $addressID = $userPersonal->address_id;
                $personalAddress = Address::find($addressID);
            }

            $personalAddress->fill([
                'user_id'   => $id,
                'address1'  => $vars['address1'],
                'address2'  => $vars['address2'],
                'city'      => $vars['city'],
                'region'    => $vars['region'],
                'postal_code'   => $vars['postal_code'],
                'country_id'    => $vars['country_id'],
            ]);
            $personalAddress->save();

            $userPersonal->address_id = $personalAddress->id;
            $userPersonal->save();
        }

        return "bine";
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

    /** Change password */
    public function updatePassword(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return ['success' => false,
                    'errors'  => 'Error while trying to authenticate. Login first then use this function.',
                    'title'   => 'Not logged in'];
        }

        $backUser = User::where('id','=',$id)->get()->first();
        if (!$backUser){
            return ['success' => false,
                    'title'   => 'User error',
                    'errors'  => 'Something went wrong while trying to access requested user.'];
        }

        $userVars = $request->only('old_password','password1','password2');
        if ($user->id!=$backUser->id && !$user->can('manage-employees')){
            $validator = Validator::make($userVars, [
                'old_password'  => 'required|min:8',
                'password1'     => 'required|min:8',
                'password2'     => 'required|min:8|same:password1',
            ]);
        }
        else{
            $validator = Validator::make($userVars, [
                'password1'     => 'required|min:8',
                'password2'     => 'required|min:8|same:password1',
            ]);
        }

        if ($validator->fails()){
            return ['success' => false,
                    'title'   => 'Validation error',
                    'errors'  => 'Entered passwords are not within minimum requirements.'];
        }
        else {
            $auth = auth();
            if ($auth->attempt([ 'id' => $id, 'password' => $userVars['old_password'] ]) || $user->can('manage-employees')) {
                $backUser->fill(['password' => Hash::make($request->password1)])->save();
                return ['success' => true,
                        'title'   => 'Password changed',
                        'message' => 'Everything went well, password was changed'];
            }
            else{
                return ['success' => false,
                        'title'   => 'Permission/Old password error',
                        'errors'  => 'You don\'t have permission to change this user password or old password error.'];
            }
        }
    }

    public function update_personal_avatar(Request $request, $id){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $user = User::findOrFail($id);

        $avatarLocation = 'employees/'.$id.'/avatars/';
        $avatarFilename = $user->username.'.'.$request->file('user_avatar')->getClientOriginalExtension();
        $exists = Storage::disk('local')->exists($avatarLocation . $avatarFilename);
        if ($exists){
            Storage::disk('local')->move( $avatarLocation . $avatarFilename, $avatarLocation . time().'-'.$avatarFilename.'.old');
        }

        $avatarData = [
            'user_id'   => $id,
            'file_name' => $avatarFilename,
            'file_location' => $avatarLocation,
            'width' => 0,
            'height'=> 0
        ];

        $avatar = UserAvatars::find(['user_id' => $id])->first();
        if (!$avatar) {
            $avatar = new UserAvatars();
        }
        $avatar->fill($avatarData);
        $avatar->save();

        Storage::disk('local')->put(
            $avatarLocation . $avatarFilename,
            file_get_contents($request->file('user_avatar')->getRealPath())
        );

        //return redirect('admin/back_users/view_user/'.$id);
        return redirect()->intended(route('admin/back_users/view_user/', ['id' => $id]));
    }

    public function add_account_document(Request $request, $id){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $user = User::findOrFail($id);
//xdebug_var_dump($request); exit;
        $documentLocation = 'employees/'.$id.'/documents/';
        $documentFilename = $request->file('user_doc')->getClientOriginalName();
        $exists = Storage::disk('local')->exists($documentLocation . $documentFilename);
        if ($exists){
            return "Error";
        }

        $documentData = [
            'user_id'   => $id,
            'file_name' => $documentFilename,
            'file_location' => $documentLocation,
            'file_type' => $request->file('user_doc')->getClientMimeType(),
            'category' => 'account_documents',
            'comments'=> ''
        ];

        $document = new UserDocuments();
        $document->fill($documentData);
        $document->save();

        Storage::disk('local')->put(
            $documentLocation . $documentFilename,
            file_get_contents($request->file('user_doc')->getRealPath())
        );

        return "Bine";
        //return redirect('admin/back_users/view_user/'.$id);
    }

    public function get_user_account_document($id, $document_name){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $back_user = User::findOrFail($id);
        $entry = UserDocuments::where('user_id',$back_user->id)->where('file_name', $document_name)->where('category', 'account_documents')->firstOrFail();

        $file_path = 'employees/'.$id.'/documents/'. $document_name;
        $exists = Storage::disk('local')->exists($file_path);
        if ($exists) {
            $file = Storage::disk('local')->get($file_path);
            return (new Response($file, 200))
                ->header('Content-Type', $entry->file_type)
                ->header('Content-Disposition', 'attachment; filename="'.$document_name.'"');
        }
        else
        {
            // Error
            exit('Requested file does not exist on our server!');
        }
    }

    public function ajax_get_users(Request $request){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $vars = $request->only('q');
        $items_array = array();
        $items = array();

        $searchTerm = trim($vars['q']);
        $searchTerm = preg_replace('!\s+!', ' ', $searchTerm);

        $query = DB::table('users')
            ->select('users.first_name','users.middle_name','users.last_name','users.id','users.email','personal_details.mobile_number')
            ->leftjoin('personal_details','personal_details.user_id','=','users.id')
            ->leftjoin('role_user', 'users.id', '=', 'role_user.user_id')
            ->whereIn('role_user.role_id',['5','6'])
            ->where(function($query) use ($searchTerm){
                $query->where('users.first_name','like',$searchTerm.'%')
                    ->orWhere('users.middle_name','like',$searchTerm.'%')
                    ->orWhere('users.last_name','like',$searchTerm.'%')
                    ->orWhere(DB::raw("CONCAT(users.first_name, ' ', users.last_name)"),'like',$searchTerm.'%')
                    ->orWhere(DB::raw("CONCAT(users.first_name, ' ', users.middle_name, ' ', users.last_name)"),'like',$searchTerm.'%')
                    ->orWhere('users.email','like',$searchTerm.'%')
                    ->orWhere('personal_details.mobile_number','like',$searchTerm.'%');
            })
            ->groupBy('users.id')
        ->take(15);

        $results = $query->get();
        if ($results){
            foreach($results as $result){
                $user_temp = User::where('id','=',$result->id)->get()->first();
                $user_link = route('admin/front_users/view_user',['id'=>$user_temp->id]);
                $avatar = $user_temp->get_avatar_image();

                $userMembership = UserMembership::where('user_id','=',$result->id)->where('status','=','active')->get()->first();
                if ($userMembership){
                    $activeMembership = $userMembership->membership_name;
                }
                else{
                    $activeMembership = 'No Active Membership';
                }

                $items[] = array('id'=>$result->id,
                    'first_name'    => $result->first_name,
                    'middle_name'   => $result->middle_name,
                    'last_name'     => $result->last_name,
                    'email'         => $result->email,
                    'phone'         => $result->mobile_number,
                    'city'          => '',
                    'region'        => '',
                    'membership'    => $activeMembership,
                    'user_profile_img'      => asset('assets/pages/img/avatars/team'.rand(1,10).'.jpg'),
                    'avatar_image'          => $avatar['avatar_base64'],
                    'user_link_details'     => $user_link
                );
            }
        }

        $items_array['items'] = $items;
        return $items_array;
    }

    public function ajax_get_users_optimized(Request $request){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $vars = $request->only('q');
        $items_array = array();
        $items = array();

        $searchTerm = trim($vars['q']);
        $searchTerm = preg_replace('!\s+!', ' ', $searchTerm);

        $query = OptimizeSearchMembers::
                where('first_name','like',$searchTerm.'%')
                ->orWhere('middle_name','like',$searchTerm.'%')
                ->orWhere('last_name','like',$searchTerm.'%')
                ->orWhere('first_last_name','like',$searchTerm.'%')
                ->orWhere('first_middle_last_name','like',$searchTerm.'%')
                ->orWhere('email','like',$searchTerm.'%')
                ->orWhere('phone','like',$searchTerm.'%')
            ->take(10);

        //$query = DB::select("SELECT * FROM optimize_search_members WHERE MATCH (first_name) AGAINST ('".$searchTerm."' IN NATURAL LANGUAGE MODE) LIMIT 10");
        $results = $query->get();
        //xdebug_var_dump($query); exit;
        if ($results){
            foreach($results as $result){
                $user_temp = User::where('id','=',$result->user_id)->get()->first();
                $avatar = $user_temp->get_avatar_image();

                $items[] = array(
                    'id'            => $result->user_id,
                    'first_name'    => $result->first_name,
                    'middle_name'   => $result->middle_name,
                    'last_name'     => $result->last_name,
                    'email'         => $result->email,
                    'phone'         => $result->phone,
                    'city'          => $result->city,
                    'region'        => $result->region,
                    'membership'    => $result->membership_name,
                    'user_profile_img'      => $result->user_profile_image,
                    'avatar_image'          => $avatar['avatar_base64'],
                    'user_link_details'     => $result->user_link_details
                );
            }
        }

        $items_array['items'] = $items;
        return $items_array;
    }

    public function ajax_get_user_info(Request $request, $id=-1)
    {
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        if (isset($request->id)){
            $id = $request->id;
        }

        $user_info = [
            'full_name' => ' - ',
            'email' => ' - ',
            'city' => ' - ',
            'state' => ' - ',
            'phone_number' => ' - ',
        ];

        if ($id == -1) {
            $user_info = [
                'full_name' => ' Jhon Doe ',
                'email' => ' jhon@doe.com ',
                'city' => ' New York ',
                'state' => ' WA ',
                'phone_number' => ' 12234389 ',
            ];
        }
        else {
            $query = DB::table('users')
                ->select('users.first_name','users.middle_name','users.last_name','users.id','users.email','personal_details.mobile_number','addresses.city','addresses.region','addresses.id as billAddress')
                ->leftjoin('personal_details','personal_details.user_id','=','users.id')
                ->leftjoin('addresses','addresses.id','=','personal_details.address_id')
                ->where('users.id','=',$id)
                ->limit(1);

            $results = $query->get();
            if ($results){
                $result = $results[0];
                $user_info = [
                    'full_name' => $result->first_name.' '.$result->middle_name.' '.$result->last_name,
                    'email' => $result->email,
                    'city' => $result->city?$result->city:'-',
                    'state' => $result->region?$result->region:'-',
                    'phone_number' => $result->mobile_number?$result->mobile_number:'-',
                    'bill_address' => $result->billAddress,
                    'ship_address' => $result->billAddress,
                ];
            }
        }

        return $user_info;
    }

    public function ajax_get_bill_address(Request $request){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $vars = $request->only('addressID','memberID');
        $user_address = ['full_address' => '<br /> -<br /> -<br /> -<br /> -<br /> -<br /> -<br />'];

        if ($vars['addressID']==-1){
            $user_address = [
                'full_address' => ' Jhon Done <br> #24 Park Avenue Str <br> New York <br> Connecticut, 23456 New York <br> United States <br> T: 123123232 <br> F: 231231232 <br> ',
            ];
        }
        else{
            $query = DB::table('users')
                ->select('first_name','last_name','middle_name','address1','address2','city','postal_code','region','countries.name','personal_details.mobile_number')
                ->join('addresses','addresses.user_id','=','users.id')
                ->where('users.id','=',$vars['memberID'])
                ->where('addresses.id','=',$vars['addressID'])
                ->limit(1);
            $results = $query->get();
            if ($results){
                $result = $results[0];
                $user_address['full_address'] = $result->first_name." ".$result->middle_name." ".$result->last_name."
                <br /> ".$result->address1." ".$result->address2."
                <br /> ".$result->city.", ".$result->postal_code." ".$result->region."
                <br /> ".$result->country_name."
                <br /> ".($result->mobile_number?$result->mobile_number:'-')."
                <br />
                <br /> ";
            }
        }

        return $user_address;
    }

    public function ajax_get_ship_address(Request $request, $id=-1){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $user_address = [];
        if ($id==-1){
            $user_address = [
                'full_address' => ' Jhon Done <br> #24 Park Avenue Str <br> New York <br> Connecticut, 23456 New York <br> United States <br> T: 123123232 <br> F: 231231232 <br> ',
            ];
        }
        else{

        }

        return $user_address;
    }

}
