<?php

namespace App\Http\Controllers;

use App\applicationSetting;
use App\Booking;
use App\GeneralNote;
use App\ShopLocations;
use App\ShopResourceCategory;
use App\User;
use App\UserSettings;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use Regulus\ActivityLog\Models\Activity;
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
use Webpatser\Countries\Countries;
use App\Http\Controllers\AppSettings;

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
            $user = Auth::user();
            Activity::log([
                'contentId'     => $user->id,
                'contentType'   => 'login',
                'action'        => 'Front end login',
                'description'   => 'Successful login for '.$user->first_name.' '.$user->middle_name.' '.$user->last_name,
                'details'       => 'User Email : '.$user->email,
                'updated'       => false,
            ]);

            return redirect()->intended('/');
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

        $unreadNotes= [];
        if (isset($user)) {
            $own_friends_bookings = $this::get_own_and_friends_bookings($user->id);
            $settings   = UserSettings::get_general_settings($user->id, ['settings_preferred_location','settings_preferred_activity']);

            // check if preferred location is active
            $preferredShopLocation = ShopLocations::where('id','=',@$settings['settings_preferred_location'])->where('visibility','=','public')->get()->first();
            if (!$preferredShopLocation){
                unset($settings['settings_preferred_location']);
            }

            // check if preferred activity is visible
            $preferredActivity = ShopResourceCategory::where('id','=',@$settings['settings_preferred_activity'])->get()->first();
            if (!$preferredActivity){
                unset($settings['settings_preferred_activity']);
            }

            $unreadNotes = $user->get_public_notes('DESC', 'unread', true);
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
        $countries = Countries::orderBy('name', 'asc')->get();

        return view('front/home_page',[
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'user'  => $user,
            'shops' => $shopLocations,
            'countries' => $countries,
            'resourceCategories' => $resourceCategories,
            'meAndFriendsBookings' => @$own_friends_bookings,
            'settings'  => @$settings,
            'unredNotes'=> $unreadNotes
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

        if ($vars['location_selected']!=-1){
            $location = ShopLocations::where('id','=',$vars['location_selected'])->get()->first();
            $locationOpeningHours = $location->get_opening_hours($dateSelected->format('Y-m-d'));
            $open_at  = $locationOpeningHours['open_at'];
            $close_at = $locationOpeningHours['close_at'];
        }
        else{
            $open_at  = '07:00';
            $close_at = '23:00';
        }

        if (!$dateSelected){
            return 'error';
        }
        else{
            $hours = BookingController::make_hours_interval($dateSelected->format('Y-m-d'), $open_at, $close_at, 30, false, false);
        }

        $occupancy_status = [1=>'green-jungle-stripe',
            2=>'yellow-saffron-stripe',
            3=>'red-stripe',
            4=>'green-jungle-stripe',
            5=>'green-jungle-stripe',
            6=>'yellow-saffron-stripe',
            7=>"dark-stripe"];

        if (isset($user) || AppSettings::get_setting_value_by_name('show_calendar_availability_rule')==1){
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
            if (AppSettings::get_setting_value_by_name('show_calendar_availability_rule')!=1) {
                foreach ($hours as $key => $val) {
                    $hours[$key]['color_stripe'] = 'dark-stripe';
                    $hours[$key]['percent'] = 100;
                }
            }
        }
        else{
            $hours = BookingController::check_bookings_intervals_restrictions($hours, $vars['date_selected'], $vars['selected_category'], $user->id);
            //xdebug_var_dump($hours); exit;

            foreach($resourcesAvailability as $key=>$percentage){
                if ($hours[$key]['color_stripe']=='purple-stripe'){
                    continue;
                }

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

        $locationActivities = [];
        $availableActivities = ShopResource::select('category_id')->where('location_id','=',$vars['location_selected'])->groupBy('category_id')->get();
        if ($availableActivities){
            foreach($availableActivities as $oneActivity){
                $locationActivities[$oneActivity->category_id] = $oneActivity->category_id;
            }
        }

        $returnArray = ["hours"=>$hours, "shopResources"=>$shopResource, "available_activities"=>$locationActivities];

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
            return [];
        }
        else{
            if ($vars['location_selected']!=-1){
                $location = ShopLocations::where('id','=',$vars['location_selected'])->get()->first();
                $locationOpeningHours = $location->get_opening_hours($dateSelected->format('Y-m-d'));
                $open_at  = $locationOpeningHours['open_at'];
                $close_at = $locationOpeningHours['close_at'];
            }
            else{
                $open_at  = '07:00';
                $close_at = '23:00';
            }

            if (!$dateSelected){
                return [];
            }
            else{
                $hours = BookingController::make_hours_interval($dateSelected->format('Y-m-d'), $open_at, $close_at, 30, false, true);
            }

            //$hours = FrontPageController::get_hours_interval($vars['date_selected'], 30, true);
        }
//xdebug_var_dump($hours);
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
            $hours = BookingController::check_bookings_intervals_restrictions($hours, $vars['date_selected'], $vars['selected_category'], $user->id);

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

            if ($hour['percent']==100 || $hour['color_stripe']=='purple-stripe'){
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
            //->whereIn('by_user_id', $me_an_friends,'or')
            ->whereIn('for_user_id',$me_an_friends,'or')
            ->orderBy('created_at','desc')
            ->take(20)
            ->get();
        foreach($bookings as $booking){
            if ( in_array($booking->status,['expired','canceled']) ){ continue; }
            elseif ( $booking->for_user_id != $user->id && in_array($booking->status, ['paid', 'unpaid', 'no_show']) ){ continue; }

            $createdAt = Carbon::createFromTimeStamp(strtotime($booking->created_at))->diffForHumans();
            $singleBook = [];
            $singleBook['passed_time_since_creation'] = $createdAt;
            $singleBook['breated_by']   = $booking->by_user['first_name'].' '.$booking->by_user['middle_name'].' '.$booking->by_user['last_name'];
            $singleBook['breated_for']  = $booking->for_user['first_name'].' '.$booking->for_user['middle_name'].' '.$booking->for_user['last_name'];
            $singleBook['on_location']  = $booking->location['name'];
            $singleBook['on_resource']  = $booking->resource['name'];
            $singleBook['status']       = $booking->status;

            // get category name
            $category = ShopResourceCategory::where('id','=',$booking->resource['category_id'])->get()->first();
            $singleBook['categoryName']     = $category['name'];
            $singleBook['book_date_format'] = Carbon::createFromFormat('Y-m-d',$booking['date_of_booking'])->format('l jS, F Y');  //'Sunday 24th, April 2016';
            $singleBook['book_time_start']  = $booking->booking_time_start;
            $singleBook['book_time_end']    = $booking->booking_time_end;

            $for_user = User::where('id','=',$booking->for_user_id)->first();
            if ($for_user){
                $singleBook['avatar'] = $for_user->get_avatar_image(true);
            }

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
    
    public function terms_of_service(){
        $sidebar_link= 'front-homepage';
        return view('front.terms_of_service',[
            'in_sidebar'  => $sidebar_link,
        ]);
    }
    
    public function privacy_policy(){
        $sidebar_link= 'front-homepage';
        return view('front.privacy_policy',[
            'in_sidebar'  => $sidebar_link,
        ]);
    }
}
