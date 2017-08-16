<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Http\Requests;
use App\Role;
use App\ShopLocations;
use App\ShopResource;
use App\ShopResourceCategory;
use Illuminate\Http\Request;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $homeStats = [];
        $requestedDate = Carbon::now();
        $lastSevenDays = [];
        $total_memberships_today = 0;
        for ($i=7; $i>0; $i--){
            $lastSevenDays[] = Carbon::today()->addDays(-$i)->format('Y-m-d');
        }
        //xdebug_var_dump($lastSevenDays);
        $totalBookingsLocationsToday = [];
        $totalBookingTypeToday = [];

        $locations = ShopLocations::whereIn('visibility',['public'])->orderBy('name','ASC')->get();
        if ($locations){
            $shopResourceCategories = ShopResourceCategory::get();

            foreach($locations as $location){
                $today_occupancy = [];
                $today_availability = [];
                $lastSeven = [];
                $bookings_this_month = [];
                $bookings_last_month = [];
                $locationCategories = [];
                $toadyBookings = 0;

                // get maximum of possible bookings
                $availableSessions = $location->get_location_available_hours($requestedDate->format("Y-m-d"), true);
                foreach($shopResourceCategories as $resourceCategory){
                    $locationResources = [];
                    $categoryName = $resourceCategory->name;

                    // get available resources for category
                    $availableResources= ShopResource::where('location_id','=',$location->id)->where('category_id','=',$resourceCategory->id)->get();
                    if (sizeof($availableResources)==0){
                        continue;
                    }
                    $locationCategories[] = $categoryName;

                    foreach($availableResources as $resource){
                        $locationResources[] = $resource->id;
                    }
                    $today_availability[] = sizeof($locationResources) * sizeof($availableSessions);

                    // get today bookings
                    $bookings = Booking::
                        where('date_of_booking','=',$requestedDate->format('Y-m-d'))
                        ->whereNotIn('status',['expired', 'canceled'])
                        ->where('location_id','=',$location->id)
                        ->whereIn('resource_id',$locationResources)
                        ->get();

                    $bookings = DB::select('SELECT a.id, a.payment_type, a.location_id, b.membership_id, b.membership_name FROM `bookings` as a left join `user_memberships` as b on a.membership_id=b.id WHERE date(a.date_of_booking)=date(now()) and a.location_id=?',[$location->id]);

                    $today_occupancy[] = sizeof($bookings);
                    $toadyBookings+=sizeof($bookings);
                    foreach ($bookings as $single_booking){
                        switch ($single_booking->payment_type){
                            case 'cash' : $pay_key = 'drop-in';
                                break;
                            case 'recurring' : $pay_key = 'recurring';
                                break;
                            default:
                                if (isset($single_booking->membership_id)){
                                    $pay_key = $single_booking->membership_name;
                                }
                                else{
                                    $pay_key = 'unknown';
                                }
                                break;
                        }

                        if (isset($totalBookingTypeToday[$pay_key])){
                            $totalBookingTypeToday[$pay_key]++;
                        }
                        else{
                            $totalBookingTypeToday[$pay_key] = 1;
                        }
                    }

                    // get last 7 days of bookings
                    foreach($lastSevenDays as $singleDay){
                        $countA = Booking::where('date_of_booking','=',$singleDay)
                            ->whereNotIn('status',['expired', 'canceled'])
                            ->where('location_id','=',$location->id)
                            ->whereIn('resource_id',$locationResources)
                            ->count();
                        $countB = Booking::where('date_of_booking','=',$singleDay)
                            ->whereNotIn('status',['expired', 'canceled'])
                            ->where('location_id','=',$location->id)
                            ->where('payment_type','=','membership')
                            ->whereIn('resource_id',$locationResources)
                            ->count();
                        $lastSeven[] = ['all'=>$countA, 'membership'=>$countB];
                    }

                    // get bookings this month
                    $this_month_bookings = Booking::whereRaw(' month(now()) = month(date_of_booking) ')
                        ->whereRaw(' year(now()) = year(date_of_booking) ')
                        ->where('location_id','=',$location->id)
                        ->whereIn('resource_id',$locationResources)
                        ->count();
                    $bookings_this_month[] = $this_month_bookings;

                    // get bookings last month
                    $start = new Carbon('first day of last month');
                    $end = new Carbon('last day of last month');
                    $last_month_bookings = Booking::where('date_of_booking','>=',$start->format('Y-m-d'))
                        ->where('date_of_booking','<=',$end->format('Y-m-d'))
                        ->where('location_id','=',$location->id)
                        ->whereIn('resource_id',$locationResources)
                        ->count();
                    $bookings_last_month[] = $last_month_bookings;
                }

                // get new memberships signed in today
                $todayMemberships = DB::select("select count(a.id) as nr from user_memberships as a join user_settings as b on a.user_id=b.user_id where date(a.created_at) = date(now()) and b.var_name='registration_signed_location' and b.var_value=?", [$location->id]);
                $total_memberships_today+=isset($todayMemberships[0]->nr)?$todayMemberships[0]->nr:0;

                $homeStats[$location->id] = [
                    'location_name'     => $location->name,
                    'location_id'       => $location->id,
                    'today_occupancy'   => $today_occupancy,
                    'today_availability'=> $today_availability,
                    'last_seven_days'   => $lastSeven,
                    'members_today'     => isset($todayMemberships[0]->nr)?$todayMemberships[0]->nr:0,
                    'bookings_this_month'=> $bookings_this_month,
                    'bookings_last_month'=> $bookings_last_month,
                    'location_categories'=> $locationCategories,
                ];

                $totalBookingsLocationsToday[] = [
                    'name'      => $location->name,
                    'amount'    => $toadyBookings
                ];
            }
        }
        //xdebug_var_dump($homeStats); //exit;
        //xdebug_var_dump($totalBookingsLocationsToday); //exit;
        //xdebug_var_dump($totalBookingTypeToday); //exit;

        $breadcrumbs = [
            'Home'      => route('admin'),
            'Dashboard' => '',
        ];
        $text_parts  = [
            'title'     => 'Home',
            'subtitle'  => 'users dashboard',
            'table_head_text1' => 'Dashboard Summary'
        ];
        $sidebar_link= 'admin-home_dashboard';

        return view('admin/main_page',[
            'breadcrumbs'   => $breadcrumbs,
            'text_parts'    => $text_parts,
            'in_sidebar'    => $sidebar_link,
            'stats'         => $homeStats,
            'membersToday'  => $total_memberships_today,
            'totalToday'    => $totalBookingsLocationsToday,
            'totalPerType'  => $totalBookingTypeToday
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function public_index()
    {
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
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
        $sidebar_link= 'admin-home_dashboard';

        return view('admin/main_page_public',[
            'breadcrumbs'   => $breadcrumbs,
            'text_parts'    => $text_parts,
            'in_sidebar'    => $sidebar_link
        ]);
    }

    public function authenticate(Request $request)
    {
        if (Auth::attempt(['username' => $request->input('username'), 'password' => $request->input('password')])) {
            // Authentication passed...
            $user = Auth::user();
            return redirect()->intended('admin');

            // check user status
            switch ($user->status){
                case 'active' :
                    // all good
                    // \Cache::forget('globalWebsite_registration_finished');
                    $status = AppSettings::get_setting_value_by_name('globalWebsite_registration_finished');
                    if ( $status==0 ) {
                        return redirect('admin/registration');
                    }
                    break;
                case 'suspended':
                    // show message suspended;
                    Auth::logout();
                    $errors = new MessageBag([
                        'password'  => ['Username and/or password invalid.'],
                        'username'  => ['Username and/or password invalid.'],
                        'header'    => ['Invalid Login Attempt'],
                        'message_body' => ['Your account is suspended. Contact the owner.'],
                    ]);

                    return  redirect()->intended(route('admin/login'))
                        ->withInput()
                        ->withErrors($errors)
                        ->with('message', 'Login Failed');
                    break;
                case 'deleted' :
                default:
                    Auth::logout();
                    $errors = new MessageBag([
                        'password'  => ['Username and/or password invalid.'],
                        'username'  => ['Username and/or password invalid.'],
                        'header'    => ['Invalid Login Attempt'],
                        'message_body' => ['Your account is deleted. Contact the owner.'],
                    ]);

                    return  redirect()->intended(route('admin/login'))
                        ->withInput()
                        ->withErrors($errors)
                        ->with('message', 'Login Failed');
                        break;
            }
        }
        else {
            $errors = new MessageBag([
                'password' => ['Username and/or password invalid.'],
                'username' => ['Username and/or password invalid.'],
                'header' => ['Invalid Login Attempt'],
                'message_body' => ['Username and/or password invalid.'],
            ]);            
            if (!empty(Auth::$error)){
                $errors = new MessageBag([                
                    'password' => ['Username and/or password invalid.'],
                    'username' => ['Username and/or password invalid.'],
                    'header' => ['Invalid Login Attempt'],
                    'message_body' => [Auth::$error],
                ]);
            }
            return  redirect()->intended(route('admin/login'))
                    ->withInput()
                    ->withErrors($errors)
                    ->with('message', 'Login Failed');
        }
    }

    public function ajax_authenticate(Request $request)
    {
        if (Auth::attempt(['username' => $request->input('username'), 'password' => $request->input('password')])) {
            // Authentication passed...
            $user = Auth::user();
            // check user status
            switch ($user->status){
                case 'active' :
                    // all good
                    \Cache::forget('globalWebsite_registration_finished');
                    if (env('FEDERATION',false)) {
                        $redirect_url = route('admin');
                    } else {
                        $status = AppSettings::get_setting_value_by_name('globalWebsite_registration_finished');
                        if ( $status==0 && $user->hasRole('owner') ){
                            $redirect_url = route('admin/registration');
                        }
                        elseif ($user->hasRole(['manager','employee'])){
                            $redirect_url = route('bookings/location_calendar_day_view',['day' => \Carbon\Carbon::now()->format('d-m-Y')]);
                        }
                        else{
                            $redirect_url = route('admin');
                        }
                    }

                    return [
                        'success'   => true,
                        'title'     => 'Login Successful',
                        'message'   => 'Please wait while you are being redirected to admin page',
                        'redirect_url' => $redirect_url

                    ];
                    break;
                case 'suspended':
                    // show message suspended;
                    Auth::logout();
                    $errors = 'This account is suspended. Contact club owner for details';

                    return [
                        'success'   => false,
                        'title'     => 'Login warning',
                        'redirect_url' => '',
                        'errors'    => $errors
                    ];

                    break;
                case 'deleted' :
                default:
                    Auth::logout();
                    $errors = 'This account is deleted. Contact club owner for details';

                    return [
                        'success'   => false,
                        'title'     => 'Login Failed',
                        'redirect_url' => route('admin/login'),
                        'errors'    => $errors
                    ];
                    break;
            }
        }
        else {
            $errors = [
                'password' => ['Username and/or password invalid.'],
                'username' => ['Username and/or password invalid.'],
                'header' => ['Invalid Login Attempt'],
                'message_body' => ['Username and/or password invalid.'],
            ];
            if (!empty(Auth::$error)){
                $errors = [
                    'password' => ['Username and/or password invalid.'],
                    'username' => ['Username and/or password invalid.'],
                    'header' => ['Invalid Login Attempt'],
                    'message_body' => [Auth::$error],
                ];
            }
            return [
                'success' => false,
                'title'   => 'Login Failed',
                'redirect_url' => '',
                'message' => $errors
            ];
        }
    }

    public function logout(){
        Auth::logout();
        return redirect()->intended(route('admin/login'));
    }

    public function permission_denied(){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End User'     => route('admin'),
            'Permissions'        => '',
        ];
        $text_parts  = [
            'title'     => 'Add new membership plan',
            'subtitle'  => '',
            'table_head_text1' => 'Membership Plans - Create New'
        ];
        $sidebar_link = 'error_permission_denied';

        return view('admin/errors/permission_denied', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
        ]);
    }

    public function not_found(){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End User'     => route('admin'),
            'Permissions'        => '',
        ];
        $text_parts  = [
            'title'     => 'Add new membership plan',
            'subtitle'  => '',
            'table_head_text1' => 'Membership Plans - Create New'
        ];
        $sidebar_link = 'error_not_found';

        return view('admin/errors/not_found', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
        ]);
    }

    public function error_404(){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }

        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Back End User'     => route('admin'),
            'Permissions'        => '',
        ];
        $text_parts  = [
            'title'     => 'Add new membership plan',
            'subtitle'  => '',
            'table_head_text1' => 'Membership Plans - Create New'
        ];
        $sidebar_link = 'error_not_found';

        return view('admin/errors/not_found', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
        ]);
    }

}
