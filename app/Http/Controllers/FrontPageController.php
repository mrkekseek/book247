<?php

namespace App\Http\Controllers;

use App\ShopLocations;
use App\ShopResourceCategory;
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
        if (Auth::attempt(['email' => $request->input('username'), 'password' => $request->input('password')])) {
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

        $user = Auth::user();

        $shopLocations = ShopLocations::with('opening_hours')->with('resources')->get();

        $resourceCategories = [];
        $categories = ShopResourceCategory::all();
        foreach($categories as $category){
            $nr = DB::table('shop_resources')->where('category_id','=',$category->id)->count();

            if ($nr>0){
                $resourceCategories[$category->id] = ['resources_count'=>$nr, 'name'=>$category->name];
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
        $sidebar_link= 'admin-home_dashboard';

        return view('front\home_page',[
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'user'  => $user,
            'shops' => $shopLocations,
            'resourceCategories' => $resourceCategories,
        ]);

    }

    /**
     * @param $locationID : -1 for all the locations, 1 and above, numeric, for specific location
     * @param $resourceID : -1 for all local resources, 1 and above, numeric, for specific resource
     * @param $requestedHour : hh:mm format, minutes are 00 or 30 by default
     */
    public function check_booking_hour($locationID, $resourceID, $requestedHour){
        // check location if locationID!=-1

        // check resource if resourceID!=-1

        // check requested hour for availability
    }

    public function check_time_availability($date, $time, $resources){
        $resourceNumber = sizeof($resources);
        if ($resourceNumber==0){
            return 100;
        }

        $resourceIDs = [];
        foreach($resources as $resource){
            $resourceIDs[] = $resource->id;
        }

        $q = DB::table('bookings')->where('resource_id','in',implode(',',$resourceIDs))
            ->where('date_of_booking','=',$date)
            ->where('booking_time_start','=',$time);
        $res = $q->get();
        $number = sizeof($res);

        return ceil(($number*100)/$resourceNumber);
    }

    public function get_hours_interval($date_selected, $time_period=30){
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
        $end->addMinutes(-60);

        $interval   = DateInterval::createFromDateString($time_period.' minutes');
        $period     = new DatePeriod($begin, $interval, $end);

        foreach ( $period as $dt ) {
            $hours[$dt->format( "H:i" )] = ['color_stripe' => "blue-dark-stripe"];
        }

        return $hours;
    }

    public function get_booking_hours(Request $request){
        if (Auth::check()) {
            $user = Auth::user();
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
            }
        }

        $returnArray = ["hours"=>$hours, "shopResources"=>$shopResource];

        return $returnArray;
    }

}
