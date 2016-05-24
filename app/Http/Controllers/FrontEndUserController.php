<?php

namespace App\Http\Controllers;

use App\Booking;
use App\UserFriends;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Role;
use App\Permission;
use App\PersonalDetail;
use App\ProfessionalDetail;
use App\Address;
use App\UserAvatars;
use App\UserDocuments;
use App\ShopLocations;
use App\ShopResource;
use App\ShopResourceCategory;
use Webpatser\Countries\Countries;
use Auth;
use Storage;
use Carbon\Carbon;
use Validator;
use DB;

class FrontEndUserController extends Controller
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
        else{
            $user = Auth::user();
        }

        $back_users = User::whereHas('roles', function($query){
            $query->where('name', 'front-user');
        })->orWhereHas('roles', function($query){
            $query->where('name', 'front-member');
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
        $sidebar_link= 'admin-frontend-all_members';

        $all_roles = Role::orderBy('name')->get();
        //xdebug_var_dump($all_roles);

        return view('admin/front_users/all_members_list', [
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
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }
        else{
            $user = Auth::user();
        }

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
        else{
            $user = Auth::user();
        }
        $back_user = User::with('roles')->find($id);

        $text_parts  = [
            'title'     => 'Back-End Users',
            'subtitle'  => 'view all users',
            'table_head_text1' => 'Backend User List'
        ];

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

        $avatar = $back_user->avatar;
        if (!$avatar) {
            $avatar = new UserAvatars();
            $avatar->file_location = 'employees/default/avatars/';
            $avatar->file_name = 'default.jpg';
        }

        $avatarContent = Storage::disk('local')->get($avatar->file_location . $avatar->file_name);
        $avatarType = Storage::disk('local')->mimeType($avatar->file_location . $avatar->file_name);

        $userDocuments = UserDocuments::where('user_id','=',$id)->where('category','=','account_documents')->get();

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End Users'    => route('admin/back_users'),
            $back_user->first_name.' '.$back_user->middle_name.' '.$back_user->last_name => '',
        ];
        $sidebar_link= 'admin-frontend-user_details_view';

        return view('admin/front_users/view_member_details', [
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
            'avatar'      => $avatarContent,
            'avatarType'  => $avatarType,
            'permissions' => $permissions,
            'documents'   => $userDocuments,
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_account_settings($id)
    {
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }
        else{
            $user = Auth::user();
        }
        $back_user = User::with('roles')->find($id);

        $text_parts  = [
            'title'     => 'Back-End Users',
            'subtitle'  => 'view all users',
            'table_head_text1' => 'Backend User List'
        ];

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

        $avatar = $back_user->avatar;
        if (!$avatar) {
            $avatar = new UserAvatars();
            $avatar->file_location = 'employees/default/avatars/';
            $avatar->file_name = 'default.jpg';
        }

        $avatarContent = Storage::disk('local')->get($avatar->file_location . $avatar->file_name);
        $avatarType = Storage::disk('local')->mimeType($avatar->file_location . $avatar->file_name);

        $userDocuments = UserDocuments::where('user_id','=',$id)->where('category','=','account_documents')->get();

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End Users'    => route('admin/back_users'),
            $back_user->first_name.' '.$back_user->middle_name.' '.$back_user->last_name => '',
        ];
        $sidebar_link= 'admin-frontend-user_details_view';

        return view('admin/front_users/view_member_settings', [
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
            'avatar'      => $avatarContent,
            'avatarType'  => $avatarType,
            'permissions' => $permissions,
            'documents'   => $userDocuments,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_bookings($id){
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }
        else{
            $user = Auth::user();
        }
        $back_user = User::with('roles')->find($id);

        $text_parts  = [
            'title'     => 'Back-End Users',
            'subtitle'  => 'view all users',
            'table_head_text1' => 'Backend User List'
        ];

        $bookingsList = [];
        $bookings = Booking::where('for_user_id','=',$id)
                    ->orWhere('by_user_id','=',$id)
                    ->orderBy('date_of_booking','desc')
                    ->orderBy('booking_time_start','desc')
                    ->get();
        if ($bookings){
            $buttons = [];
            $colorStatus = '';
            foreach ($bookings as $booking){
                switch ($booking->status) {
                    case 'pending' :
                        $colorStatus = 'info';
                        $buttons = 'yellow-gold';
                        break;
                    case 'expired' :
                        $colorStatus = 'warning';
                        $buttons = 'grey-salsa';
                        break;
                    case'active' :
                        $colorStatus = 'success';
                        $buttons = 'green-jungle';
                        break;
                    case 'paid' :
                        $colorStatus = 'success';
                        $buttons = 'green-jungle';
                        break;
                    case 'unpaid' :
                        $colorStatus = 'danger';
                        $buttons = 'yellow-gold';
                        break;
                    case 'noshow' :
                        $colorStatus = 'danger';
                        $buttons = 'red-thunderbird';
                        break;
                    case 'old' :
                        $colorStatus = 'success';
                        $buttons = 'green-meadow';
                        break;
                    case 'canceled' :
                        $colorStatus = 'success';
                        $buttons = 'yellow-lemon';
                        break;
                }

                $date       = Carbon::createFromFormat('Y-m-d', $booking->date_of_booking)->format('l, M jS, Y');
                $addedOn    = Carbon::createFromFormat('Y-m-d H:i:s', $booking->updated_at)->format('j/m/Y H:i');
                $dateSmall  = Carbon::createFromFormat('Y-m-d', $booking->date_of_booking)->format('j/m/Y');
                $timeInterval = Carbon::createFromFormat('H:i:s', $booking->booking_time_start)->format('H:i').' - '.Carbon::createFromFormat('H:i:s', $booking->booking_time_stop)->format('H:i');

                $madeForID = $booking->for_user_id;
                $userFor= User::find($booking->for_user_id);
                if ($userFor) {
                    $madeFor = $userFor->first_name . ' ' . $userFor->middle_name . ' ' . $userFor->last_name;
                }
                else{
                    $madeFor = ' - ';
                }

                $location = ShopLocations::find($booking->location_id);
                $locationName = $location->name;
                $room = ShopResource::find($booking->resource_id);
                $roomName = $room->name;
                $category = ShopResourceCategory::find($room->category_id);
                $categoryName = $category->name;

                $bookingsList[] = [
                    'dateShort'     => $dateSmall,
                    'date'          => $date,
                    'timeInterval'  => $timeInterval,
                    'player_name'   => $madeFor,
                    'player_id'     => $madeForID,
                    'location'      => $locationName,
                    'room'          => $roomName,
                    'activity'      => $categoryName,
                    'status'        => $booking->status,
                    'color_status'  => $colorStatus,
                    'color_button'  => $buttons,
                    'last_update'   => $booking->updated_at,
                    'added_by'      => $booking->by_user_id,
                    'search_key'    => $booking->search_key,
                    'added_on'      => $addedOn
                ];
            }

            $nr = 1;
            $index = [];
            $lastMan = 0;
            $lastTime = '';
            $lastTenBookings = [];
            foreach($bookingsList as $lastOne){
                if (!isset($lastMan)){ $lastMan = $lastOne['added_by']; }
                if (!isset($lastTime)){ $lastTime = $lastOne['last_update']; }

                if ($lastMan==$lastOne['added_by'] && $lastTime==$lastOne['last_update']){
                    $nr++;
                    $lastOne['colspan'] = 0;
                }
                else{
                    $lastMan  = $lastOne['added_by'];
                    $lastTime = $lastOne['last_update'];

                    $indexKey = sizeof($index)+1;
                    $index[$indexKey-1] = $nr;

                    if ($indexKey>10){
                        break;
                    }
                    $nr=1;
                }

                $lastTenBookings[] = $lastOne;
            }
            $index[$indexKey] = $nr;
        }

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End Users'    => route('admin/back_users'),
            $back_user->first_name.' '.$back_user->middle_name.' '.$back_user->last_name => '',
        ];
        $sidebar_link= 'admin-frontend-user_details_view';

        return view('admin/front_users/view_member_bookings', [
            'user'      => $back_user,
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'bookings'    => $bookingsList,
            'multipleBookingsIndex' => $index,
            'lastTen' =>  $lastTenBookings
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_finance($id)
    {
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }
        else{
            $user = Auth::user();
        }
        $back_user = User::with('roles')->find($id);

        $text_parts  = [
            'title'     => 'Back-End Users',
            'subtitle'  => 'view all users',
            'table_head_text1' => 'Backend User List'
        ];

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

        $avatar = $back_user->avatar;
        if (!$avatar) {
            $avatar = new UserAvatars();
            $avatar->file_location = 'employees/default/avatars/';
            $avatar->file_name = 'default.jpg';
        }

        $avatarContent = Storage::disk('local')->get($avatar->file_location . $avatar->file_name);
        $avatarType = Storage::disk('local')->mimeType($avatar->file_location . $avatar->file_name);

        $userDocuments = UserDocuments::where('user_id','=',$id)->where('category','=','account_documents')->get();

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End Users'    => route('admin/back_users'),
            $back_user->first_name.' '.$back_user->middle_name.' '.$back_user->last_name => '',
        ];
        $sidebar_link= 'admin-frontend-user_details_view';

        return view('admin/front_users/view_member_finance', [
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
            'avatar'      => $avatarContent,
            'avatarType'  => $avatarType,
            'permissions' => $permissions,
            'documents'   => $userDocuments,
        ]);
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
        else{
            $user = Auth::user();
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
        else{
            $user = Auth::user();
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
        else{
            $user = Auth::user();
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
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }
        else{
            $user = Auth::user();
        }

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

    public function update_personal_avatar(Request $request, $id){
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }
        else{
            $user = Auth::user();
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
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }
        else{
            $user = Auth::user();
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
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }
        else{
            $user = Auth::user();
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
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }
        else{
            $user = Auth::user();
        }

        $vars = $request->only('q');
        $items_array = array();
        $items = array();

        $query = DB::table('users')
            ->select('users.first_name','users.middle_name','users.last_name','users.id','users.email','personal_details.mobile_number','addresses.city','addresses.region')
            ->leftjoin('personal_details','personal_details.user_id','=','users.id')
            ->leftjoin('addresses','addresses.id','=','personal_details.address_id')
            ->where(    'users.first_name','like','%'.$vars['q'].'%')
            ->orWhere(  'users.middle_name','like','%'.$vars['q'].'%')
            ->orWhere(  'users.last_name','like','%'.$vars['q'].'%')
            ->orWhere(  'users.email','like','%'.$vars['q'].'%')
            ->orWhere(  'personal_details.personal_email','like','%'.$vars['q'].'%');

        $results = $query->get();
        if ($results){
            foreach($results as $result){
                $items[] = array('id'=>$result->id,
                    'first_name' => $result->first_name,
                    'middle_name' => $result->middle_name,
                    'last_name' => $result->last_name,
                    'email' => $result->email,
                    'phone'=>$result->mobile_number,
                    'city'=>$result->city,
                    'region'=>$result->region,
                    'product_image_url' => asset('assets/pages/img/avatars/team'.rand(1,10).'.jpg')
                );
            }
        }

        $items_array['items'] = $items;

        return $items_array;


        $user_info = [];
    }

    public function ajax_get_user_info(Request $request, $id=-1)
    {
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }
        else{
            $user = Auth::user();
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
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }
        else{
            $user = Auth::user();
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
        if (!Auth::check()) {
            return redirect()->intended(route('admin/login'));
        }
        else{
            $user = Auth::user();
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

    public function ajax_get_friends_list($id=-1){
        if (!Auth::check()) {
            return [];
        }
        else{
            $user = Auth::user();
            $user_id = $user->id;
        }

        $all_friends = [];
        $friends = UserFriends::where('user_id','=',$user_id)->orWhere('friend_id','=',$user_id)->get();
        foreach($friends as $friend){
            $friend_id = $friend->user_id==$user_id?$friend->friend_id:$friend->user_id;
            $user_details = User::find($friend_id);

            if (!$user_details){ continue; }
            $all_friends[] = ['name' => $user_details->first_name.' '.$user_details->middle_name.' '.$user_details->last_name, 'id'=>$user_details->id];
        }

        return $all_friends;
    }

    public function ajax_get_available_players_list(Request $request){
        if (!Auth::check()) {
            return [];
        }
        else{
            $user = Auth::user();
            $user_id = $user->id;
        }

        $vars = $request->only('userID');
        if (isset($vars['userID'])){
            if ($user_id!=$vars['userID']){
                $user = User::find($vars['userID']);
                if ($user) {
                    $user_id = $user->id;
                }
                else{
                    return [];
                }
            }
        }

        $all_friends[] = ['name' => $user->first_name.' '.$user->middle_name.' '.$user->last_name, 'id' => $user->id];
        $friends = UserFriends::where('user_id','=',$user_id)->orWhere('friend_id','=',$user_id)->get();
        foreach($friends as $friend){
            $friend_id = $friend->user_id==$user_id?$friend->friend_id:$friend->user_id;
            $user_details = User::find($friend_id);

            if (!$user_details){ continue; }
            $bookings = BookingController::get_user_bookings($friend_id,['pending','active']);
            if (sizeof($bookings)>0){ continue; }

            $all_friends[] = ['name' => $user_details->first_name.' '.$user_details->middle_name.' '.$user_details->last_name, 'id'=>$user_details->id];
        }

        return $all_friends;
    }

    public function add_friend_by_phone(Request $request, $id=-1){
        if (!Auth::check()) {
            $msg = ['success'=>'false', 'error'=> ['title'=>'An error occured', 'message'=>'Please check the number and add it again. You have a limited number of attempts']];
            return $msg;
        }
        else{
            $user = Auth::user();
        }

        $vars = $request->only('phone_no');

        if ($id==-1){
            $user_id = $user->id;
        }
        else{
            $user_id = $id;
        }

        $friends = PersonalDetail::where('mobile_number','=',$vars['phone_no'])->get()->first();
        if (sizeof($friends)==0){
            $msg = ['success'=>'false', 'error'=> ['title'=>'An error occurred', 'message'=>'Please check the number and add it again. You have a limited number of attempts']];
        }
        else {
            // one friend found
            //xdebug_var_dump($friends); exit;
            $friend_fill = ['user_id'=>$user_id, 'friend_id'=>$friends->user_id];
            $validator = Validator::make($friend_fill, UserFriends::rules('POST'), UserFriends::$message, UserFriends::$attributeNames);

            if ($validator->fails()){
                $msg = array(
                    'success' => false,
                    'error' => [
                        'validator' => $validator->getMessageBag()->toArray(),
                        'title' => 'An error occurred',
                        'message'=>'Please check the number and add it again. You have a limited number of attempts'
                    ]
                );
            }
            else {
                $new_friend = UserFriends::firstOrCreate($friend_fill);
                $new_friend->save();

                $msg = [];
            }
        }

        return $msg;
    }

    public static function are_friends($user1, $user2){
        $friends = DB::select('select id from user_friends where (user_id=? and friend_id=?) or (user_id=? and friend_id=?)', [$user1, $user2, $user2, $user1]);
        if ($friends){
            return true;
        }
        else{
            return false;
        }
    }
}
