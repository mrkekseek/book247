<?php

namespace App\Http\Controllers;

use App\Booking;
use App\BookingInvoice;
use App\BookingInvoiceItem;
use App\BookingNote;
use App\GeneralNote;
use App\Invoice;
use App\InvoiceItem;
use App\UserAccessCard;
use App\UserFriends;
use App\UserMembership;
use App\UserMembershipInvoicePlanning;
use App\UserSettings;
use Illuminate\Auth\Passwords\PasswordBroker;
use Illuminate\Http\Request;

use Illuminate\Http\Response;
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
use App\MembershipPlan;
use App\MembershipRestriction;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Facades\Excel;
use Mockery\CountValidator\Exception;
use PhpParser\Node\Expr\Cast\Bool_;
use Regulus\ActivityLog\Models\Activity;
use Webpatser\Countries\Countries;
use Auth;
use Hash;
use Storage;
use Carbon\Carbon;
use Validator;
use DB;
use Illuminate\Support\Str;
use Snowfire\Beautymail\Beautymail;
use Illuminate\Auth\Passwords\TokenRepositoryInterface;

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
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }
        elseif (!$user->can('view-clients-list-all-clients')) {
            return redirect()->intended(route('admin/error/permission_denied'));
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
            'title'     => 'Front-End Users/Members',
            'subtitle'  => 'list all',
            'table_head_text1' => 'Backend User List'
        ];
        $sidebar_link= 'admin-frontend-all_members';

        $role = Role::where('name','=','front-user')->get()->first();
        //xdebug_var_dump($all_roles);

        return view('admin/front_users/all_members_list', [
            'users' => $back_users,
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'role'   => $role,
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
            return redirect()->intended(route('admin/login'));
        }
        elseif (!$user->can('manage-clients')){
            return redirect()->intended(route('admin/error/permission_denied'));
        }

        $back_users = User::whereHas('roles', function($query){
            $query->where('name', 'front-user');
        })->orWhereHas('roles', function($query){
            $query->where('name', 'front-member');
        })->get();

        $countries = Cache::remember('countries', 3660, function() {
            return Countries::orderBy('name')->get();
        });

        $role = Role::where('name','=','front-user')->get()->first();
        $memberships = MembershipPlan::where('status','=','active')->where('id','!=','1')->get();

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End User'     => route('admin'),
            'All Backend Users' => '',
        ];
        $text_parts  = [
            'title'     => 'Company Clients',
            'subtitle'  => 'add new',
            'table_head_text1' => 'Backend User List'
        ];
        $sidebar_link= 'admin-frontend-add_member';

        return view('admin/front_users/add_new_member', [
            'users'         => $back_users,
            'breadcrumbs'   => $breadcrumbs,
            'text_parts'    => $text_parts,
            'in_sidebar'    => $sidebar_link,
            'role'          => $role,
            'memberships'   => $memberships,
            'countries'     => $countries
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return [
                'success' => false,
                'errors'  => 'Error while trying to authenticate. Login first then use this function.',
                'title'   => 'Not logged in'];
        }
        elseif (!$user->can('manage-clients')){
            return [
                'success'   => false,
                'errors'    => 'You don\'t have permission to access this page',
                'title'     => 'Permission Error'];
            /*return redirect()->intended(route('admin/error/permission_denied'));*/
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

        $member = User::with('personalDetail')->where('id','=',$id)->get()->first();
        if (!$member || !$member->is_front_user()){
            return redirect(route('admin/error/not_found'));
        }

        $publicNote = $member->get_public_notes("DESC");
        $privateNote = $member->get_private_notes("DESC");

        $activityLogs = Activity::where('content_id','=',$member->id)->orderBy('created_at','DESC')->take(30)->get();
        $memberLogs = [];
        if ($activityLogs){
            foreach($activityLogs as $singleLog){
                $memberLogs[] = [
                    'content_type'  => $singleLog->content_type,
                    'action'        => $singleLog->action,
                    'description'   => $singleLog->description,
                    'addedOn'       => Carbon::createFromFormat('Y-m-d H:i:s', $singleLog->created_at)->diffForHumans(null, true),
                    'logDate'       => Carbon::createFromFormat('Y-m-d H:i:s', $singleLog->created_at)->format('d-m-Y H:i'),
                    'ip_address'    => $singleLog->ip_address
                ];
            }
        }

        $avatar = $member->get_avatar_image();

        $old_bookings = Booking::select('id')->where('for_user_id','=',$member->id)->whereIn('status',['paid','unpaid','old','no_show'])->count();
        $new_bookings = Booking::select('id')->where('for_user_id','=',$member->id)->whereIn('status',['active'])->count();
        $cancelled_bookings = Booking::select('id')->where('for_user_id','=',$member->id)->whereIn('status',['canceled'])->count();
        $own_friends  = UserFriends::select('id')->where('user_id','=',$member->id)->orWhere('friend_id','=',$member->id)->count();

        $front_statistics = $this->get_member_bookings_statistics($member);

        $text_parts  = [
            'title'     => 'Back-End Users',
            'subtitle'  => 'view all users',
            'table_head_text1' => 'Backend User List'
        ];
        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End Users'    => route('admin/back_users'),
            $member->first_name.' '.$member->middle_name.' '.$member->last_name => '',
        ];
        $sidebar_link= 'admin-frontend-user_details_view';

        return view('admin/front_users/view_member_details', [
            'user'          => $member,
            'breadcrumbs'   => $breadcrumbs,
            'text_parts'    => $text_parts,
            'in_sidebar'    => $sidebar_link,
            'avatar'        => $avatar['avatar_base64'],
            'publicNote'    => $publicNote,
            'privateNote'   => $privateNote,
            'activityLog'   => $memberLogs,
            'redFlagLog'    => [],
            'countOldBookings'    => $old_bookings,
            'countCancelledBookings'    => $cancelled_bookings,
            'countActiveBookings' => $new_bookings,
            'countFriends'        => $own_friends,
            'top_stats'     => $front_statistics
        ]);
    }

    function desc_cmp($a, $b){
        if ($a['timestamp'] == $b['timestamp']) {
            return 0;
        }
        return ($a['timestamp'] > $b['timestamp']) ? -1 : 1;
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
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $member = User::with('roles')->find($id);
        if (!$member || !$member->is_front_user()){
            return redirect(route('admin/error/not_found'));
        }

        $text_parts  = [
            'title'     => 'Back-End Users',
            'subtitle'  => 'view all users',
            'table_head_text1' => 'Backend User List'
        ];

        $userPersonal = $member->PersonalDetail;
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

        $avatar = $member->get_avatar_image();
        $userDocuments = UserDocuments::where('user_id','=',$id)->where('category','=','account_documents')->get();

        $plan_request = [];
        $invoiceCancellation = [];
        $invoiceFreeze = [];
        $canCancel = true;
        $canFreeze = true;
        $canUpdate = true;

        $my_plan = UserMembership::where('user_id','=',$member->id)->whereIn('status',['active','suspended'])->get()->first();
        if ($my_plan){
            $restrictions = $my_plan->get_plan_restrictions();
            $plan_details = $my_plan->get_plan_details();

            $currentInvoiceDate = UserMembershipInvoicePlanning::where('status','=','old')->where('user_membership_id','=',$my_plan->id)->orderBy('issued_date','desc')->get()->first();
            if ($currentInvoiceDate) {
                $invoice_date1 = Carbon::createFromFormat('Y-m-d', $currentInvoiceDate->issued_date);
                $invoice_date2 = Carbon::createFromFormat('Y-m-d', $currentInvoiceDate->last_active_date);

                $plan_details['invoicePeriod'] = $invoice_date1->format('j M Y') . ' - ' . $invoice_date2->format('j M Y');
            }
            else{
                $plan_details['invoicePeriod'] = '-';
            }

            $nextInvoiceDate = UserMembershipInvoicePlanning::where('status','=','pending')->where('user_membership_id','=',$my_plan->id)->orderBy('issued_date','asc')->get()->first();
            if ($nextInvoiceDate) {
                $invoice_date1 = Carbon::createFromFormat('Y-m-d', $nextInvoiceDate->issued_date);
                $invoice_date2 = Carbon::createFromFormat('Y-m-d', $nextInvoiceDate->last_active_date);

                $plan_details['nextInvoicePeriod'] = $invoice_date1->format('j M Y') . ' - ' . $invoice_date2->format('j M Y');
            }
            else{
                $plan_details['nextInvoicePeriod'] = '-';
            }

            $plan_request = $my_plan->get_plan_requests(); //xdebug_var_dump($plan_request);
            foreach($plan_request as $a_request){
                if ($a_request['action_type'] == 'cancel' && $a_request['status']!='cancelled'){
                    $canCancel = false;
                }
                elseif ($a_request['action_type'] == 'freeze' && $a_request['status']=='active'){
                    $canFreeze = false;
                    $canUpdate = false;
                }
                elseif ($a_request['action_type'] == 'update' && $a_request['status']=='active'){
                    $canUpdate = false;
                    $canFreeze = false;
                }
            }

            $allPlannedInvoices = UserMembershipInvoicePlanning::where('user_membership_id','=',$my_plan->id)->orderBy('issued_date','asc')->get();
            $plannedInvoices = [];
            foreach($allPlannedInvoices as $onlyOne){
                $varInv = [
                    'invoiceLink'   => '',
                    'invoiceStatus' => '',
                    'item_name' => $onlyOne->item_name,
                    'price'     => $onlyOne->price,
                    'status'    => $onlyOne->status,
                    'issued_date'   => $onlyOne->issued_date,
                    'last_active_date'   => $onlyOne->last_active_date
                ];

                if ($onlyOne->invoice_id!=-1){
                    $getInvoice = Invoice::where('id','=',$onlyOne->invoice_id)->get()->first();
                    $varInv['status']= 'issued';
                    $varInv['invoiceStatus']= $getInvoice->status;
                    $varInv['invoiceLink']  = route('admin/invoices/view', ['id' => $getInvoice->invoice_number]);
                }

                $plannedInvoices[$onlyOne->id] = $varInv;

                foreach ($plan_request as $key=>$a_request){
                    if (!isset($a_request['after_date']) && $a_request['start_date']->lte(Carbon::createFromFormat('Y-m-d H:i:s', $onlyOne->issued_date.' 00:00:00'))){
                        $plan_request[$key]['after_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $onlyOne->issued_date.' 00:00:00');
                    }
                }
            }

            $signOutDate = Carbon::today()->addMonths($my_plan->sign_out_period);
            //xdebug_var_dump($allPlannedInvoices[0]); exit;
            foreach($allPlannedInvoices as $onlyOne){
                if ($onlyOne->status == 'pending'){
                    $invoiceFreeze[$onlyOne->id] = Carbon::createFromFormat('Y-m-d',$onlyOne->issued_date)->format('jS \o\f F, Y');
                }

                //xdebug_var_dump(Carbon::createFromFormat('Y-m-d',$onlyOne->issued_date));
                if ($signOutDate->lte(Carbon::createFromFormat('Y-m-d',$onlyOne->issued_date))){
                    $invoiceCancellation[$onlyOne->id] = Carbon::createFromFormat('Y-m-d',$onlyOne->last_active_date)->addDay()->format('jS \o\f F, Y');
                }
            }
            //xdebug_var_dump($invoiceCancellation);
            //exit;
        }
        else {
            $my_plan = MembershipPlan::where('id','=',1)->get()->first();
        }
        $membership_plans = MembershipPlan::with('price')->where('id','!=','1')->where('status','=','active')->get()->sortBy('name');
        $membership_plans_update = [];
        foreach ($membership_plans as $single){
            if ($single->plan_period!=$my_plan->invoice_period){
                continue;
            }

            if (!isset($my_plan->short_description) && $single->get_price()->price>=$my_plan->price){
                $status = 'UPGRADE';
            }
            else{
                $status = 'DOWNGRADE';
            }

            $membership_plans_update[] = [
                'id'         => $single->id,
                'name'       => $single->name,
                'up_or_down' => $status,
            ];
        }

        $cardNo = UserAccessCard::where('user_id','=',$member->id)->where('status','=','active')->get()->first();
        if ($cardNo){
            $accessCardNo = $cardNo->key_no;
        }

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End Users'    => route('admin/back_users'),
            $member->first_name.' '.$member->middle_name.' '.$member->last_name => '',
        ];
        $sidebar_link= 'admin-frontend-user_details_view';

        return view('admin/front_users/view_member_account_settings', [
            'user'              => $member,
            'personalAddress'   => $personalAddress,
            'breadcrumbs'       => $breadcrumbs,
            'text_parts'        => $text_parts,
            'in_sidebar'        => $sidebar_link,
            'avatar'            => $avatar['avatar_base64'],
            'documents'         => $userDocuments,
            'membership_plan'   => $my_plan,
            'plan_requests'     => $plan_request,
            'restrictions'          => @$restrictions,
            'plan_details'          => @$plan_details,
            'memberships'           => $membership_plans,
            'update_memberships'    => $membership_plans_update,
            'plannedInvoices'       => @$plannedInvoices,
            'invoiceCancellation'   => $invoiceCancellation,
            'invoiceFreeze'         => $invoiceFreeze,
            'canCancel'         => $canCancel,
            'canFreeze'         => $canFreeze,
            'canUpdate'         => $canUpdate,
            'accessCardNo'      => @$accessCardNo
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_personal_settings($id)
    {
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $member = User::with('roles')->find($id);
        if (!$member || !$member->is_front_user()){
            return redirect(route('admin/error/not_found'));
        }

        $text_parts  = [
            'title'     => 'Back-End Users',
            'subtitle'  => 'view all users',
            'table_head_text1' => 'Backend User List'
        ];

        @$userRole = $member->roles[0];
        if (!$userRole){
            $defaultRole = Role::where('name','employee')->get();
            $userRole = $defaultRole[0];
        }
        $permissions = Permission::all();

        $userProfessional = $member->ProfessionalDetail;
        if (!isset($userProfessional)){
            $userProfessional = new ProfessionalDetail();
        }

        $userPersonal = $member->PersonalDetail;
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
        $countries = Cache::remember('countries', 3660, function() {
                return Countries::orderBy('name')->get();
            });
        $userCountry = Countries::find($member->country_id);

        $avatar = $member->get_avatar_image();

        $avatarArchive = [];
        $old_avatars = Storage::disk('local')->files($avatar['file_location']);
        if ($old_avatars){
            foreach($old_avatars as $old_avatar){
                $avatarArchive[] = [
                    'type'  => Storage::disk('local')->mimeType($old_avatar),
                    'data'  => Storage::disk('local')->get($old_avatar)
                ];
            }
        }

        $userDocuments = UserDocuments::where('user_id','=',$id)->where('category','=','account_documents')->get();

        $plan_request = [];
        $invoiceCancellation = [];
        $invoiceFreeze = [];
        $canCancel = true;
        $canFreeze = true;

        $my_plan = UserMembership::where('user_id','=',$member->id)->whereIn('status',['active','suspended'])->get()->first();
        if ($my_plan){
            $restrictions = $my_plan->get_plan_restrictions();
            $plan_details = $my_plan->get_plan_details();

            $currentInvoiceDate = UserMembershipInvoicePlanning::where('status','=','old')->where('user_membership_id','=',$my_plan->id)->orderBy('issued_date','desc')->get()->first();
            if ($currentInvoiceDate) {
                $invoice_date1 = Carbon::createFromFormat('Y-m-d', $currentInvoiceDate->issued_date);
                $invoice_date2 = Carbon::createFromFormat('Y-m-d', $currentInvoiceDate->last_active_date);

                $plan_details['invoicePeriod'] = $invoice_date1->format('j M Y') . ' - ' . $invoice_date2->format('j M Y');
            }
            else{
                $plan_details['invoicePeriod'] = '-';
            }

            $nextInvoiceDate = UserMembershipInvoicePlanning::where('status','=','pending')->where('user_membership_id','=',$my_plan->id)->orderBy('issued_date','asc')->get()->first();
            if ($nextInvoiceDate) {
                $invoice_date1 = Carbon::createFromFormat('Y-m-d', $nextInvoiceDate->issued_date);
                $invoice_date2 = Carbon::createFromFormat('Y-m-d', $nextInvoiceDate->last_active_date);

                $plan_details['nextInvoicePeriod'] = $invoice_date1->format('j M Y') . ' - ' . $invoice_date2->format('j M Y');
            }
            else{
                $plan_details['nextInvoicePeriod'] = '-';
            }

            $plan_request = $my_plan->get_plan_requests();
            foreach($plan_request as $a_request){
                if ($a_request['action_type'] == 'cancel' && $a_request['status']!='cancelled'){
                    $canCancel = false;
                }
                elseif ($a_request['action_type'] == 'freeze' && $a_request['status']=='active'){
                    $canFreeze = false;
                }
            }

            $allPlannedInvoices = UserMembershipInvoicePlanning::where('user_membership_id','=',$my_plan->id)->orderBy('issued_date','asc')->get();
            $plannedInvoices = [];
            foreach($allPlannedInvoices as $onlyOne){
                $varInv = [
                    'invoiceLink'   => '',
                    'invoiceStatus' => '',
                    'item_name' => $onlyOne->item_name,
                    'price'     => $onlyOne->price,
                    'status'    => $onlyOne->status,
                    'issued_date'   => $onlyOne->issued_date,
                    'last_active_date'   => $onlyOne->last_active_date
                ];

                if ($onlyOne->invoice_id!=-1){
                    $getInvoice = Invoice::where('id','=',$onlyOne->invoice_id)->get()->first();
                    $varInv['status']= 'issued';
                    $varInv['invoiceStatus']= $getInvoice->status;
                    $varInv['invoiceLink']  = route('admin/invoices/view', ['id' => $getInvoice->invoice_number]);
                }

                $plannedInvoices[$onlyOne->id] = $varInv;

                foreach ($plan_request as $key=>$a_request){
                    if (!isset($a_request['after_date']) && $a_request['start_date']->lte(Carbon::createFromFormat('Y-m-d H:i:s', $onlyOne->issued_date.' 00:00:00'))){
                        $plan_request[$key]['after_date'] = Carbon::createFromFormat('Y-m-d H:i:s', $onlyOne->issued_date.' 00:00:00');
                    }
                }
            }

            $signOutDate = Carbon::today()->addMonths($my_plan->sign_out_period);
            //xdebug_var_dump($allPlannedInvoices[0]); exit;
            foreach($allPlannedInvoices as $onlyOne){
                if ($onlyOne->status == 'pending'){
                    $invoiceFreeze[$onlyOne->id] = Carbon::createFromFormat('Y-m-d',$onlyOne->issued_date)->format('jS \o\f F, Y');
                }

                //xdebug_var_dump(Carbon::createFromFormat('Y-m-d',$onlyOne->issued_date));
                if ($signOutDate->lte(Carbon::createFromFormat('Y-m-d',$onlyOne->issued_date))){
                    $invoiceCancellation[$onlyOne->id] = Carbon::createFromFormat('Y-m-d',$onlyOne->last_active_date)->addDay()->format('jS \o\f F, Y');
                }
            }
            //xdebug_var_dump($invoiceCancellation);
            //exit;
        }
        else {
            $my_plan = MembershipPlan::where('id','=',1)->get()->first();
        }
        $membership_plans = MembershipPlan::where('id','!=','1')->where('status','=','active')->get()->sortBy('name');

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End Users'    => route('admin/back_users'),
            $member->first_name.' '.$member->middle_name.' '.$member->last_name => '',
        ];
        $sidebar_link= 'admin-frontend-user_details_view';

        return view('admin/front_users/view_member_personal_settings', [
            'user'      => $member,
            'userRole'  => $userRole,
            'professional' => $userProfessional,
            'personal'  => $userPersonal,
            'personalAddress'   => $personalAddress,
            'countryDetails'    => $userCountry,
            'countries' => $countries,
            'roles'     => $roles,
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'avatar'      => $avatar['avatar_base64'],
            'permissions' => $permissions,
            'documents'   => $userDocuments,
            // membership plan
            'membership_plan'   => $my_plan,
            'plan_requests'     => $plan_request,
            //'activities'    => $activities,
            'restrictions'  => @$restrictions,
            'plan_details'  => @$plan_details,
            'memberships'   => $membership_plans,
            'old_avatars'   => $avatarArchive,
            'plannedInvoices'       => @$plannedInvoices,
            'invoiceCancellation'   => $invoiceCancellation,
            'invoiceFreeze'         => $invoiceFreeze,
            'canCancel'     => $canCancel,
            'canFreeze'     => $canFreeze
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show_bookings($id){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $member = User::with('roles')->find($id);
        if (!$member || !$member->is_front_user()){
            return redirect(route('admin/error/not_found'));
        }

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
        if (sizeof($bookings)>0){
            $buttons = [];
            $colorStatus = '';
            foreach ($bookings as $booking){
                switch ($booking->status) {
                    case 'pending' :
                        $colorStatus = 'warning';
                        $buttons = 'yellow-gold';
                        break;
                    case 'expired' :
                        $colorStatus = 'info';
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
                        $colorStatus = 'warning';
                        $buttons = 'yellow-gold';
                        break;
                    case 'noshow' :
                        $colorStatus = 'danger';
                        $buttons = 'red-thunderbird';
                        break;
                    case 'old' :
                        $colorStatus = 'info';
                        $buttons = 'green-meadow';
                        break;
                    case 'canceled' :
                        $colorStatus = 'warning';
                        $buttons = 'yellow-lemon';
                        break;
                }

                $date       = Carbon::createFromFormat('Y-m-d', $booking->date_of_booking)->format('l, M jS, Y');
                $addedOn    = Carbon::createFromFormat('Y-m-d H:i:s', $booking->updated_at)->format('j/m/Y H:i');
                $dateSmall  = Carbon::createFromFormat('Y-m-d', $booking->date_of_booking)->format('j/m/Y');
                $timeInterval = Carbon::createFromFormat('H:i:s', $booking->booking_time_start)->format('H:i').' - '.Carbon::createFromFormat('H:i:s', $booking->booking_time_stop)->format('H:i');

                $madeForID  = $booking->for_user_id;
                $userFor    = User::find($booking->for_user_id);
                if ($userFor) {
                    $madeFor = $userFor->first_name . ' ' . $userFor->middle_name . ' ' . $userFor->last_name;
                }
                else{
                    $madeFor = ' - ';
                }

                $madeByID   = $booking->by_user_id;
                $userBy     = User::find($booking->by_user_id);
                if ($userBy) {
                    $madeBy = $userBy->first_name . ' ' . $userBy->middle_name . ' ' . $userBy->last_name;
                }
                else{
                    $madeBy = ' - ';
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
                    'bookingByName' => $madeBy,
                    'bookingByID'   => $madeByID,
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
                    'added_on'      => $addedOn,
                    'type'          => $booking->payment_type
                ];
            }
            unset($bookings); unset($booking);

            $upcomingBookings = [];
            foreach($bookingsList as $key=>$lastOne){
                if ($lastOne['status']!='active' && $lastOne['status']!='pending'){
                    continue;
                }

                switch ($lastOne['status']) {
                    case 'pending' :
                        $colorStatus = 'label-warning';
                        break;
                    case 'expired' :
                        $colorStatus = 'label-default';
                        break;
                    case 'active' :
                        $colorStatus = 'label-success';
                        break;
                    case 'paid' :
                        $colorStatus = 'label-success';
                        break;
                    case 'unpaid' :
                        $colorStatus = 'label-danger';
                        break;
                    case 'noshow' :
                        $colorStatus = 'label-danger';
                        break;
                    case 'old' :
                        $colorStatus = 'label-info';
                        break;
                    case 'canceled' :
                        $colorStatus = 'label-default';
                        break;
                }
                $lastOne['status-color'] = $colorStatus;
                $upcomingBookings[] = $lastOne;

                unset($bookingsList[$key]);
            }
        }

        $avatar = $member->get_avatar_image();

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End Users'    => route('admin/back_users'),
            $member->first_name.' '.$member->middle_name.' '.$member->last_name => '',
        ];
        $sidebar_link= 'admin-frontend-user_details_view';

        return view('admin/front_users/view_member_bookings', [
            'user'          => $member,
            'breadcrumbs'   => $breadcrumbs,
            'text_parts'    => $text_parts,
            'in_sidebar'    => $sidebar_link,
            'avatar'        => $avatar['avatar_base64'],
            'bookings'          => $bookingsList,
            'upcomingBookings'  =>  isset($upcomingBookings)?$upcomingBookings:[]
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
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $member = User::find($id);
        if (!$member || !$member->is_front_user()){
            return redirect(route('admin/error/not_found'));
        }

        $generalInvoiceList = [];
        $lastTenGeneralInvoice = [];
        $generalInvoices = Invoice::where('user_id','=',$id)->orderBy('created_at','desc')->get();
        if (sizeof($generalInvoices)>0){
            $buttons = [];
            $colorStatus = '';
            foreach ($generalInvoices as $single_invoice){
                switch ($single_invoice->status) {
                    case 'pending':
                        $colorStatus = 'warning';
                        $buttons = 'yellow-gold';
                        $explained = 'Not Paid';
                        break;
                    case 'ordered':
                    case 'processing':
                        $colorStatus = 'info';
                        $buttons = 'green-meadow';
                        $explained = 'Processing';
                        break;
                    case 'completed':
                        $colorStatus = 'success';
                        $buttons = 'green-jungle';
                        $explained = 'Paid';
                        break;
                    case 'cancelled':
                        $colorStatus = 'warning';
                        $buttons = 'yellow-lemon';
                        $explained = 'Cancelled';
                        break;
                    case 'declined':
                    case 'incomplete':
                        $colorStatus = 'danger';
                        $buttons = 'red-thunderbird';
                        $explained = 'Declined';
                        break;
                    case 'preordered':
                        $colorStatus = 'info';
                        $buttons = 'green-meadow';
                        $explained = '';
                        break;
                    default:
                        $explained = 'Unknown';
                        break;
                }

                $price = 0;
                $items = 0;
                $display_name = '-';
                $invoiceItems = InvoiceItem::where('invoice_id','=',$single_invoice->id)->get();
                if ($invoiceItems){
                    foreach ($invoiceItems as $invItem){
                        $price+=$invItem->total_price;
                        $items++;

                        if ($display_name=='-' && $invItem->item_type=='user_memberships'){
                            $display_name = 'Membership - '.$invItem->item_name;
                        }
                        elseif ($display_name=='-' && $invItem->item_type=='booking_invoice_item'){
                            $bookingItem = BookingInvoiceItem::where('id','=',$invItem->item_reference_id)->get()->first();
                            $display_name = 'Booking - '.@$bookingItem->location_name;
                        }
                        elseif ($display_name=='-' && $invItem->item_type=='store_credit_item'){
                            $bookingItem = BookingInvoiceItem::where('id','=',$invItem->item_reference_id)->get()->first();
                            $display_name = 'Store Credit - '.@$bookingItem->location_name;
                        }
                    }

                    if ($items==0){
                        continue;
                    }
                }

                $addedOn    = Carbon::createFromFormat('Y-m-d H:i:s', $single_invoice->created_at)->format('j/m/Y');
                $generalInvoiceList[] = [
                    'invoice_id'    => $single_invoice->id,
                    'invoice_no'    => $single_invoice->invoice_number,
                    'date'          => $addedOn,
                    'status'            => $single_invoice->status,
                    'status_explained'  => $explained,
                    'color_status'      => $colorStatus,
                    'color_button'  => $buttons,
                    'price_to_pay'  => $price,
                    'items'         => $items,
                    'display_name'  => $display_name,
                    'invoice_type'  => $single_invoice->invoice_type
                ];
            }

            $nr = 0;
            foreach($generalInvoiceList as $invoice){
                if ($nr>9){
                    break;
                }

                //if ( $invoice['invoice_type'] == 'booking_invoice'){
                //    continue;
                //}

                $sum_price = 0;
                $sum_discount = 0;
                $sum_total = 0;
                $is_first = 0;

                $invoiceItems = InvoiceItem::where('invoice_id','=',$invoice['invoice_id'])->get();
                $invType = ['store_credit_item'=>'Store Credit','user_memberships'=>'Membership Plan','booking_invoice_item'=>'Booking Item'];
                if (sizeof($invoiceItems)>0){
                    foreach($invoiceItems as $item){
                        $new_set = [
                            'item_name' => $item->item_name,
                            'item_type' => $invType[$item->item_type],
                            'price'     => $item->price,
                            'discount'  => $item->discount,
                            'total'     => $item->total_price,
                            'date'      => Carbon::createFromFormat('Y-m-d H:i:s', $item->created_at)->format('j/m/Y')
                        ];
                        if ($is_first==0){
                            $is_first=1;
                            $new_set['colspan'] = sizeof($invoiceItems)+1;
                            $new_set['invoice_id'] = $invoice['invoice_id'];
                            $new_set['invoice_no'] = $invoice['invoice_no'];
                        }

                        $lastTenGeneralInvoice[] = $new_set;

                        $sum_price+= $item->price;
                        $sum_discount+= $item->discount;
                        $sum_total+= $item->total_price;
                    }
                }

                switch ($invoice['status']) {
                    case 'pending' :
                        $colorStatus = 'label-warning';
                        break;
                    case 'expired' :
                        $colorStatus = 'label-default';
                        break;
                    case 'active' :
                        $colorStatus = 'label-success';
                        break;
                    case 'paid' :
                        $colorStatus = 'label-success';
                        break;
                    case 'unpaid' :
                        $colorStatus = 'label-danger';
                        break;
                    case 'noshow' :
                        $colorStatus = 'label-danger';
                        break;
                    case 'old' :
                        $colorStatus = 'label-info';
                        break;
                    case 'canceled' :
                        $colorStatus = 'label-default';
                        break;
                }

                $lastTenGeneralInvoice[] = [
                    'item_name' => '',
                    'item_type' => '',
                    'status'            => $invoice['status'],
                    'status_explained'  => $invoice['status_explained'],
                    'color_status'      => $colorStatus,
                    'price'     => $sum_price,
                    'discount'  => $sum_discount,
                    'total'     => $sum_total,
                    'date'      => $invoice['date'],
                    'invoice_no'=> $invoice['invoice_no']
                ];
                $nr++;
            }
        }

        $avatar = $member->get_avatar_image();

        $text_parts  = [
            'title'     => 'Back-End Users',
            'subtitle'  => 'view all users',
            'table_head_text1' => 'Backend User List'
        ];
        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End Users'    => route('admin/back_users'),
            $member->first_name.' '.$member->middle_name.' '.$member->last_name => '',
        ];
        $sidebar_link= 'admin-frontend-user_details_view';

        //xdebug_var_dump($invoicesList);
        //xdebug_var_dump($generalInvoiceList);
        //xdebug_var_dump($lastTenGeneralInvoice);

        return view('admin/front_users/view_member_finance', [
            'user'      => $member,
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'avatar'      => $avatar['avatar_base64'],
            'generalInvoices' => $generalInvoiceList,
            'lastTenGeneral'  => $lastTenGeneralInvoice,
            'multipleBookingsIndex' => isset($index)?$index:[],
        ]);
    }

    public function update_account_avatar(Request $request, $id){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        //$user = User::findOrFail($id);

        $avatarLocation = 'members/'.$id.'/avatars/';
        $avatarFilename = $user->username.'.'.$request->file('user_avatar')->getClientOriginalExtension();
        $exists = Storage::disk('local')->exists($avatarLocation . $avatarFilename);
        if ($exists){
            $old_avatar_name = time().'-'.$avatarFilename.'.old';
            Storage::disk('local')->move( $avatarLocation . $avatarFilename, $avatarLocation . $old_avatar_name);
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
        return redirect()->intended(route('admin/front_users/view_personal_settings', ['id' => $id]));
    }

    /**
     * Update member/user personal details - needs updated
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|string
     */
    public function update_personal_info(Request $request, $id)
    {
        if (!Auth::check()) {
            return [
                'success' => false,
                'title'   => 'Authentication Error',
                'errors'  => 'Please reload the page and try again'
            ];
        }
        else{
            $user = Auth::user();
        }

        $is_staff = false;
        if (!$user->hasRole(['front-member','front-user'])){
            $is_staff = true;
        }

        if ($id!=$user->id && $is_staff || $id==$user->id) {
            // an employee is updating a member details
            $user = User::findOrFail($id);
        }
        else{
            return [
                'success' => false,
                'title'   => 'Authentication Error',
                'errors'  => 'Please reload the page and try again'
            ];
        }

        $vars = $request->only('about_info', 'country_id', 'date_of_birth', 'first_name', 'last_name', 'middle_name', 'gender', 'mobile_number', 'personal_email');

        $userVars = array(
            'first_name'    => $vars["first_name"],
            'last_name'     => $vars["last_name"],
            'middle_name'   => $vars["middle_name"],
            'gender'        => $vars['gender'],
            'country_id'    => $vars["country_id"],
            'date_of_birth' => $vars["date_of_birth"]);

        $validator = Validator::make($userVars, [
            'first_name'    => 'required|min:2|max:150',
            'last_name'     => 'required|min:2|max:150',
            'date_of_birth' => 'required|date',
            'country_id'    => 'required|exists:countries,id',
            'gender'        => 'required|in:M,F'
        ]);

        if ($validator->fails()){
            return [
                'success' => false,
                'title'   => 'Validation Error',
                'errors'  => $validator->getMessageBag()->toArray()
            ];
        }
        else{
            $user->first_name  = $vars["first_name"];
            $user->last_name   = $vars["last_name"];
            $user->middle_name = $vars["middle_name"];
            $user->country_id  = $vars["country_id"];
            $user->gender      = $vars["gender"];
            $user->save();
        }

        $personalData = array(  'personal_email'=> $vars['personal_email'],
            'mobile_number' => $vars['mobile_number'],
            'date_of_birth' => $vars['date_of_birth'],
            'about_info'    => $vars['about_info'],
            'user_id'       => $user->id);
        $personalDetails = PersonalDetail::firstOrNew(array('user_id'=>$user->id));
        $personalData['date_of_birth'] = Carbon::createFromFormat('d-m-Y', $personalData['date_of_birth'])->toDateString();
        $personalDetails->fill($personalData);
        $personalDetails->save();

        return [
            'success' => true,
            'title'   => 'Information Updated',
            'message' => 'Member/user information successfully updated'
        ];
    }

    public function update_personal_address(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return [
                'success' => false,
                'title'   => 'Authentication Error',
                'errors'  => 'Please reload the page and try again'
            ];
        }

        $client = User::where('id','=',$id)->get()->first();
        if (!$client){
            return [
                'success' => false,
                'title'   => 'Client not found',
                'errors'  => 'No client found with the given details'
            ];
        }

        $userPersonal = PersonalDetail::where('user_id','=',$client->id)->get()->first();

        $vars = $request->only('address1', 'address2', 'city', 'country_id', 'postal_code', 'region');
        $validator = Validator::make($vars, [
            'address1'    => 'required|min:5|max:150',
            'city'        => 'required|min:3|max:150',
            'region'      => 'required|min:2',
            'postal_code' => 'required|min:2',
            'country_id'  => 'required|exists:countries,id',
        ]);

        if ($validator->fails()){
            return array(
                'success' => false,
                'title'   => 'Error validating',
                'errors'  => $validator->getMessageBag()->toArray()
            );
        }
        else{
            if ( !isset($userPersonal) ){
                $userPersonal = new PersonalDetail();
                $userPersonal->user_id = $client->id;
                $personalAddress = new Address();
            }
            else {
                $addressID = $userPersonal->address_id;
                if ($addressID==0){
                    $personalAddress = new Address();
                }
                else{
                    $personalAddress = Address::find($addressID);
                }
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

            return [
                'success' => true,
                'title'   => 'Address Added/Updated',
                'message' => 'Client address details successfully updated'
            ];
        }
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
            return ['success' => false,
                'title'   => 'Authentication Error',
                'errors'  => 'Please reload the page and try again'];
        }
        else{
            $user = Auth::user();
        }

        $is_staff = false;
        if (!$user->hasRole(['front-member','front-user'])){
            $is_staff = true;
        }

        if ($id!=$user->id && $is_staff) {
            // an employee is updating a member password
            $user = User::findOrFail($id);

            $userVars = $request->only('password1','password2');
            // Validate the new password length...
            $validator = Validator::make($userVars, [
                'password1'     => 'required|min:8',
                'password2'     => 'required|min:8|same:password1',
            ]);
        }
        else {
            $userVars = $request->only('old_password','password1','password2');
            // Validate the new password length and require old password...
            $validator = Validator::make($userVars, [
                'old_password'  => 'required|min:8',
                'password1'     => 'required|min:8',
                'password2'     => 'required|min:8|same:password1',
            ]);
        }

        if ($validator->fails()){
            return [
                'success' => false,
                'title'   => 'New password validation failed',
                'errors'  => $validator->getMessageBag()->toArray()
            ];
        }
        else {
            if ($is_staff){
                $user->fill([
                    'password' => Hash::make($request->password1)
                ])->save();

                return [
                    'success' => true,
                    'title' => 'Password updated',
                    'message' => 'Old password changed ... user updated'
                ];
            }
            else{
                $auth = auth();
                if ($auth->attempt([ 'id' => $id, 'password' => $userVars['old_password'] ])) {
                    $user->fill([
                        'password' => Hash::make($request->password1)
                    ])->save();

                    return [
                        'success' => true,
                        'title' => 'Password updated',
                        'message' => 'Old password changed ... user updated'
                    ];
                }
                else{
                    return [
                        'success' => false,
                        'title'  => 'Error updating password',
                        'errors' => 'Old password mismatch'
                    ];
                }
            }
        }
    }

    public function update_personal_avatar(Request $request, $id){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $user = User::findOrFail($id);

        $avatarLocation = 'members/'.$id.'/avatars/';
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

        $documentLocation = 'members/'.$id.'/documents/';
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

        $file_path = 'members/'.$id.'/documents/'. $document_name;
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

    /**
     * Check if the booking fillable can be transformed into a booking
     * @param $fillable
     * @param string $search_key
     * @param bool $recurring
     * @return array
     */
    private function validate_booking($fillable, $search_key='', $recurring = false, $is_employee = false){
        $message = ['status'=>true, 'payment'=>'membership'];

        // get user membership if exists
        $active_membership = UserMembership::where('user_id','=',$fillable['for_user_id'])->where('status','=','active')->get()->first();
        if ($active_membership){
            $restrictions = $active_membership->get_plan_restrictions();
        }
        else{
            $default_membership = MembershipPlan::where('id','=',1)->get()->first();
            if ($default_membership){
                $restrictions = $default_membership->get_restrictions(true);
            }
            else{
                $restrictions = [];
            }
        }

        $message['payment'] = $this->check_membership_restrictions($fillable, $restrictions, $search_key);

        // check for existing booking on the same resource, bookings that are already made
        $openBookings = Booking::whereIn('status',['pending','active'])
            ->where('resource_id',        '=',  $fillable['resource_id'])
            ->where('location_id',        '=',  $fillable['location_id'])
            ->where('date_of_booking',    '=',  $fillable['date_of_booking'])
            ->where('booking_time_start', '=',  $fillable['booking_time_start'])
            ->where('search_key',         '!=', $search_key)
            ->get()->first();
        if (sizeof($openBookings)>0){
            // we have another booking with the same details
            $message['status'] = false;
        }

        return $message;
    }

    private function check_membership_restrictions($fillable, $restrictions, $search_key = 'c12abab@abab#abab12c'){
        // we assume the payment type is membership then we go through all the restrictions and see if any of them is broken
        $payment_type = 'membership';

        // get activity selected for booking
        $resource_category = ShopResource::where('id','=',$fillable['resource_id'])->get()->first();

        // check for open bookings
        $ownBookings = Booking::whereIn('status',['pending','active'])
            ->where('for_user_id','=',$fillable['for_user_id'])
            ->where('search_key','!=',$search_key)
            ->where('payment_type','=','membership')
            ->get();
        $nr_of_open_bookings = sizeof($ownBookings);

        $time_of_day_result = [];

        foreach ($restrictions as $restriction){
            switch ($restriction['name']){
                case 'time_of_day' : {
                    $status = true; //break;

                    //'value' => string '[1,2,3,4,5]' (length=11)
                    $selected_days  = json_decode($restriction['value']);
                    //'time_start' => string '06:00' (length=5)
                    if (substr_count($restriction['time_start'], ':')==1) {
                        $hour_start = Carbon::createFromFormat('H:i', $restriction['time_start']);
                    }
                    else{
                        $hour_start = Carbon::createFromFormat('H:i:s', $restriction['time_start']);
                    }
                    //'time_end' => string '14:00' (length=5)
                    if (substr_count($restriction['time_end'], ':')==1) {
                        $hour_end = Carbon::createFromFormat('H:i', $restriction['time_end']);
                    }
                    else{
                        $hour_end = Carbon::createFromFormat('H:i:s', $restriction['time_end']);
                    }
                    // booking hour
                    if (substr_count($fillable['booking_time_start'], ':')==1) {
                        $booking_hour = Carbon::createFromFormat('H:i', $fillable['booking_time_start']);
                        $booking_day_time   = Carbon::createFromFormat('Y-m-d H:i', $fillable['date_of_booking'].' '.$fillable['booking_time_start']);
                    }
                    else{
                        $booking_hour = Carbon::createFromFormat('H:i:s', $fillable['booking_time_start']);
                        $booking_day_time   = Carbon::createFromFormat('Y-m-d H:i:s', $fillable['date_of_booking'].' '.$fillable['booking_time_start']);
                    }
                    // day of week for the booking
                    $booking_day        = Carbon::createFromFormat('Y-m-d', $fillable['date_of_booking'])->format('w');

                    // we check the day first
                    if (!in_array($booking_day, $selected_days)){
                        // not in selected day
                        $status = false;
                    }
                    // we check the hours interval second
                    elseif ($booking_hour->lt($hour_start) || $booking_hour->gte($hour_end)){
                        // not in selected time period
                        $status = false;
                    }

                    $special_restrictions = json_decode($restriction['special_permissions']);
                    if (sizeof($special_restrictions)>=1 && $status!=false && $special_restrictions->special_days_ahead!=-1){
                        if (!isset($special_restrictions->special_days_ahead)){
                            $special_restrictions->special_days_ahead = 1;
                        }

                        $now_day = Carbon::now();
                        if ($special_restrictions->special_current_day=='1') {
                            // we calculate the time when this period can be booked - start interval
                            $start_interval = Carbon::instance($booking_day_time)->subDays($special_restrictions->special_days_ahead)->startOfDay();
                            // we calculate the end interval
                            $end_interval   = Carbon::instance($booking_day_time)->endOfDay();
                        }
                        else{
                            // we calculate the time when this period can be booked - start interval
                            $start_interval = Carbon::instance($booking_day_time)->subDays($special_restrictions->special_days_ahead)->startOfDay();
                            // we calculate the end interval
                            $end_interval   = Carbon::instance($booking_day_time)->subDays(1)->endOfDay();
                        }
                        //echo $now_day->toDateTimeString().' : '.$start_interval->toDateTimeString().' to '.$end_interval->toDateTimeString(); //exit;

                        if ($now_day->gte($start_interval) && $now_day->lt($end_interval)){
                            //$status = true;
                        }
                        else{
                            $status = false;
                        }
                    }

                    $time_of_day_result[] = $status;
                }
                    break;
                case 'open_bookings' : {
                    $the_open_restrictions = (int)$restriction['value'][0];
                    //echo '<br />'.$nr_of_open_bookings.' >= '.$the_open_restrictions.' -- '.$fillable['for_user_id'].'<br />';
                    if ( $nr_of_open_bookings >= $the_open_restrictions ){
                        //echo '<br />'.$nr_of_open_bookings.' >= '.$the_open_restrictions.' -- '.$fillable['for_user_id'].'<br />';
                        $payment_type = 'cash';
                        return $payment_type;
                    }
                }
                    break;
                case 'cancellation' : {
                    // do not need it here
                }
                    break;
                case 'price' : {
                    // do not need it here
                }
                    break;
                case 'included_activity' : {
                    $activities_in = json_decode($restriction['value']);
                    if (!in_array($resource_category->category_id, $activities_in)){
                        //xdebug_var_dump($fillable);
                        //xdebug_var_dump($activities_in);
                        //xdebug_var_dump($resource_category->category_id);
                        //echo '<br />Not in category<br />';
                        $payment_type = 'cash';
                        return $payment_type;
                    }
                }
                    break;
                case 'booking_time_interval' : {
                    // booking must take place x hours from now
                    $hours_from_now      = $restriction['min_value'];
                    // bookings must be made until y hours from now
                    $hours_until_booking = $restriction['max_value'];

                    if (substr_count($fillable['booking_time_start'], ':')==1) {
                        $booking_date_time = Carbon::createFromFormat('Y-m-d H:i', $fillable['date_of_booking'] . ' ' . $fillable['booking_time_start']);
                    }
                    else{
                        $booking_date_time = Carbon::createFromFormat('Y-m-d H:i:s', $fillable['date_of_booking'] . ' ' . $fillable['booking_time_start']);
                    }
                    $closest_booking_date_time = Carbon::now()->addHours($hours_from_now);
                    $farthest_booking_date_time = Carbon::now()->addHours($hours_until_booking);

                    if (!$booking_date_time->gte($closest_booking_date_time) || !$booking_date_time->lt($farthest_booking_date_time)){
                        //echo '<br />Not in booking time interval<br />';
                        $payment_type = 'cash';
                        return $payment_type;
                    }
                }
                    break;
            }
        }

        foreach ($time_of_day_result as $a){
            if ($a == true){
                $payment_type = 'membership';
                return $payment_type;
            }
            else{
                $payment_type = 'cash';
            }
        }

        return $payment_type;
    }

    public function ajax_get_available_players_list(Request $request){
        if (!Auth::check()) {
            return [];
        }
        else{
            $user = Auth::user();
            $user_id = $user->id;
        }

        $is_staff = false;
        if (!$user->hasRole(['front-member','front-user'])){
            $is_staff = true;
        }

        $vars = $request->only('userID', 'resourceID', 'booking_time_start', 'booking_day', 'search_key');
        $fillable = [
            'for_user_id'   => -1,
            'resource_id'   => -1,
            'location_id'   => -1,
            'date_of_booking'    => $vars['booking_day'],
            'booking_time_start' => $vars['booking_time_start'],
            'search_key'    => ''
        ];

        if (strlen($vars['search_key'])>2){
            $booking = Booking::where('search_key','=',$vars['search_key'])->get()->first();
            if (!$booking){
                return [];
            }
            else{
                $fillable['search_key']     = $booking->search_key;
                $fillable['resource_id']    = $booking->resource_id;
                $fillable['location_id']    = $booking->location_id;
                $fillable['date_of_booking']    = $booking->date_of_booking;
                $fillable['booking_time_start'] = $booking->booking_time_start;
            }
        }
        else {
            $resource = ShopResource::where('id', '=', $vars['resourceID'])->get()->first();
            if (!$resource) {
                return [];
            }
            else {
                $fillable['resource_id'] = $resource->id;
                $fillable['location_id'] = $resource->location_id;
            }
        }

        if (isset($vars['userID'])){
            if ($user_id!=$vars['userID'] && $is_staff==true){
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
            $fillable['for_user_id'] = $friend_id;

            if (!$user_details){ continue; }

            $canBook = $this->validate_booking($fillable, $vars['search_key']);
            //xdebug_var_dump($fillable);
            //xdebug_var_dump($canBook);
            if ( $canBook['status']==false || $canBook['payment']=='cash' ){
                continue;
            }
            else {
                $all_friends[] = [
                    'name' => $user_details->first_name . ' ' . $user_details->middle_name . ' ' . $user_details->last_name,
                    'id' => $user_details->id,
                    'status'    => $canBook['status'],
                    'payment'   => $canBook['payment']
                ];
            }
        }
        //exit;
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
            $msg = [
                'success'=>'false',
                'error'=> [
                    'title'=>'An error occurred',
                    'message'=>'Please check the number and add it again. You have a limited number of attempts'
                ]
            ];
        }
        else {
            // one friend found, now we search to see if he's a front user/member
            $newFriend = User::where('id','=',$friends->user_id)->get()->first();
            if ($newFriend->is_back_user()){
                $msg = [
                    'success'=>'false',
                    'error'=> [
                        'title'   => 'An error occurred',
                        'message' => 'Please check the number for errors; no player found with that number'
                    ]
                ];
            }
            else{
                $friend_fill = ['user_id'=>$user_id, 'friend_id'=>$friends->user_id];
                $validator = Validator::make($friend_fill, UserFriends::rules('POST'), UserFriends::$message, UserFriends::$attributeNames);

                if ($validator->fails()){
                    $msg = array(
                        'success' => 'false',
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

                    $friend = User::where('id','=',$friends->user_id)->get()->first();
                    $msg = ['success'=>'true', 'message' => 'You have a new friend', 'full_name' => $friend->first_name.' '.$friend->middle_name.' '.$friend->last_name ];
                }
            }
        }

        return $msg;
    }

    public function remove_friend_from_list(Request $request, $id=-1){
        if (!Auth::check()) {
            return [
                'success'=>false,
                'title'=>'An error occurred',
                'errors'=> 'You need to be logged in to have access to this function'
            ];
        }
        else{
            $user = Auth::user();
        }

        $vars = $request->only('search_key');
        $is_staff = !$user->hasRole(['front-member','front-user']);

        if ($id==-1){
            $user_id = $user->id;
        }
        elseif ($is_staff==true){
            $user_id = $id;
        }
        else{
            return [
                'success'=>false,
                'title'  =>'An error occurred',
                'errors' => 'Error in page configuration'
            ];
        }

        $friend = UserFriends::where('id','=',$vars['search_key'])->get()->first();
        if($friend){
            if ($friend->user_id!=$user_id && $friend->friend_id!=$user_id){
                return [
                    'success'=> false,
                    'title'  => 'An error occurred',
                    'errors' => 'The selected friend could not be found on your friends list. Try logout/login first'
                ];
            }

            $friend->delete();
            return [
                'success'=> true,
                'title'  => 'Friend removed from Friends list',
                'message' => 'You have now a friend less than before'
            ];
        }
        else{
            return [
                'success'=> false,
                'title'  => 'An error occurred',
                'errors' => 'Could not find the friend you selected.'
            ];
        }
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

    public function new_member_registration(Request $request){
        $user = Auth::user();
        if ($user && $user->is_back_user()) {
            $by_user = $user;
        }

        $vars = $request->only('first_name', 'middle_name', 'last_name', 'gender', 'email', 'phone_number', 'dob', 'password', 'rpassword', 'username', 'user_type',
            'address1', 'address2', 'city', 'adr_country_id', 'postal_code', 'region',
            'membership_plan', 'start_date'); //exit;

        if (!isset($vars['middle_name'])){
            $vars['middle_name'] = '';
        }

        if (!isset($vars['username'])){
            $vars['username'] = $vars['email'];
        }

        if (!isset($vars['user_type'])){
            $userType = Role::where('name','=','front-user')->get()->first();
        }
        else{
            $userType = Role::where('id','=',$vars['user_type'])->get()->first();
            if (!$userType){
                $userType = Role::where('name','=','front-user')->get()->first();
            }
        }

        if ($vars['password']==""){
            $vars['password'] = substr(bcrypt(str_random(12)),0,8);
        }

        if (!isset($vars['country_id'])){
            $vars['country_id'] = Config::get('constants.globalWebsite.defaultCountryId');
        }

        if (!isset($vars['dob']) || $vars['dob']==''){
            $vars['date_of_birth'] = Carbon::today()->toDateString();
        }
        else{
            $vars['date_of_birth'] = Carbon::createFromFormat('d-m-Y',$vars['dob'])->toDateString();
        }

        if (!isset($userType)){
            return [
                'success'   => false,
                'title'     => 'No User Types',
                'errors'    => 'User type not found for front member'
            ];
        }

        $credentials = [
            'first_name'    => $vars['first_name'],
            'middle_name'   => $vars['middle_name'],
            'last_name'     => $vars['last_name'],
            'gender'        => $vars['gender'],
            'username'      => $vars['username'],
            'email'         => $vars['email'],
            'password'      => $vars['password'],
            'country_id'    => $vars['country_id'],
            'status'        => 'active',
            'user_type'     => @$userType->id
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

        $text_psw    = $vars['password'];
        $credentials['password'] = Hash::make($credentials['password']);
        $the_plan = MembershipPlan::where('id','=',$vars['membership_plan'])->where('id','!=',1)->where('status','=','active')->get()->first();

        try {
            $user = User::create($credentials);

            $personalData = [
                'personal_email'=> $vars['email'],
                'mobile_number' => $vars['phone_number'],
                'date_of_birth' => $vars['date_of_birth'],
                'bank_acc_no'   => 0,
                'social_sec_no' => 0,
                'about_info'    => '',
                'user_id'       => $user->id
            ];
            $personalDetails = PersonalDetail::firstOrNew(['user_id'=>$user->id]);
            $personalDetails->fill($personalData);
            $personalDetails->save();

            $beautymail = app()->make(Beautymail::class);
            $beautymail->send('emails.new_user_registration',
                ['user'=>$user, 'personal_details'=>$personalDetails, 'raw_password' => $text_psw, 'logo' => ['path' => 'http://sqf.se/wp-content/uploads/2012/12/sqf-logo.png']],
                function($message) use ($user){
                    $message
                        ->from(Config::get('constants.globalWebsite.system_email'))
                        ->to($user->email, $user->first_name.' '.$user->middle_name.' '.$user->last_name)
                        //->to('stefan.bogdan@ymail.com', $user->first_name.' '.$user->middle_name.' '.$user->last_name)
                        ->subject('Booking System - You are registered!');
                });

            $addressFill = [
                'user_id'       => $user->id,
                'address1'      => $vars['address1'],
                'address2'      => $vars['address2'],
                'city'          => $vars['city'],
                'region'        => $vars['region'],
                'postal_code'   => $vars['postal_code'],
                'country_id'    => $vars['adr_country_id'],
            ];
            $validator = Validator::make($addressFill, [
                'address1'    => 'required|min:5|max:150',
                'city'        => 'required|min:3|max:150',
                'region'      => 'required|min:2',
                'postal_code' => 'required|min:2',
                'country_id'  => 'required|exists:countries,id',
            ]);
            if ($validator->fails()){ }
            else {
                $personalAddress = new Address();
                $personalAddress->fill($addressFill);
                $personalAddress->save();

                $personalDetails->address_id = $personalAddress->id;
                $personalDetails->save();
            }

            // if membership plan selected
            if ($the_plan && isset($by_user)) {
                try{
                    $the_date = Carbon::createFromFormat('d-m-Y', $vars['start_date'])->format('Y-m-d');
                }
                catch (\Exception $ex){
                    $the_date = Carbon::today()->format('Y-m-d');
                }

                // add the membership plan to the new registered user
                if ($user->attach_membership_plan($the_plan, $by_user, $the_date)) {
                    $memberRole = Role::where('name', '=', 'front-member')->get()->first();

                    @$user->detachAllRoles();
                    $user->attachRole($memberRole);
                } else {
                    $user->attachRole($userType);
                }
            }
            else{
                $user->attachRole($userType);
            }

            return [
                'success'       => true,
                'title'         => 'New member registered',
                'message'       => 'You registered a new member : '.$user->first_name.' '.$user->last_name,
                'member_id'     => $user->id,
                'member_name'   => $user->first_name.' '.$user->last_name,
            ];
        }
        catch (Exception $e) {
            return [
                'success'   => false,
                'title'     => 'User already exists',
                'errors'    => 'User already exists and could not be registered.'
            ];
        }
    }

    public function register_new_client($client_vars){
        //$client_vars = ['first_name', 'middle_name', 'last_name', 'email', 'phone_number', 'password', 'membership_plan', 'username', 'user_type', 'country_id'];
        if (!isset($client_vars['middle_name'])){
            $client_vars['middle_name'] = '';
        }

        if (!isset($client_vars['username']) || $client_vars['username']==''){
            $client_vars['username'] = $client_vars['email'];
        }

        if (!isset($client_vars['user_type'])){
            $client_vars['user_type'] = Role::where('name','=','front-user')->get()->first()->id;
        }

        if ($client_vars['password']==""){
            $client_vars['password'] = substr(bcrypt(str_random(12)),0,8);
        }

        $validator = Validator::make($client_vars, User::rules('POST'), User::$messages, User::$attributeNames);

        if ($validator->fails()){
            //return $validator->errors()->all();
            return [
                'success'   => false,
                'errors'    => $validator->getMessageBag()->toArray()
            ];
        }

        $credentials = $client_vars;
        $text_psw    = $client_vars['password'];
        $credentials['password'] = Hash::make($credentials['password']);

        try {
            $user = User::create($credentials);
            $user->attachRole($client_vars['user_type']);

            if (isset($client_vars['phone_number']) && strlen($client_vars['phone_number'])>4){
                $personalData = [
                    'personal_email'=> $client_vars['email'],
                    'date_of_birth' => $client_vars['date_of_birth'],
                    'mobile_number' => $client_vars['phone_number'],
                    'bank_acc_no'   => 0,
                    'social_sec_no' => 0,
                    'about_info'    => $client_vars['about_info'],
                    'user_id'       => $user->id
                ];
                $personalDetails = PersonalDetail::firstOrNew(['user_id'=>$user->id]);
                $personalData['date_of_birth'] = $client_vars['date_of_birth']!=''?$client_vars['date_of_birth']:Carbon::today()->toDateString();
                $personalDetails->fill($personalData);
                $personalDetails->save();
            }

            return [
                'success'   => true,
                'password'  => $text_psw,
                'user'      => $user
            ];
        }
        catch (Exception $e) {
            return [
                'success'   => false,
                'error'     => 'User already exists.'
            ];
        }
    }

    public function validate_phone_for_member(Request $request){
        $vars = $request->only('phone');
        $user = PersonalDetail::where('mobile_number','=',$vars['phone'])->get()->first();
        if ($user){
            return 'false';
        }
        else{
            return 'true';
        }
    }

    public function validate_email_for_member(Request $request){
        $vars = $request->only('email');
        $user = User::where('email','=',$vars['email'])->get()->first();
        if ($user){
            return 'false';
        }
        else{
            return 'true';
        }
    }

    public function update_general_settings(Request $request, $id = -1){
        if (!Auth::check()) {
            return [
                'success'=>false,
                'title'=>'An error occurred',
                'errors'=> 'You need to be logged in to have access to this function'
            ];
        }
        else{
            $user = Auth::user();
        }

        $vars = $request->only('general_settings');
        if (sizeof($vars['general_settings'])>0){
            foreach($vars['general_settings'] as $key=>$val){
                $fillable = [
                    'user_id'   => $user->id,
                    'var_name'  => $key
                ];

                $storeSetting = UserSettings::firstOrNew($fillable);
                $storeSetting->var_value = $val;
                $storeSetting->save();
            }

            return [
                'success' => true,
                'title'   => 'Settings saved',
                'message' => 'Settings saved and in effect'
            ];
        }
        else{
            return [
                'success' => false,
                'title'   => 'An error occurred',
                'errors'  => 'Saved settings could not be stored.'
            ];
        }
    }

    public function import_from_file(Request $request){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }
        elseif (!$user->can('manage-calendar-products')){
            return redirect()->intended(route('admin/error/permission_denied'));
        }

        $vars = $request->only('_token','_method','start_date','membership_type');

        $allRows = [];
        $members = [];
        $nr = 1;
        $selectMembership = 1;
        $date_start = '';
        $returnMessages = [];
        $memberships = MembershipPlan::orderBy('name','ASC')->get();

        if ($request->hasFile('clients_import_list')){
            if ($request->file('clients_import_list')->isValid()){
                // upload file to storage
                $file = $request->file('clients_import_list');
                $extension = $file->getClientOriginalExtension();
                $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME).'-'.time().'-'.rand(100,999);
                Storage::disk('local')->put('temp/'.$name.'.'.$extension,  File::get($file));

                // get file from storage
                $empty_lines = 0;
                //Storage::disk('local')->get('temp/'.$name.'.'.$extension);

                $formattedList = Excel::load('storage/app/temp/'.$name.'.'.$extension)->get();
                foreach ($formattedList->toArray() as $row) {
                    $singleRow = [];
                    $nr = 1;
                    $chars = 0;

                    foreach($row as $vals){
                        $singleRow[$nr++] = $vals;
                    }

                    $allRows[] = $singleRow;
                    if ($chars>0){

                    }
                    else{
                        $empty_lines++;
                    }

                    if ($empty_lines>5){
                        break;
                    }
                }
                //xdebug_var_dump($allRows);
                $selectMembership = $vars['membership_type'];
                $date_start = $vars['start_date'];
            }
            else{
                // file upload error
            }
        }
        elseif(isset($vars['_method']) && $vars['_method']=='PUT') {
            $vars = $request->toArray();
            try{
                $the_date = Carbon::createFromFormat('d-m-Y', $vars['date_start'])->format('Y-m-d');
            }
            catch (\Exception $ex){
                $the_date = Carbon::today()->format('Y-m-d');
            }

            $fname      = 999;
            $mname      = 999;
            $lname      = 999;
            $phone      = 999;
            $email      = 999;
            $passwd     = 999;
            $uname      = 999;
            $utype      = 999;
            $dob        = 999;
            $uinfo      = 999;
            $uaddress   = 999;
            $upostalcode= 999;
            $ucity      = 999;

            // get how lines are ordered
            foreach($vars['col_explain'] as $key=>$val){
                switch($val){
                    case 'first_name' :
                        $fname = $key;
                        break;
                    case 'middle_name' :
                        $mname = $key;
                        break;
                    case 'last_name' :
                        $lname = $key;
                        break;
                    case 'phone' :
                        $phone = $key;
                        break;
                    case 'email' :
                        $email = $key;
                        break;
                    case 'password' :
                        $passwd = $key;
                        break;
                    case 'username' :
                        $uname = $key;
                        break;
                    case 'user_type' :
                        $utype = $key;
                        break;
                    case 'dob' :
                        $dob = $key;
                        break;
                    case 'about_info' :
                        $uinfo = $key;
                        break;
                    case 'street' :
                        $uaddress = $key;
                        break;
                    case 'postal_code' :
                        $upostalcode = $key;
                        break;
                    case 'city' :
                        $ucity = $key;
                        break;
                }
            }

            // create $users array of imported value
            for ($i=0; $i<=$vars['key_return']; $i++){
                if (!isset($vars['line_'.$i])){
                    continue;
                }

                try{
                    $dob = Carbon::createFromFormat('Y-m-d H:i:s', @$vars['col_'.$i][$dob])->format('Y-m-d');
                }
                catch(\Exception $ex){
                    $dob = Carbon::today()->format('Y-m-d');
                }

                $importUser = [
                    'first_name'        => @$vars['col_'.$i][$fname],
                    'middle_name'       => @$vars['col_'.$i][$mname],
                    'last_name'         => @$vars['col_'.$i][$lname],
                    'email'             => @$vars['col_'.$i][$email],
                    'country_id'        => Config::get('constants.globalWebsite.defaultCountryId'),
                    'phone_number'      => @$vars['col_'.$i][$phone],
                    'password'          => strlen(@$vars['col_'.$i][$passwd])>7?@$vars['col_'.$i][$passwd]:@$vars['col_'.$i][$phone],
                    'membership_plan'   => @$vars['membership_'.$i],
                    'username'          => @$vars['col_'.$i][$uname],
                    'user_type'         => @$vars['col_'.$i][$utype],
                    'date_of_birth'     => $dob,
                    'about_info'        => isset($vars['col_'.$i][$uinfo])?$vars['col_'.$i][$uinfo]:'',
                    'address1'          => @$vars['col_'.$i][$uaddress],
                    'postal_code'       => @$vars['col_'.$i][$upostalcode],
                    'city'              => @$vars['col_'.$i][$ucity],
                ];
                $members[] = $importUser;
            }
            $membershipPlans = MembershipPlan::get();
            $arrayPlan = [];
            foreach($membershipPlans as $plan){
                $arrayPlan[$plan->id] = [
                    'id'    => $plan->id,
                    'name'  => $plan->name,
                    'full_plan' => $plan
                ];
            }

            foreach ($members as $member){
                // add member
                $newUser = FrontEndUserController::register_new_client($member);

                if ($newUser['success']==false){
                    $msg = 'Following errors occurred : <br />';
                    foreach ($newUser['errors'] as $key=>$error){
                        $msg.= $key.' : '.implode(', ',$error).'; <br />';
                    }
                }
                else{
                    $new_member = $newUser['user'];
                    $msg = 'User added successfully : <br />';

                    if (strlen($member['address1'])>1 && strlen($member['postal_code'])>1 && strlen($member['city'])>1 ){
                        // we add the address
                        $userPersonal = PersonalDetail::where('user_id','=',$new_member->id)->get()->first();

                        $vars = [
                            'address1'      => $member['address1'],
                            'address2'      => isset($member['address2'])?$member['address2']:'',
                            'city'          => $member['city'],
                            'country_id'    => Config::get('constants.globalWebsite.defaultCountryId'),
                            'postal_code'   => $member['postal_code'],
                            'region'        => $member['city'],
                        ];
                        $validator = Validator::make($vars, [
                            'address1'    => 'required|min:5|max:150',
                            'city'        => 'required|min:3|max:150',
                            'region'      => 'required|min:2',
                            'postal_code' => 'required|min:2',
                            'country_id'  => 'required|exists:countries,id',
                        ]);

                        if ($validator->fails()){
                            $valErr = $validator->getMessageBag()->toArray();
                            $msg.= 'Error adding address : ';
                            foreach ($valErr as $key=>$error){
                                $msg.= $key.' : '.implode(', ',$error).'; ';
                            }
                            $msg.='<br /> ';
                        }
                        else{
                            $personalAddress = new Address();
                            $personalAddress->fill([
                                'user_id'       => $new_member->id,
                                'address1'      => $vars['address1'],
                                'address2'      => $vars['address2'],
                                'city'          => $vars['city'],
                                'region'        => $vars['region'],
                                'postal_code'   => $vars['postal_code'],
                                'country_id'    => $vars['country_id'],
                            ]);
                            $personalAddress->save();

                            $userPersonal->address_id = $personalAddress->id;
                            $userPersonal->save();

                            $msg.= 'User address added successfully : '.implode(',',$vars).' <br />';
                        }
                    }

                    // assign membership
                    // Default free plan is the first plan
                    if (strlen($member['membership_plan'])>1 && isset($arrayPlan[$member['membership_plan']])){
                        // we have a plan and we apply it
                        //xdebug_var_dump($new_member);
                        $new_member->attach_membership_plan($arrayPlan[$member['membership_plan']]['full_plan'], $user, $the_date);

                        $msg.= 'Membership plan assigned : '.$arrayPlan[$member['membership_plan']]['full_plan']->name.' <br />';
                    }
                }

                $returnMessages[] = [
                    'inputData' => $member,
                    'returnMsg' => $msg,
                    'userID'    => isset($new_member->id)?$new_member->id:-1
                ];
            }
            //xdebug_var_dump($returnMessages); exit;
        }

        $text_parts  = [
            'title'     => 'Back-End Users',
            'subtitle'  => 'view all users',
            'table_head_text1' => 'Backend User List'
        ];
        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End Users'    => route('admin/back_users'),
        ];
        $sidebar_link= 'admin-frontend-import_members';

        return view('admin/front_users/import_from_file', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'importedRows'=> $allRows,
            'per_line'    => $nr,
            'memberships' => $memberships,
            'selectedMembership'=> $selectMembership,
            'date_start'        => $date_start,
            'importedMembers'   => $members,
            'returnMessages'    => $returnMessages
        ]);
    }

    /* Front end pages part - START */

    public function member_friends_list($userID = -1){
        $user = Auth::user();
        if (!$user || !$user->is_front_user()) {
            return redirect()->intended(route('homepage'));
        }

        if ($userID == -1){
            $userID = $user->id;
        }
        else{
            // check if the logged in user is an employee
        }

        $friends_list = [];
        $locations = [];
        $friends = DB::table('user_friends')
            ->where('user_id','=',$userID)
            ->orWhere('friend_id','=',$userID)
            ->get();
        if ($friends){
            // get list of locations to use for preferred one
            $shopLocations = ShopLocations::where('visibility','=','public')->get();

            foreach($shopLocations as $location){
                $locations[$location->id] = $location->name;
            }

            foreach($friends as $friend){
                if ($userID == $friend->user_id){
                    $friendID = $friend->friend_id;
                }
                else{
                    $friendID = $friend->user_id;
                }

                $since = Carbon::createFromFormat('Y-m-d H:i:s', $friend->created_at)->format('M j Y');
                $userFriend = User::with('PersonalDetail')->where('id','=',$friendID)->get()->first();
                $generalSettings = UserSettings::get_general_settings($userFriend->id, ['settings_preferred_location']);

                $friends_list[] = [
                    'full_name'     => $userFriend->first_name.' '.$userFriend->middle_name.' '.$userFriend->last_name,
                    'email_address' => isset($userFriend['PersonalDetail']->personal_email)?$userFriend['PersonalDetail']->personal_email:'-',
                    'phone_number'  => isset($userFriend['PersonalDetail']->mobile_number)?$userFriend['PersonalDetail']->mobile_number:'-',
                    'preferred_gym' => isset($generalSettings['settings_preferred_location'])?$generalSettings['settings_preferred_location']:'-',
                    'ref_nr'        => $friend->id,
                    'since'         => $since
                ];
            }
        }

        $breadcrumbs = [
            'Home'      => route('admin'),
            'Dashboard' => '',
        ];
        $text_parts  = [
            'title'     => 'Home',
            'subtitle'  => 'users dashboard',
            'table_head_text1' => 'Dashboard Summary'
        ];
        $sidebar_link= 'front-friends_list';

        return view('front/user_friends/friends_list',[
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'user'  => $user,
            'list_of_friends' => $friends_list,
            'locations' => $locations
        ]);
    }

    public function front_invoice_list(){
        $user = Auth::user();
        if (!$user || !$user->is_front_user()) {
            return redirect()->intended(route('homepage'));
        }

        $breadcrumbs = [
            'Home'      => route('admin'),
            'Dashboard' => '',
        ];
        $text_parts  = [
            'title'     => 'Home',
            'subtitle'  => 'users dashboard',
            'table_head_text1' => 'Dashboard Summary'
        ];
        $sidebar_link= 'front-finance_invoice_list';

        return view('front/finance/invoice_list',[
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'user'  => $user,
        ]);
    }

    public function front_show_invoice($id)
    {
        $user = Auth::user();
        if (!$user || !$user->is_front_user()) {
            return redirect()->intended(route('homepage'));
        }

        $invoice = Invoice::where('invoice_number','=',$id)->where('user_id','=',$user->id)->get()->first();
        if ($invoice){
            $subtotal = 0;
            $total = 0;
            $discount = 0;
            $vat = [];

            $items = InvoiceItem::where('invoice_id','=',$invoice->id)->get();
            foreach($items as $item){
                // base price minus the discount
                $item_one_price = $item->price - (($item->price*$item->discount)/100);
                // apply the vat to the price
                $item_vat = $item_one_price * ($item->vat/100);

                if (isset($vat[$item->vat])){
                    $vat[$item->vat]+= $item_vat*$item->quantity;
                }
                else{
                    $vat[$item->vat] = $item_vat*$item->quantity;
                }

                $discount+= (($item->price*$item->discount)/100)*$item->quantity;
                $subtotal+= $item->price * $item->quantity;

                $total+= ($item_one_price + $item_vat)*$item->quantity;
            }

            $member = User::with('ProfessionalDetail')->with('PersonalDetail')->where('id','=',$invoice->user_id)->get()->first();
            $member_professional = $member->ProfessionalDetail;
            $member_personal = $member->PersonalDetail;
            //xdebug_var_dump($member_professional);
            //xdebug_var_dump($member_personal);
            //xdebug_var_dump($member);

            if ($member->country_id==0){
                $country = '-';
            }
            else{
                $get_country = Countries::where('id','=',$member->country_id)->get()->first();
                $country = $get_country->name;
            }

            $invoice_user = [
                'full_name' => $member->first_name.' '.$member->middle_name.' '.$member->last_name,
                'email_address' => $member->email,
                'date_of_birth' => $member_personal->date_of_birth,
                'country'   => $country,
            ];
        }

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
        $sidebar_link= 'admin-backend-shop-new_order';

        return view('front/finance/show_invoice', [
            'breadcrumbs'   => $breadcrumbs,
            'text_parts'    => $text_parts,
            'in_sidebar'    => $sidebar_link,
            'invoice'   => $invoice,
            'invoice_items' => @$items,
            'member'    => @$invoice_user,
            'sub_total' => $subtotal,
            'discount'  => $discount,
            'vat'       => $vat,
            'grand_total'   => $total
        ]);
    }

    /**
     * Get all bookings for specific user with status different than expired
     * @param int $userID
     * @return array
     */
    public function get_user_invoice_list($userID = -1){
        if (Auth::check()) {
            $user = Auth::user();
        }
        else{
            return [];
        }

        if ($userID == -1){
            $userID = $user->id;
        }

        $invoicesList = [];
        $generalInvoices = Invoice::where('user_id','=',$userID)->where('status','!=','cancelled')->orderBy('created_at','desc')->get();
        if (sizeof($generalInvoices)>0){
            $buttons = [];
            $colorStatus = '';
            foreach ($generalInvoices as $single_invoice){
                switch ($single_invoice->status) {
                    case 'pending':
                        $colorStatus = 'warning';
                        $buttons = 'yellow-gold';
                        $explained = 'Not Paid';
                        break;
                    case 'ordered':
                    case 'processing':
                        $colorStatus = 'info';
                        $buttons = 'green-meadow';
                        $explained = 'Processing';
                        break;
                    case 'completed':
                        $colorStatus = 'success';
                        $buttons = 'green-jungle';
                        $explained = 'Paid';
                        break;
                    case 'cancelled':
                        $colorStatus = 'warning';
                        $buttons = 'yellow-lemon';
                        $explained = 'Cancelled';
                        break;
                    case 'declined':
                    case 'incomplete':
                        $colorStatus = 'danger';
                        $buttons = 'red-thunderbird';
                        $explained = 'Declined';
                        break;
                    case 'preordered':
                        $colorStatus = 'info';
                        $buttons = 'green-meadow';
                        $explained = '';
                        break;
                    default:
                        $explained = 'Unknown';
                        break;
                }

                $price = 0;
                $items = 0;
                $display_name = '-';
                $invoiceItems = InvoiceItem::where('invoice_id','=',$single_invoice->id)->get();
                if ($invoiceItems){
                    foreach ($invoiceItems as $invItem){
                        $price+=$invItem->total_price;
                        $items++;

                        if ($display_name=='-' && $invItem->item_type=='user_memberships'){
                            $display_name = 'Membership - '.$invItem->item_name;
                        }
                        elseif ($display_name=='-' && $invItem->item_type=='booking_invoice_item'){
                            $bookingItem = BookingInvoiceItem::where('id','=',$invItem->item_reference_id)->get()->first();
                            $display_name = 'Booking - '.$bookingItem->location_name;
                        }
                    }
                }

                $addedOn    = Carbon::createFromFormat('Y-m-d H:i:s', $single_invoice->created_at)->format('j/m/Y');
                $invoicesList[] = [
                    '<div class="'.$colorStatus.'"></div><a href="javascript:;"> #'.$single_invoice->invoice_number.' </a>',
                    $display_name,
                    $items,
                    $price,
                    $addedOn,
                    $explained,
                    '<a class="btn '.$buttons.' btn-sm invoice_details_modal" href="'.route('front/view_invoice/', ['id'=>$single_invoice->invoice_number]).'"> <i class="fa fa-edit"></i> Details </a>',
                ];
            }
        }

        $bookings = [
            "data" => $invoicesList
        ];

        return $bookings;
    }

    public function type_of_memberships(){
        $breadcrumbs = [
            'Home'      => route('admin'),
            'Dashboard' => '',
        ];
        $text_parts  = [
            'title'     => 'Home',
            'subtitle'  => 'users dashboard',
            'table_head_text1' => 'Dashboard Summary'
        ];
        $sidebar_link= 'front-type_of_memberships';

        return view('front/type_of_memberships',[
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
        ]);
    }

    public function contact_locations(){
        $breadcrumbs = [
            'Home'      => route('admin'),
            'Dashboard' => '',
        ];
        $text_parts  = [
            'title'     => 'Home',
            'subtitle'  => 'users dashboard',
            'table_head_text1' => 'Dashboard Summary'
        ];
        $sidebar_link= 'front-contact_or_locations';

        return view('front/contact_locations',[
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
        ]);
    }

    public function member_active_membership(){
        $user = Auth::user();
        if (!$user || !$user->is_front_user()) {
            return redirect()->intended(route('homepage'));
        }

        $my_plan = UserMembership::where('user_id','=',$user->id)->whereIn('status',['active','suspended'])->get()->first();
        if ($my_plan){
            $restrictions = $my_plan->get_plan_restrictions();
            $plan_details = $my_plan->get_plan_details();
        }
        else {
            //$my_plan = MembershipPlan::where('id','=',1)->get()->first();
        }

        $breadcrumbs = [
            'Home'      => route('admin'),
            'Dashboard' => '',
        ];
        $text_parts  = [
            'title'     => 'Home',
            'subtitle'  => 'users dashboard',
            'table_head_text1' => 'Dashboard Summary'
        ];
        $sidebar_link= 'front-finance_active_membership';

        return view('front/finance/active_membership',[
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'membership_plan'   => $my_plan,
            //'activities'    => $activities,
            'restrictions'  => @$restrictions,
            'plan_details'  => @$plan_details,
            'user'          => $user
        ]);
    }

    public function settings_account(){
        $user = Auth::user();
        if (!$user || !$user->is_front_user()) {
            return redirect()->intended(route('homepage'));
        }

        $locations  = ShopLocations::where('visibility','=','public')->orderBy('name')->get();
        $activities = ShopResourceCategory::orderBy('name')->get();
        $settings   = UserSettings::get_general_settings($user->id, ['settings_preferred_location','settings_preferred_activity']);

        $breadcrumbs = [
            'Home'      => route('admin'),
            'Dashboard' => '',
        ];
        $text_parts  = [
            'title'     => 'Home',
            'subtitle'  => 'users dashboard',
            'table_head_text1' => 'Dashboard Summary'
        ];
        $sidebar_link= 'front-settings_account';

        return view('front/settings/account',[
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'locations'     => $locations,
            'activities'    => $activities,
            'user'          => $user,
            'settings'      => $settings
        ]);
    }

    public function settings_personal(){
        $user = Auth::user();
        if (!$user || !$user->is_front_user()) {
            return redirect()->intended(route('homepage'));
        }

        $userProfessional = $user->ProfessionalDetail;
        if (!isset($userProfessional)){
            $userProfessional = new ProfessionalDetail();
        }

        $userPersonal = $user->PersonalDetail;
        if (isset($userPersonal)) {
            $userPersonal->dob_format = Carbon::createFromFormat('Y-m-d', $userPersonal->date_of_birth)->format('d-m-Y');
            $userPersonal->dob_to_show = Carbon::createFromFormat('Y-m-d', $userPersonal->date_of_birth)->format('d M Y');
        }
        else{
            $userPersonal = new PersonalDetail();
        }

        /*$personalAddress = Address::find($userPersonal->address_id);
        if (!isset($personalAddress)){
            $personalAddress = new Address();
        }*/

        $countries = Countries::orderBy('name')->get();
        $userCountry = Countries::find($user->country_id);

        $avatar = $user->get_avatar_image();

        $userMembership = UserMembership::where('user_id','=',$user->id)->get()->first();
        if ($userMembership){
            $membershipName = $userMembership->membership_name;
        }
        else{
            $membershipName = 'No Membership Plan';
        }

        $old_bookings = Booking::select('id')->where('for_user_id','=',$user->id)->whereIn('status',['paid','unpaid','old','no_show'])->count();
        $new_bookings = Booking::select('id')->where('for_user_id','=',$user->id)->whereIn('status',['active'])->count();
        $cancelled_bookings = Booking::select('id')->where('for_user_id','=',$user->id)->whereIn('status',['canceled'])->count();
        $own_friends  = UserFriends::select('id')->where('user_id','=',$user->id)->orWhere('friend_id','=',$user->id)->count();
        //$own_logins   = ;

        $breadcrumbs = [
            'Home'      => route('admin'),
            'Dashboard' => '',
        ];
        $text_parts  = [
            'title'     => 'Home',
            'subtitle'  => 'users dashboard',
            'table_head_text1' => 'Dashboard Summary'
        ];
        $sidebar_link= 'front-settings_personal';

        return view('front/settings/personal',[
            'breadcrumbs'   => $breadcrumbs,
            'text_parts'    => $text_parts,
            'in_sidebar'    => $sidebar_link,
            'user'          => $user,
            'countries'     => $countries,
            'personal'      => $userPersonal,
            'avatar'        => $avatar['avatar_base64'],
            'membershipName'=> $membershipName,
            'countOldBookings'    => $old_bookings,
            'countCancelledBookings'    => $cancelled_bookings,
            'countActiveBookings' => $new_bookings,
            'countFriends'        => $own_friends
        ]);
    }

    public function settings_personal_info(Request $request){
        if (Auth::check()) {
            $user = Auth::user();
        }
        else{
            return [
                'success' => false,
                'title'   => 'Authentication Error',
                'errors'  => 'You need to be logged in to access this function. Please login...'
            ];
        }

        return $this->update_personal_info($request, $user->id);
    }

    public function settings_personal_avatar(Request $request){
        $user = Auth::user();
        if (!$user || $user->is_back_user()) {
            return [
                'success' => false,
                'title'   => 'Authentication Error',
                'errors'  => 'You need to be logged in to access this function. Please login...'
            ];
        }

        $avatarLocation = 'members/'.$user->id.'/avatars/';
        $avatarFilename = $user->username.'.'.$request->file('user_avatar')->getClientOriginalExtension();
        $exists = Storage::disk('local')->exists($avatarLocation . $avatarFilename);
        if ($exists){
            $old_avatar_name = time().'-'.$avatarFilename.'.old';
            Storage::disk('local')->move( $avatarLocation . $avatarFilename, $avatarLocation . $old_avatar_name);
        }

        $avatarData = [
            'user_id'   => $user->id,
            'file_name' => $avatarFilename,
            'file_location' => $avatarLocation,
            'width' => 0,
            'height'=> 0
        ];

        $avatar = UserAvatars::find(['user_id' => $user->id])->first();
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
        return redirect()->intended(route('settings/personal'));
    }

    public function settings_personal_update_password(Request $request){
        if (!Auth::check()) {
            return [
                'success' => false,
                'title'   => 'Authentication Error',
                'errors'  => 'Please reload the page and try again'
            ];
        }
        else{
            $user = Auth::user();
        }

        return $this->updatePassword($request, $user->id);
    }

    public function front_view_all_messages(){
        $user = Auth::user();
        if (!$user || !$user->is_front_user()) {
            return redirect()->intended(route('homepage'));
        }

        $allNotes = $user->get_public_notes('DESC','all',false);
        $avatar = $user->get_avatar_image();

        $breadcrumbs = [
            'Home'      => route('admin'),
            'Dashboard' => '',
        ];
        $text_parts  = [
            'title'     => 'Home',
            'subtitle'  => 'users dashboard',
            'table_head_text1' => 'Dashboard Summary'
        ];
        $sidebar_link= 'front-settings_personal';

        return view('front/settings/all_messages',[
            'breadcrumbs'   => $breadcrumbs,
            'text_parts'    => $text_parts,
            'in_sidebar'    => $sidebar_link,
            'user'          => $user,
            'avatar'        => $avatar['avatar_base64'],
            'notes'         => $allNotes
        ]);
    }
    /* Front end pages part - STOP */

    /* Reset Password Functions - START */
    public function password_reset_form(Request $request, $token){
        $breadcrumbs = [
            'Home'      => route('admin'),
            'Dashboard' => '',
        ];
        $text_parts  = [
            'title'     => 'Home',
            'subtitle'  => 'users dashboard',
            'table_head_text1' => 'Dashboard Summary'
        ];
        $sidebar_link= 'front-password_reset';

        return view('front/password_reset/reset_password',[
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'token'       => $token
        ]);
    }

    public function password_reset_action(Request $request, $token){
        $this->check_expired_password_requests();

        $vars = $request->only('token', 'email', 'password1', 'password2');
        $validator = Validator::make($vars, [
            'email' => 'required|email',
            'token' => 'required|min:32',
            'password1'     => 'required|min:8',
            'password2'     => 'required|min:8|same:password1',

        ]);

        if ($validator->fails()){
            return [
                'success'   => false,
                'errors'    => 'The email or passwords that you entered are not valid Please try again.',
                'title'     => 'Email/Password validation failed'
            ];
        }

        // check token with the email received
        $db_token = DB::select('select email from password_resets where email=:email and token=:token limit 1', ['email' => $vars['email'], 'token' => $vars['token']]);
        if (sizeof($db_token)<1){
            return [
                'success'   => false,
                'errors'    => 'Invalid token / email combination or link expired. Remember this link is available 60 minutes from the moment of request. If you need to reset password again, make a new request.',
                'title'     => 'Invalid token / email combination'
            ];
        }

        $user = User::where('email','=',$vars['email'])->get()->first();
        if (!$user){
            return [
                'success'   => false,
                'errors'    => 'Email not fount in users list. Contact administrators about this issue.',
                'title'     => 'Invalid email provided'
            ];
        }

        $user->password = bcrypt($vars['password1']);
        $user->save();
        DB::delete('delete from password_resets where email=:email and token=:token limit 1', ['email' => $vars['email'], 'token' => $vars['token']]);

        $beauty_mail = app()->make(Beautymail::class);
        $beauty_mail->send('emails.user.password_changed',
            ['user'=>$user, 'logo' => ['path' => 'http://sqf.se/wp-content/uploads/2012/12/sqf-logo.png']],
            function($message) use ($user) {
                $message
                    ->from(Config::get('constants.globalWebsite.system_email'))
                    ->to($user->email, $user->first_name.' '.$user->middle_name.' '.$user->last_name)
                    //->to('stefan.bogdan@ymail.com', $player->first_name.' '.$player->middle_name.' '.$player->last_name)
                    ->subject('Booking System - Password successfully changed');
            });

        return [
            'success'   => true,
            'message'   => 'Your password was successfully changed with the new one. Go to homepage and login',
            'title'     => 'Password Changed'
        ];
    }

    public function password_reset_request(Request $request){
        $vars = $request->only('email');

        $validator = Validator::make($vars, [
            'email' => 'required|email',
        ]);
        if ($validator->fails()){
            return [
                'success'   => false,
                'errors'    => 'Invalid email used in password reset function',
                'title'     => 'Invalid email'
            ];
        }

        $user = User::where('email','=',$vars['email'])->get()->first();
        if ($user){
            $generateKey = $this->createNewToken();
            $oldKey = DB::select('select * from password_resets where email = :email limit 1', ['email'=>$user->email]);
            if (sizeof($oldKey)>0){
                // we have old key so we delete it then insert the new key
                DB::delete('delete from password_resets where email = :email limit 1', ['email'=>$user->email]);
            }

            DB::insert('insert into password_resets (email, token, created_at) values (:email, :key, :now_time)', ['email'=>$user->email, 'key'=>$generateKey, 'now_time'=>Carbon::now()]);

            $beauty_mail = app()->make(Beautymail::class);
            $beauty_mail->send('emails.user.password_reset_link',
                ['user'=>$user, 'token'=>$generateKey, 'logo' => ['path' => 'http://sqf.se/wp-content/uploads/2012/12/sqf-logo.png']],
                function($message) use ($user) {
                    $message
                        ->from(Config::get('constants.globalWebsite.system_email'))
                        ->to($user->email, $user->first_name.' '.$user->middle_name.' '.$user->last_name)
                        //->to('stefan.bogdan@ymail.com', $player->first_name.' '.$player->middle_name.' '.$player->last_name)
                        ->subject('Booking System - Password reset request');
                });
        }

        return [
            'success'   => true,
            'title'     => 'Password reset action',
            'message'   => 'If the email is registered in our system, an email will be sent in the next minute with the steps to reset your email'
        ];
    }

    private function createNewToken()
    {
        return hash_hmac('sha256', Str::random(40), \Config::get('app.key'));
    }

    private function check_expired_password_requests($minutes = 60){
        $expire_date = Carbon::now()->subMinutes($minutes);

        return DB::delete('delete from password_resets where created_at < :date_time', ['date_time'=>$expire_date]);
    }
    /* Reset Password functions - STOP */

    public function change_account_status(Request $request){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return [
                'success' => false,
                'title'   => 'You need to be logged in',
                'errors'  => 'You need to be logged in as an employee in order to use this function'];
        }

        $vars = $request->only('memberID','custom_message');
        $member = User::where('id','=',$vars['memberID'])->get()->first();
        if (!$member){
            return [
                'success' => false,
                'title'   => 'Member not found',
                'errors'  => 'The member you want to suspend/reactivate was not found in the system'];
        }

        $oldStatus = $member->status;
        if ($member->status == 'active'){
            $title = 'Member status updated to "SUSPENDED"';
            $newStatus = 'suspended';
        }
        else{
            $title = 'Member status updated to "ACTIVE"';
            $newStatus = 'active';
        }

        $note_fill = [
            'by_user_id'    => $user->id,
            'for_user_id'   => $member->id,
            'note_title'    => $title,
            'note_body'     => '',
            'note_type'     => 'member status update',
            'privacy'       => '',
            'status'        => 'unread'];
        if (strlen($vars['custom_message'])){
            $note_fill['note_body'] = $vars['custom_message'];
            $note_fill['privacy']   = 'employees';

            $validator = Validator::make($note_fill, GeneralNote::rules('POST'), GeneralNote::$message, GeneralNote::$attributeNames);
            if ($validator->fails()){
                // note could not be created
                return [
                    'success' => false,
                    'title'   => 'Could not add note',
                    'errors'  => 'There is something wrong with the validity of the message attached to this action!'];
            }
            else {
                $generalNote = GeneralNote::create($note_fill);
                $generalNote->save();

                $member->status = $newStatus;
                $member->save();

                return [
                    'success' => true,
                    'title'   => 'Member status updated',
                    'message'  => 'You have update this member status from '.$oldStatus.' to '.$newStatus.''];
            }
        }
        else{
            return [
                'success' => false,
                'title'   => 'Member not found',
                'errors'  => 'The member you want to suspend/reactivate was not found in the system'];
        }
    }

    public function update_access_card(Request $request){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return [
                'success' => false,
                'title'   => 'You need to be logged in',
                'errors'  => 'You need to be logged in as an employee in order to use this function'];
        }

        $vars = $request->only('memberID','card_value');
        $member = User::where('id','=',$vars['memberID'])->get()->first();
        if (!$member){
            return [
                'success' => false,
                'title'   => 'Member not found',
                'errors'  => 'The member you want to assign an access card was not found in the system'];
        }

        $access_card_fill = [
            'user_id'   => $member->id,
            'key_no'    => $vars['card_value'],
            'status'    => 'active',
        ];

        $validator = Validator::make($access_card_fill, UserAccessCard::rules('POST'), UserAccessCard::$message, UserAccessCard::$attributeNames);
        if ($validator->fails()){
            // note could not be created
            return [
                'success' => false,
                'title'   => 'Could not update card',
                'errors'  => 'There is something wrong with the validity of the access card you want to add!'];
        }
        else {
            $access_card = UserAccessCard::firstOrNew(['user_id'=>$member->id]);
            $access_card->fill($access_card_fill);
            $access_card->save();

            return [
                'success' => true,
                'title'   => 'Access card added',
                'message' => 'You have updated the access card number for this member.'];
        }
    }

    public function get_member_bookings_statistics(User $member, $simple=true){
        $stats = [
            'this_week' => [
                'ord'   => 1,
                'drop_ins'  => [
                    'nr'    => 0,
                    'money' => 0,
                    'rate'  => 0
                ],
                'membership'=> [
                    'nr'    => 0,
                    'money' => 0,
                    'rate'  => 0
                ],
                'active'    => [
                    'nr'    => 0,
                    'money' => 0,
                    'rate'  => 0
                ],
                'show'      => [
                    'nr'    => 0,
                    'money' => 0,
                    'rate'  => 0
                ],
                'no_show'   => [
                    'nr'    => 0,
                    'money' => 0,
                    'rate'  => 0
                ],
                'canceled' => [
                    'nr'    => 0,
                    'money' => 0,
                    'rate'  => 0
                ]
            ],
            'this_month'    => [
                'ord'   => 2,
                'drop_ins'  => [
                    'nr'    => 0,
                    'money' => 0,
                    'rate'  => 0
                ],
                'membership'=> [
                    'nr'    => 0,
                    'money' => 0,
                    'rate'  => 0
                ],
                'active'    => [
                    'nr'    => 0,
                    'money' => 0,
                    'rate'  => 0
                ],
                'show'      => [
                    'nr'    => 0,
                    'money' => 0,
                    'rate'  => 0
                ],
                'no_show'   => [
                    'nr'    => 0,
                    'money' => 0,
                    'rate'  => 0
                ],
                'canceled' => [
                    'nr'    => 0,
                    'money' => 0,
                    'rate'  => 0
                ]
            ],
            'last_3months'  => [
                'ord'   => 3,
                'drop_ins'  => [
                    'nr'    => 0,
                    'money' => 0,
                    'rate'  => 0
                ],
                'membership'=> [
                    'nr'    => 0,
                    'money' => 0,
                    'rate'  => 0
                ],
                'active'    => [
                    'nr'    => 0,
                    'money' => 0,
                    'rate'  => 0
                ],
                'show'      => [
                    'nr'    => 0,
                    'money' => 0,
                    'rate'  => 0
                ],
                'no_show'   => [
                    'nr'    => 0,
                    'money' => 0,
                    'rate'  => 0
                ],
                'canceled' => [
                    'nr'    => 0,
                    'money' => 0,
                    'rate'  => 0
                ]
            ],
            'last_all'  => [
                'ord'   => 3,
                'drop_ins'  => [
                    'nr'    => 0,
                    'money' => 0,
                    'rate'  => 0
                ],
                'membership'=> [
                    'nr'    => 0,
                    'money' => 0,
                    'rate'  => 0
                ],
                'active'    => [
                    'nr'    => 0,
                    'money' => 0,
                    'rate'  => 0
                ],
                'show'      => [
                    'nr'    => 0,
                    'money' => 0,
                    'rate'  => 0
                ],
                'no_show'   => [
                    'nr'    => 0,
                    'money' => 0,
                    'rate'  => 0
                ],
                'canceled' => [
                    'nr'    => 0,
                    'money' => 0,
                    'rate'  => 0
                ]
            ]
        ];

        $all_bookings = Booking::where('for_user_id','=',$member->id)->where('status','!=','expired')->where('status','!=','pending')->get();
        if ($all_bookings){
            foreach($all_bookings as $booking){
                $bookingDate = Carbon::createFromFormat('Y-m-d H:i:s', $booking->created_at);
                $status_for = ['last_all'];

                if ($bookingDate->between(Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek())){
                    // this week only
                    $status_for[] = 'this_week';
                }

                if ($bookingDate->between(Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth())){
                    // this month only
                    $status_for[] = 'this_month';
                }

                if($bookingDate->between(Carbon::now()->startOfMonth()->addMonths(-3), Carbon::now()->startOfMonth()->addDays(-1))){
                    // start of month three months ago until first of today's month minus one day
                    $status_for[] = 'last_3months';
                }

                foreach ($status_for as $single_status){
                    if ($booking->payment_type=='cash' || $booking->payment_type=='recurring'){
                        $stats[$single_status]['drop_ins']['nr']++;
                        $stats[$single_status]['drop_ins']['money']+=$booking->payment_amount;
                    }
                    elseif($booking->payment_type=='membership'){
                        $stats[$single_status]['membership']['nr']++;
                        $stats[$single_status]['membership']['money']+=0;
                    }

                    if ($booking->status=='active'){
                        $stats[$single_status]['active']['nr']++;
                        $stats[$single_status]['active']['money']+=     $booking=='membership'?0:$booking->payment_amount;
                    }
                    elseif($booking->status=='noshow'){
                        $stats[$single_status]['no_show']['nr']++;
                        $stats[$single_status]['no_show']['money']+=    $booking=='membership'?0:$booking->payment_amount;
                    }
                    elseif($booking->status=='canceled'){
                        $stats[$single_status]['canceled']['nr']++;
                        $stats[$single_status]['canceled']['money']+=   $booking=='membership'?0:$booking->payment_amount;
                    }
                    elseif($booking->status=='paid' || $booking->status=='unpaid' || $booking->status=='old'){
                        $stats[$single_status]['show']['nr']++;
                        $stats[$single_status]['show']['money']+=       $booking=='membership'?0:$booking->payment_amount;
                    }
                }
            }

            foreach ($stats as $key=>$stat){
                $total1 = $stat['drop_ins']['nr'] + $stat['membership']['nr'];
                $stats[$key]['drop_ins']['rate']   = $total1==0?'-':round(($stat['drop_ins']['nr']*100)/$total1);
                $stats[$key]['membership']['rate'] = $total1==0?'-':round(($stat['membership']['nr']*100)/$total1);

                $total2 = $stat['active']['nr'] + $stat['show']['nr'] + $stat['no_show']['nr'] + $stat['canceled']['nr'];
                $stats[$key]['active']['rate']      = $total2==0?'-':round(($stat['active']['nr']*100)/$total2);
                $stats[$key]['show']['rate']        = $total2==0?'-':round(($stat['show']['nr']*100)/$total2);;
                $stats[$key]['no_show']['rate']     = $total2==0?'-':round(($stat['no_show']['nr']*100)/$total2);;
                $stats[$key]['canceled']['rate']    = $total2==0?'-':round(($stat['canceled']['nr']*100)/$total2);;
            }
        }

        return $stats;
    }
}