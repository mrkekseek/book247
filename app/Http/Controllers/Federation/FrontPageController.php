<?php

namespace App\Http\Controllers\Federation;
use App\Http\Controllers\FrontPageController as Base;
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
use App\Http\Controllers\BookingController;

class FrontPageController extends Base
{

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
        if (isset($user) && $user != false) {
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

        return view('front/home_page_federation',[
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'user'  => $user,
            'shops' => $shopLocations,
            'resourceCategories' => $resourceCategories,
            'meAndFriendsBookings' => @$own_friends_bookings,
            'settings'  => @$settings,
            'unredNotes'=> $unreadNotes
        ]);
    }

}
