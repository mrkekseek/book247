<?php

namespace App\Http\Controllers\Federation;

use App\Http\Controllers\FrontEndUserController as Base;
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
use App\UserStoreCredits;
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
use App\OptimizeSearchMembers;
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
use App\Http\Controllers\AppSettings;

class FrontEndUserController extends Base
{

    public function all_front_members_list()
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
        })->take(100)->get();

        $allMemberships = MembershipPlan::orderBy('name','ASC')->get();

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

        return view('admin/front_users/federation/all_members_list_ajax', [
            'users' => $back_users,
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'allMemberships'    => $allMemberships
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

        return view('admin/front_users/federation/add_new_member', [
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
;
        $member = User::with('personalDetail')->where('id','=',$id)->take('1')->get()->first();
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

        $old_bookings       = $member->count_own_old_bookings();
        $new_bookings       = $member->count_own_active_bookings();
        $cancelled_bookings = $member->count_own_cancelled_bookings();
        $own_friends        = $member->count_own_friends();

        $front_statistics = $this->get_member_bookings_statistics($member);
        $finance_statistics = $this->get_member_financial_statistics($member);

        $all_paid = [];     $total_paid = 0;
        $dropins_paid = []; $total_dropins = 0;
        foreach ($finance_statistics as $one_finance){
            $all_paid[]     = $one_finance['total_paid'];
            $total_paid+=$one_finance['total_paid'];;

            $dropins_paid[] = $one_finance['bookings_paid'];
            $total_dropins+=$one_finance['bookings_paid'];
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
            $member->first_name.' '.$member->middle_name.' '.$member->last_name => '',
        ];
        $sidebar_link= 'admin-frontend-user_details_view';

        return view('admin/front_users/federation/view_member_details', [
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
            'top_stats'     => $front_statistics,
            'finance_total' => $total_paid,
            'finance_paid_list' => $all_paid,
            'bookings_total'=> $total_dropins,
            'bookings_paid_list'=> $dropins_paid,
        ]);
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
            }

            $signOutDate = Carbon::today()->addMonths($my_plan->sign_out_period);
            foreach($allPlannedInvoices as $onlyOne){
                if ($onlyOne->status == 'pending'){
                    $invoiceFreeze[$onlyOne->id] = Carbon::createFromFormat('Y-m-d',$onlyOne->issued_date)->format('jS \o\f F, Y');
                }

                //xdebug_var_dump(Carbon::createFromFormat('Y-m-d',$onlyOne->issued_date));
                if ($signOutDate->lte(Carbon::createFromFormat('Y-m-d',$onlyOne->issued_date))){
                    $invoiceCancellation[$onlyOne->id] = Carbon::createFromFormat('Y-m-d',$onlyOne->last_active_date)->addDay()->format('jS \o\f F, Y');
                }
            }
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

        $plannedInvoicesAndActions = [];
        if (isset($plannedInvoices)){
            foreach ($plannedInvoices as $singleInvoice){
                $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $singleInvoice['issued_date'].' 00:00:00');
                $endDate = Carbon::createFromFormat('Y-m-d H:i:s',   $singleInvoice['last_active_date'].' 00:00:00');

                foreach($plan_request as $keyS=>$single_request){
                    if ($startDate->lte($single_request['start_date']) && $endDate->gte($single_request['end_date']) && $single_request['action_type']=='update'){
                        $plannedInvoicesAndActions[] = [
                            'type'  => $single_request['action_type'],
                            'object'=> $single_request
                        ];

                        unset($plan_request[$keyS]);
                    }
                    elseif ($startDate->lte($single_request['start_date']) && $endDate->gte($single_request['start_date']) && $single_request['action_type']=='freeze'){
                        $plannedInvoicesAndActions[] = [
                            'type'  => $single_request['action_type'],
                            'object'=> $single_request
                        ];
                        unset($plan_request[$keyS]);
                    }
                }

                $plannedInvoicesAndActions[] = [
                    'type'  => 'invoice',
                    'object'=> $singleInvoice
                ];

                foreach($plan_request as $keyS=>$single_request) {
                    if ($startDate->lte($single_request['start_date']) && $endDate->gte($single_request['end_date']) && $single_request['action_type'] == 'cancel') {
                        $plannedInvoicesAndActions[] = [
                            'type' => $single_request['action_type'],
                            'object' => $single_request
                        ];
                        unset($plan_request[$keyS]);
                    }
                }
            }

            if (sizeof($plan_request)>0){
                foreach($plan_request as $keyS=>$single_request) {
                    $plannedInvoicesAndActions[] = [
                        'type'      => $single_request['action_type'],
                        'object'    => $single_request
                    ];
                }
            }
        }

        $storeCredit = UserStoreCredits::where('member_id','=',$member->id)->orderBy('created_at','DESC')->get();
        if($storeCredit){
            $userNames = [];
            foreach($storeCredit as $single){
                if (!isset($userNames[$single->back_user_id])){
                    $theName = User::where('id','=',$single->back_user_id)->first();
                    $userNames[$single->back_user_id] = $theName->first_name.' '.$theName->middle_name.' '.$theName->last_name;
                }
                $single->full_name = $userNames[$single->back_user_id];
            }
        }

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End Users'    => route('admin/back_users'),
            $member->first_name.' '.$member->middle_name.' '.$member->last_name => '',
        ];
        $sidebar_link= 'admin-frontend-user_details_view';

        return view('admin/front_users/federation/view_member_account_settings', [
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
            'accessCardNo'      => @$accessCardNo,
            'InvoicesActionsPlanned'=> $plannedInvoicesAndActions,
            'storeCreditNotes'  => $storeCredit
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

        $locations = ShopLocations::whereIn('visibility',['public','pending','suspended'])->orderBy('name','ASC')->get();

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End Users'    => route('admin/back_users'),
            $member->first_name.' '.$member->middle_name.' '.$member->last_name => '',
        ];
        $sidebar_link= 'admin-frontend-user_details_view';

        return view('admin/front_users/federation/view_member_personal_settings', [
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
            'canFreeze'     => $canFreeze,
            'locations'     => $locations
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

        return view('admin/front_users/federation/view_member_bookings', [
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
                    case 'cancelled' :
                    default:
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

        return view('admin/front_users/federation/view_member_finance', [
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

    public function import_from_file(Request $request){
        ini_set('max_execution_time', 600);

        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }
        elseif (!$user->can('manage-calendar-products')){
            return redirect()->intended(route('admin/error/permission_denied'));
        }

        /** @var  $vars */
        $vars = $request->only('_token','_method','start_date','membership_type','sign_location');

        $allRows = [];
        $members = [];
        $nr = 1;
        $selectMembership = 1;
        $selectLocation = -1;
        $date_start = '';
        $returnMessages = [];
        $memberships = MembershipPlan::orderBy('name','ASC')->get();
        $shopLocations = ShopLocations::whereIn('visibility',['public','pending'])->orderBy('name','asc')->get();

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
                        $chars+=trim(strlen($vals));
                    }

                    $allRows[] = $singleRow;
                    if ($chars>0){

                    }
                    else{
                        $empty_lines++;
                    }

                    if ($empty_lines>15){
                        break;
                    }
                }
                //xdebug_var_dump($allRows);
                $selectMembership = $vars['membership_type'];
                $selectLocation = $vars['sign_location'];
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

            if (isset($vars['sign_location'])){
                $signLocation = ShopLocations::where('id','=',$vars['sign_location'])->whereIn('visibility',['public','pending','suspended'])->get()->first();
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

            $constart   = 999;
            $connumber  = 999;
            $cusnumber  = 999;
            $cuscard    = 999;

            $canceldate = 999;

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
                    case 'contract_start' :
                        $constart   = $key;
                        break;
                    case 'contract_number' :
                        $connumber  = $key;
                        break;
                    case 'customer_number' :
                        $cusnumber  = $key;
                        break;
                    case 'customer_card' :
                        $cuscard    = $key;
                        break;
                    case 'cancellation_date' :
                        $canceldate = $key;
                        break;
                }
            }

            // create $users array of imported value
            for ($i=0; $i<=$vars['key_return']; $i++){
                if (!isset($vars['line_'.$i])){
                    continue;
                }

                try{
                    $dateofbirth = Carbon::createFromFormat('Y-m-d H:i:s', @$vars['col_'.$i][$dob])->format('Y-m-d');
                }
                catch(\Exception $ex){
                    $dateofbirth = Carbon::today()->format('Y-m-d');
                }

                try{
                    $cancellation_date = Carbon::createFromFormat('Y-m-d H:i:s', @$vars['col_'.$i][$canceldate]);
                }
                catch(\Exception $ex){
                    $cancellation_date = '';
                }

                $custom_start_date = '';
                if (isset($vars['col_'.$i][$constart])) {
                    try {
                        $custom_start_date = Carbon::createFromFormat('Y-m-d H:i:s', $vars['col_' . $i][$constart])->format('Y-m-d');
                    }
                    catch (\Exception $ex) { }
                }

                $importUser = [
                    'first_name'        => mb_convert_case(trim(@$vars['col_'.$i][$fname]), MB_CASE_TITLE, mb_detect_encoding(@$vars['col_'.$i][$fname])),
                    //'middle_name'       => ucfirst(strtolower(trim(@$vars['col_'.$i][$mname]))),
                    'middle_name'       => mb_convert_case(trim(@$vars['col_'.$i][$mname]), MB_CASE_TITLE, mb_detect_encoding(@$vars['col_'.$i][$mname])),
                    //'last_name'         => ucfirst(strtolower(trim(@$vars['col_'.$i][$lname]))),
                    'last_name'         => mb_convert_case(trim(@$vars['col_'.$i][$lname]), MB_CASE_TITLE, mb_detect_encoding(@$vars['col_'.$i][$lname])),
                    'email'             => mb_strtolower(trim(@$vars['col_'.$i][$email]), mb_detect_encoding(@$vars['col_'.$i][$email])),
                    'country_id'        => AppSettings::get_setting_value_by_name('globalWebsite_defaultCountryId'),
                    'phone_number'      => trim(@$vars['col_'.$i][$phone]),
                    'password'          => strlen(@$vars['col_'.$i][$passwd])>7?@$vars['col_'.$i][$passwd]:trim(@$vars['col_'.$i][$phone]),
                    'membership_plan'   => @$vars['membership_'.$i],
                    'username'          => trim(@$vars['col_'.$i][$uname]),
                    'user_type'         => @$vars['col_'.$i][$utype],
                    'date_of_birth'     => @$dateofbirth,
                    'about_info'        => isset($vars['col_'.$i][$uinfo])?strtolower(trim($vars['col_'.$i][$uinfo])):'',
                    'address1'          => mb_strtolower(trim(@$vars['col_'.$i][$uaddress]), mb_detect_encoding(@$vars['col_'.$i][$uaddress])),
                    'postal_code'       => trim(@$vars['col_'.$i][$upostalcode]),
                    'city'              => mb_convert_case(trim(@$vars['col_'.$i][$ucity]),MB_CASE_TITLE,mb_detect_encoding(@$vars['col_'.$i][$ucity])),

                    'contract_start'    => @$custom_start_date,
                    'contract_number'   => trim(@$vars['col_'.$i][$connumber]),
                    'customer_number'   => trim(@$vars['col_'.$i][$cusnumber]),
                    'customer_card'     => trim(@$vars['col_'.$i][$cuscard]),

                    'cancellation_date' => @$cancellation_date
                ];
                if (strlen($importUser['first_name'])>2 && strlen($importUser['last_name'])>2 && strlen($importUser['email'])>5){
                    $members[] = $importUser;
                }
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

            $all_new_users = [];

            foreach ($members as $member){
                //xdebug_var_dump($member); //exit;
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
                    $all_new_users[] = $new_member->id;

                    if (strlen($member['address1'])>1 && strlen($member['postal_code'])>1 && strlen($member['city'])>1 ){
                        // we add the address
                        $userPersonal = PersonalDetail::where('user_id','=',$new_member->id)->get()->first();

                        $vars = [
                            'address1'      => $member['address1'],
                            'address2'      => isset($member['address2'])?$member['address2']:'',
                            'city'          => $member['city'],
                            'country_id'    => AppSettings::get_setting_value_by_name('globalWebsite_defaultCountryId'),
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

                            if ($personalAddress){
                                $userPersonal->address_id = $personalAddress->id;
                                $userPersonal->save();
                                $msg.= 'User address added successfully : '.implode(',',$vars).' <br />';
                            }
                        }
                    }

                    // assign membership
                    // Default free plan is the first plan
                    if (strlen($member['membership_plan'])>0 && isset($arrayPlan[$member['membership_plan']])){
                        // we have a plan and we apply it
                        /** @var User $new_member */
                        $new_member->attach_membership_plan($arrayPlan[$member['membership_plan']]['full_plan'], $user, $member['contract_start']!=''?$member['contract_start']:$the_date, $member['contract_number']);

                        $msg.= 'Membership plan assigned : '.$arrayPlan[$member['membership_plan']]['full_plan']->name.' <br />';

                        if($member['cancellation_date']!==''){
                            // we get the active membership plan
                            $new_plan = UserMembership::where('user_id','=',$new_member->id)->whereIn('status',['active','suspended'])->orderBy('created_at','desc')->get()->first();

                            if ($new_plan){
                                // get list of pending invoices
                                $allPendingInvoices = UserMembershipInvoicePlanning::where('user_membership_id','=',$new_plan->id)->where('status','!=','old')->orderBy('issued_date','asc')->get();
                                //echo 'All invoices <br />';
                                //xdebug_var_dump($allPendingInvoices);
                                foreach($allPendingInvoices as $singleInvoice){
                                    $cancellation_invoice = $singleInvoice;
                                    if (Carbon::createFromFormat('Y-m-d',$singleInvoice->issued_date)->lte($member['cancellation_date']) && Carbon::createFromFormat('Y-m-d',$singleInvoice->last_active_date)->gte($member['cancellation_date'])){
                                        break;
                                    }
                                    else{
                                        unset($cancellation_invoice);
                                    }
                                }
                                //echo 'Last invoice <br />';
                                //xdebug_var_dump($cancellation_invoice);

                                // we add the cancellation action to that
                                if (isset($cancellation_invoice)){
                                    $new_member->cancel_membership_plan($new_plan, $cancellation_invoice->issued_date, $cancellation_invoice->last_active_date);
                                    $msg.= 'Membership plan cancellation added : last day - '.$cancellation_invoice->last_active_date.'; cancellation day - '.$cancellation_invoice->issued_date.' <br />';
                                }
                                else{
                                    $new_member->cancel_membership_plan($new_plan, $member['cancellation_date']->format('Y-m-d'), $member['cancellation_date']->format('Y-m-d'));
                                    //$msg.= 'Membership plan cancellation added : last day - '.$cancellation_invoice->last_active_date.'; cancellation day - '.$cancellation_invoice->issued_date.' <br />';
                                    $msg.= '<span class="font-red-mint">Membership plan cancellation added to an ODD day </span><br />';
                                }
                                unset($cancellation_invoice);
                            }
                            else{
                                // plan assignment was unsuccessful so we don't set the cancellation
                            }
                        }
                    }
                    else{
                        $msg.='No plan assigned : #'.$member['membership_plan'].'# - @'.isset($arrayPlan[$member['membership_plan']]).'@<br />';
                    }

                    if (strlen($member['customer_card'])>0){
                        $access_card_fill = [
                            'user_id'   => $new_member->id,
                            'key_no'    => $member['customer_card'],
                            'status'    => 'active',
                        ];

                        $validator = Validator::make($access_card_fill, UserAccessCard::rules('POST'), UserAccessCard::$message, UserAccessCard::$attributeNames);
                        if ($validator->fails()){
                            $msg.='Access card error : '.$member['customer_card'].'<br />';
                        }
                        else {
                            // check if access card already assigned
                            $check_card = UserAccessCard::where('key_no','=',$access_card_fill['key_no'])->where('status','=','active')->get()->first();
                            if ($check_card){
                                $msg.='Access card NOT ASSIGNED - already assigned to another member : '.$member['customer_card'].'<br />';
                            }
                            else{
                                $access_card = UserAccessCard::firstOrNew(['user_id'=>$new_member->id]);
                                $access_card->fill($access_card_fill);
                                $access_card->save();

                                $msg.='Access card assigned : '.$member['customer_card'].'<br />';
                            }
                        }
                    }

                    if ($signLocation){
                        $new_member->set_general_setting('settings_preferred_location',  $signLocation->id);
                        $new_member->set_general_setting('registration_signed_location', $signLocation->id);
                    }
                }

                $returnMessages[] = [
                    'inputData' => $member,
                    'returnMsg' => $msg,
                    'userID'    => isset($new_member->id)?$new_member->id:-1
                ];
            }

            $searchMembers = new OptimizeSearchMembers();
            $searchMembers->add_missing_members($all_new_users);

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

        return view('admin/front_users/federation/import_from_file', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'importedRows'=> $allRows,
            'per_line'    => $nr,
            'memberships' => $memberships,
            'selectedMembership'=> $selectMembership,
            'date_start'        => $date_start,
            'importedMembers'   => $members,
            'returnMessages'    => $returnMessages,
            'shopLocations'     => $shopLocations,
            'selectedLocation'  => $selectLocation
        ]);
    }

    /* Front end pages part - START */


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

        return view('front/settings/federation/account',[
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
        //$userCountry = Countries::find($user->country_id);

        $avatar = $user->get_avatar_image();

        $userMembership = UserMembership::where('user_id','=',$user->id)->get()->first();
        if ($userMembership){
            $membershipName = $userMembership->membership_name;
        }
        else{
            $membershipName = 'No Membership Plan';
        }

        $old_bookings       = $user->count_own_old_bookings();
        $new_bookings       = $user->count_own_active_bookings();
        $cancelled_bookings = $user->count_own_cancelled_bookings();
        $own_friends        = $user->count_own_friends();

        $locations = ShopLocations::whereIn('visibility',['public'])->orderBy('name','ASC')->get();

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

        return view('front/settings/federation/personal',[
            'breadcrumbs'   => $breadcrumbs,
            'text_parts'    => $text_parts,
            'in_sidebar'    => $sidebar_link,
            'user'          => $user,
            'countries'     => $countries,
            'personal'      => $userPersonal,
            'avatar'        => $avatar['avatar_base64'],
            'membershipName'=> $membershipName,
            'countOldBookings'      => $old_bookings,
            'countCancelledBookings'    => $cancelled_bookings,
            'countActiveBookings'   => $new_bookings,
            'countFriends'          => $own_friends,
            'locations'             => $locations
        ]);
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

        return view('front/settings/federation/all_messages',[
            'breadcrumbs'   => $breadcrumbs,
            'text_parts'    => $text_parts,
            'in_sidebar'    => $sidebar_link,
            'user'          => $user,
            'avatar'        => $avatar['avatar_base64'],
            'notes'         => $allNotes
        ]);
    }


    // Stop - Store credit add - backend add store credit to member
}