<?php

namespace App\Http\Controllers;

use App\ShopLocations;
use App\ShopResourceCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\ShopOpeningHour;
use App\ShopResource;
use DateTime;
use DateInterval;
use DatePeriod;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\DB;

class FrontPageController extends Controller
{
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

        $shopLocations = ShopLocations::with('opening_hours')->with('resources')->get();

        $resourceCategories = [];
        $categories = ShopResourceCategory::all();
        foreach($categories as $category){
            $nr = DB::table('shop_resources')->where('category_id','=',$category->id)->count();

            if ($nr>0){
                $resourceCategories[$category->id] = ['count'=>$nr, 'name'=>$category->name];
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

    public function get_booking_hours(Request $request){
        // check if we get today or 10 days from today
        $vars = $request->only('date_selected');
        $dateSelected = Carbon::createFromFormat("Y-m-d", $vars['date_selected']);
        if (!$dateSelected){
            return 'error';
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

        $interval   = DateInterval::createFromDateString('30 minutes');
        $period     = new DatePeriod($begin, $interval, $end);

        $occupancy_status = [1=>'green-jungle-stripe', 2=>'yellow-saffron-stripe', 3=>'red-stripe', 4=>'green-jungle-stripe', 5=>'green-jungle-stripe', 6=>'yellow-saffron-stripe'];
        foreach ( $period as $dt ) {
            $hours[$dt->format( "H:i" )] = ['color_stripe' => $occupancy_status[rand(1,6)]];
        }

        return $hours;
    }

}
