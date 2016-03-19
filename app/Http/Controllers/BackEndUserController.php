<?php

namespace App\Http\Controllers;

use App\PersonalDetail;
use App\ProfessionalDetail;
use App\Address;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\User;
use Validator;
use Auth;
use Hash;
use Mockery\CountValidator\Exception;
use \App\Role;
use Webpatser\Countries\Countries;
use Carbon\Carbon;

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

        $text_parts  = [
            'title'     => 'Back-End Users',
            'subtitle'  => 'view all users',
            'table_head_text1' => 'Backend User List'
        ];
        $sidebar_link= 'admin-backend-user_details_view';

        @$userRole = $back_user->roles[0];
        if (!$userRole){
            $defaultRole = Role::where('name','employee')->get();
            $userRole = $defaultRole[0];
        }

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
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }
        $vars = $request->only('about_info', 'country_id', 'date_of_birth', 'first_name', 'last_name', 'middle_name', 'mobile_number', 'personal_email', 'bank_acc_no', 'social_sec_no');

        $userVars = array(  'first_name'    => $vars["first_name"],
                            'last_name'     => $vars["last_name"],
                            'middle_name'   => $vars["middle_name"],
                            'country_id'    => $vars["country_id"],
                            'date_of_birth' => $vars["date_of_birth"]);
        $userCh = User::find($id);

        $validator = Validator::make($userVars, [
            'first_name'    => 'required|min:4|max:150',
            'last_name'     => 'required|min:4|max:150',
            'date_of_birth' => 'required|date',
            'country_id'    => 'required|exists:countries,id',
        ]);

        if ($validator->fails()){
            //return array(
            //    'success' => false,
            //    'errors' => $validator->getMessageBag()->toArray()
            //);
        }
        else{
            $userCh->first_name  = $vars["first_name"];
            $userCh->last_name   = $vars["last_name"];
            $userCh->middle_name = $vars["middle_name"];
            $userCh->country_id  = $vars["country_id"];
            $userCh->save();
        }

        $personalData = array(  'personal_email'=> $vars['personal_email'],
                                'mobile_number' => $vars['mobile_number'],
                                'date_of_birth' => $vars['date_of_birth'],
                                'bank_acc_no'   => $vars['bank_acc_no'],
                                'social_sec_no' => $vars['social_sec_no'],
                                'about_info'    => $vars['about_info'],
                                'user_id'       => $id);
        $personalDetails = PersonalDetail::firstOrNew(array('user_id'=>$id));
        //$personalDetails->personal_email = $personalData['personal_email'];
        //$personalDetails->mobile_number  = $personalData['mobile_number'];
        //$personalDetails->date_of_birth  = Carbon::createFromFormat('d-m-Y', $personalData['date_of_birth'])->toDateString();
        //$personalDetails->bank_acc_no    = $personalData['bank_acc_no'];
        //$personalDetails->social_sec_no  = $personalData['social_sec_no'];
        //$personalDetails->about_info     = $personalData['about_info'];
        //$personalDetails->user_id        = $personalData['user_id'];
        $personalData['date_of_birth'] = Carbon::createFromFormat('d-m-Y', $personalData['date_of_birth'])->toDateString();
        $personalDetails->fill($personalData);
        $personalDetails->save();

        return "bine";
    }

    public function update_personal_address(Request $request, $id)
    {
        if (!Auth::check()) {
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
        $user = User::findOrFail($id);
        $userVars = $request->only('old_password','password1','password2');

        // Validate the new password length...
        $validator = Validator::make($userVars, [
            'old_password'  => 'required|min:8',
            'password1'     => 'required|min:8',
            'password2'     => 'required|min:8|same:password1',
        ]);

        if ($validator->fails()){
            //return array(
            //    'success' => false,
            //    'errors' => $validator->getMessageBag()->toArray()
            //);
        }
        else {
            $auth = auth();
            if ($auth->attempt([ 'id' => $id, 'password' => $userVars['old_password'] ])) {
                $user->fill([
                    'password' => Hash::make($request->password1)
                ])->save();
            }
            else{
                return 'Old password mismatch';
            }
        }

        return 'bine';
    }
}
