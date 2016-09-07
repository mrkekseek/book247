<?php

namespace App\Http\Controllers;

use App\Booking;
use App\ShopLocations;
use App\ShopResourceCategory;
use App\UserSettings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use Session;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ShopOpeningHour;
use App\ShopResource;
use DateTime;
use DateInterval;
use DatePeriod;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;

class FrontPageController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function authenticate(Request $request)
    {
        if ( $request->input('remember') ){
            $remember = true;
        }
        else {
            $remember = false;
        }

        if (Auth::attempt(['email' => $request->input('username'), 'password' => $request->input('password')], $remember)) {
            // Authentication passed...
            return redirect()->intended('/');
        }
        else {
            $errors = new MessageBag([
                'password' => ['Username and/or password invalid.'],
                'username' => ['Username and/or password invalid.'],
                'header' => ['Invalid Login Attempt'],
                'message_body' => ['Username and/or password invalid.'],
            ]);

            return  redirect()->intended()
                ->withInput()
                ->withErrors($errors)
                ->with('message', 'Login Failed');
        }
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $errors = Session::get('errors', new MessageBag);
        //xdebug_var_dump($errors);

        //BookingController::check_for_passed_bookings();
        BookingController::check_for_expired_pending_bookings();

        $user = Auth::user();
        $shopLocations = ShopLocations::with('opening_hours')->with('resources')->where('visibility','=','public')->get();

        $resourceCategories = [];
        $categories = ShopResourceCategory::all();
        foreach($categories as $category){
            $nr = DB::table('shop_resources')->where('category_id','=',$category->id)->count();

            if ($nr>0){
                $resourceCategories[$category->id] = ['resources_count'=>$nr, 'name'=>$category->name];
            }
        }

        if (isset($user)) {
            $own_friends_bookings = $this::get_own_and_friends_bookings($user->id);
            $settings   = UserSettings::get_general_settings($user->id, ['settings_preferred_location','settings_preferred_activity']);
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
        $sidebar_link= 'front-homepage';

        return view('front/home_page',[
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'user'  => $user,
            'shops' => $shopLocations,
            'resourceCategories' => $resourceCategories,
            'meAndFriendsBookings' => @$own_friends_bookings,
            'settings'  => @$settings
        ]);
    }

    /**
     * @param $locationID : -1 for all the locations, 1 and above, numeric, for specific location
     * @param $resourceID : -1 for all local resources, 1 and above, numeric, for specific resource
     * @param $requestedHour : hh:mm format, minutes are 00 or 30 by default
     */
    public function check_booking_hour($locationID, $resourceID, $requestedDate, $requestedHour){
        // check location if locationID!=-1

        // check resource if resourceID!=-1

        // check requested hour for availability
    }

    public function get_resource_list_for_date_time(Request $request){
        $vars = $request->only('date_selected', 'location_selected', 'selected_category', 'time_selected');

        $shopResource = [];
        $next_interval = [];

        $resourcesQuery = DB::table('shop_resources')->where('category_id','=',$vars['selected_category']);
        if ($vars['location_selected']!=-1){
            $resourcesQuery->where('location_id','=',$vars['location_selected']);
        }
        $allResources = $resourcesQuery->get();

        if (sizeof($allResources)>0){
            foreach($allResources as $resource){
                $shopResource[$resource->id] = ['name' => $resource->name, 'color' => $resource->color_code, 'id' => $resource->id];
                $resourceIDs[] = $resource->id;
            }

            $q = DB::table('bookings')->whereIn('resource_id',$resourceIDs)
                ->where('date_of_booking','=',$vars['date_selected'])
                ->whereIn('status',['pending','active','paid','unpaid','old'])
                ->where('booking_time_start','=',$vars['time_selected']);
            $bookings = $q->get();

            if (sizeof($bookings)>0){
                foreach($bookings as $booking){
                    unset($shopResource[$booking->resource_id]);
                }
            }

            $next_interval = $this->get_next_available_hours($vars);
        }

        return ['shop_resources' => $shopResource, 'next_interval' => $next_interval];
    }

    /** Returns the occupancy level of the resource for the specified time */
    public function check_time_availability($date, $time, $resources){
        $resourceNumber = sizeof($resources);
        if ($resourceNumber==0){
            return 100;
        }

        $resourceIDs = [];
        foreach($resources as $resource){
            $resourceIDs[] = $resource->id;
        }

        $q = DB::table('bookings')->whereIn('resource_id',$resourceIDs)
            ->where('date_of_booking','=',$date)
            ->whereIn('status',['pending','active','paid','unpaid','old'])
            ->where('booking_time_start','=',$time);
        $res = $q->get();
        $number = sizeof($res);

        return ceil(($number*100)/$resourceNumber);
    }

    public function get_hours_interval($date_selected, $time_period=30, $show_last = false){
        $dateSelected = Carbon::createFromFormat("Y-m-d", $date_selected);
        if (!$dateSelected){
            return [];
        }

        $hours = [];

        // if selected day = today
        $currentTimeHour    = Carbon::now()->format('H');
        $currentTimeMinutes = Carbon::now()->format('i');

        if ($currentTimeMinutes>=0 && $currentTimeMinutes<30){
            $currentTimeMinutes = 30;
        }
        else{
            $currentTimeMinutes = 0;
            $currentTimeHour = (int)$currentTimeHour+1;
        }

        $begin  = Carbon::today();
        $v1 = $dateSelected->format("Y-m-d");
        $v2 = Carbon::now()->format("Y-m-d");
        if ( $v1==$v2) {
            $begin->addHour($currentTimeHour);
            $begin->addMinutes($currentTimeMinutes);
        }
        else{
            $begin->addHour(7);
        }
        $end    = Carbon::tomorrow();
        if ($show_last==false){
            $end->addMinutes(-60);
        }
        else{
            $end->addMinutes(-30);
        }

        $interval   = DateInterval::createFromDateString($time_period.' minutes');
        $period     = new DatePeriod($begin, $interval, $end);

        foreach ( $period as $dt ) {
            $hours[$dt->format( "H:i" )] = ['color_stripe' => "blue-dark-stripe"];
        }

        return $hours;
    }

    public function get_booking_hours(Request $request){
        if (Auth::check() && Auth::user()->is_front_user()) {
            $user = Auth::user();
            BookingController::check_for_expired_pending_bookings();
        }

        $shopResource = [];
        $resourcesAvailability = [];

        // check if we get today or 10 days from today
        $vars = $request->only('date_selected','selected_category','location_selected');
        $dateSelected = Carbon::createFromFormat("Y-m-d", $vars['date_selected']);
        if (!$dateSelected){
            return 'error';
        }
        else{
            $hours = FrontPageController::get_hours_interval($vars['date_selected'], 30);
        }

        $occupancy_status = [1=>'green-jungle-stripe',
            2=>'yellow-saffron-stripe',
            3=>'red-stripe',
            4=>'green-jungle-stripe',
            5=>'green-jungle-stripe',
            6=>'yellow-saffron-stripe',
            7=>"dark-stripe"];
        if (isset($user)){
            foreach($hours as $key=>$hour){
                $colorStripe = $occupancy_status[rand(1,6)];
                $hours[$key]['color_stripe'] = $colorStripe;
                $resourcesAvailability[$key] = 100;
            }
        }

        $resourcesQuery = DB::table('shop_resources')->where('category_id','=',$vars['selected_category']);
        if ($vars['location_selected']!=-1){
            $resourcesQuery->where('location_id','=',$vars['location_selected']);
        }
        $allResources = $resourcesQuery->get();

        if (sizeof($allResources)>0){
            foreach($allResources as $resource){
                $shopResource[] = $resource;
            }
        }

        if (!isset($user)){
            foreach($hours as $key=>$val){
                $hours[$key]['color_stripe'] = 'dark-stripe';
                $hours[$key]['percent'] = 100;
            }
        }
        else{
            foreach($resourcesAvailability as $key=>$percentage){
                $resourcesAvailability[$key] = FrontPageController::check_time_availability($vars['date_selected'], $key, $allResources);
                if($resourcesAvailability[$key]==100){
                    $hours[$key]['color_stripe'] = 'red-stripe';
                }
                elseif($resourcesAvailability[$key]>49){
                    $hours[$key]['color_stripe'] = 'yellow-saffron-stripe';
                }
                else{
                    $hours[$key]['color_stripe'] = 'green-jungle-stripe';
                }
                $hours[$key]['percent'] = $resourcesAvailability[$key];
            }
        }

        $returnArray = ["hours"=>$hours, "shopResources"=>$shopResource];

        return $returnArray;
    }

    /**
     * @param $vars - array('date_selected', 'location_selected', 'selected_category', 'time_selected')
     * @return array - available hours
     */
    public function get_next_available_hours($vars){
        if (Auth::check()) {
            $user = Auth::user();
            BookingController::check_for_expired_pending_bookings();
        }

        // check if we get today or 10 days from today
        $dateSelected = Carbon::createFromFormat("Y-m-d", $vars['date_selected']);
        if (!$dateSelected){
            return 'error';
        }
        else{
            $hours = FrontPageController::get_hours_interval($vars['date_selected'], 30, true);
        }

        if (isset($user)){
            foreach($hours as $key=>$hour){
                $resourcesAvailability[$key] = 100;
            }
        }

        $resourcesQuery = DB::table('shop_resources')->where('category_id','=',$vars['selected_category']);
        if ($vars['location_selected']!=-1){
            $resourcesQuery->where('location_id','=',$vars['location_selected']);
        }
        $allResources = $resourcesQuery->get();

        if (sizeof($allResources)>0){
            foreach($allResources as $resource){
                $shopResource[] = $resource;
            }
        }

        if (!isset($user)){
            foreach($hours as $key=>$val){
                $hours[$key]['color_stripe'] = 'dark-stripe';
                $hours[$key]['percent'] = 100;
            }
        }
        else{
            foreach($resourcesAvailability as $key=>$percentage){
                $hours[$key]['percent'] = FrontPageController::check_time_availability($vars['date_selected'], $key, $allResources);
            }
        }

        $available_ones = [];
        foreach ($hours as $key => $hour){
            if ($key<=$vars['time_selected']){
                continue;
            }

            $available_ones[$key] = $hour;

            if ($hour['percent']==100){
                break;
            }
        }

        return $available_ones;
    }

    public function get_resource_list_for_booking(Request $request){

    }

    public function get_own_and_friends_bookings($userID){
        if (Auth::check()) {
            $user = Auth::user();
        }
        else{
            return [];
        }

        $formatedBookings = [];
        $me_an_friends = [$userID];

        $bookings = Booking::with('by_user')
            ->with('for_user')
            ->with('location')
            ->with('resource')
            ->whereIn('by_user_id', $me_an_friends,'or')
            ->whereIn('for_user_id',$me_an_friends,'or')
            ->orderBy('created_at','desc')
            ->take(50)
            ->get();
        foreach($bookings as $booking){
            if ( in_array($booking->status,['expired','canceled']) ){ continue; }
            elseif ( $booking->for_user_id != $user->id && in_array($booking->status, ['paid', 'unpaid', 'no_show']) ){ continue; }

            $createdAt = Carbon::createFromTimeStamp(strtotime($booking->created_at))->diffForHumans();
            $singleBook = [];
            $singleBook['passed_time_since_creation'] = $createdAt;
            $singleBook['breated_by'] = $booking->by_user['first_name'].' '.$booking->by_user['middle_name'].' '.$booking->by_user['last_name'];
            $singleBook['breated_for'] = $booking->for_user['first_name'].' '.$booking->for_user['middle_name'].' '.$booking->for_user['last_name'];
            $singleBook['on_location'] = $booking->location['name'];
            $singleBook['on_resource'] = $booking->resource['name'];
            $singleBook['status'] = $booking->status;

            // get category name
            $category = ShopResourceCategory::where('id','=',$booking->resource['category_id'])->get()->first();
            $singleBook['categoryName'] = $category['name'];
            $singleBook['book_date_format'] =  Carbon::createFromFormat('Y-m-d',$booking['date_of_booking'])->format('l jS, F Y');  //'Sunday 24th, April 2016';
            $singleBook['book_time_start'] = $booking->booking_time_start;
            $singleBook['book_time_end'] = $booking->booking_time_end;

            $formatedBookings[] = $singleBook;
            if (sizeof($formatedBookings)>15){ break; }
        }

        return $formatedBookings;
    }

    public function get_bookings_summary(Request $request){
        $vars = $request->only('selected_bookings');

        return 'bine';
    }

    public function error_404(){

    }
}
