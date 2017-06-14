<?php

namespace App\Http\Controllers\Federation;

use App\Booking;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Role;
use App\ShopLocations;
use App\ShopResource;
use App\ShopResourceCategory;
use App\UserMembership;
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

//        $new_players_this_week = json_decode($this->get_from_api('new_players_this_week'));
//        $income_this_week = json_decode($this->get_from_api('income_this_week'));
//        $upcoming_tournaments = json_decode($this->get_from_api('upcoming_tournaments'));
//        $registered_players = json_decode($this->get_from_api('registered_players'));
//        $squash_players = $this->get_from_api('squash_players');
//        $members_growth = $this->get_from_api('members_growth');
//        $number_of_club_members = $this->get_from_api('number_of_club_members');
//        $overall_bookings = $this->get_from_api('overall_bookings');
//        $total_players = $this->get_from_api('total_players');
//        $total_courts = $this->get_from_api('total_courts');

        return view('admin/main_page_public_federation',[
//            'new_players_this_week' => $new_players_this_week,
//            'income_this_week' => $income_this_week,
//            'upcoming_tournaments' => $upcoming_tournaments,
//            'registered_players' => $registered_players,
//            'squash_players' => $squash_players,
//            'members_growth' => $members_growth,
//            'number_of_club_members' => $number_of_club_members,
//            'overall_bookings' => $overall_bookings,
//            'total_players' => $total_players,
//            'total_courts' => $total_courts,
            'breadcrumbs'   => $breadcrumbs,
            'text_parts'    => $text_parts,
            'in_sidebar'    => $sidebar_link
        ]);

    }




    public function authenticate(Request $request)
    {
        if (Auth::attempt(['email' => $request->input('username'), 'password' => $request->input('password')])) {
            // Authentication passed...
            $user = Auth::user();
            if ($user->hasRole(['manager','employee'])){
                return redirect()->route('bookings/location_calendar_day_view',['day'=>\Carbon\Carbon::now()->format('d-m-Y')]);
            }
            else{
                return redirect()->intended('admin');
            }
        }
        else {
            $errors = new MessageBag([
                'password' => ['Username and/or password invalid.'],
                'username' => ['Username and/or password invalid.'],
                'header' => ['Invalid Login Attempt'],
                'message_body' => ['Username and/or password invalid.'],
            ]);

            return  redirect()->intended(route('admin/login'))
                    ->withInput()
                    ->withErrors($errors)
                    ->with('message', 'Login Failed');
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
    private function prelevate_data(){

    }

    public function front_api_call(Request $r){
        $method = $r->get('method');
        if ($method) {
            $response = $this->get_from_api($method);
            if ($response) {
                return json_encode(array(
                        'success' => true ,
                        'data' => $response
                    )
                );
            } else {
                return json_encode(array(
                    'success' => false
                    )
                );
            }
        } else {
            return json_encode(array(
                    'success' => false
                )
            );
        }
    }


    public function get_from_api($get = null){
        $url = env('APIURL',"");
        $key = env('APIKEY',"");
        $site_id = env('MY_API_ID',"");
        if ($url && $key && $get && $site_id) {
            $url = $url.'/'.$get;
            $token = str_random(32);
            $data = array('token' => $token, 'encrypted_token' => base64_encode(hash_hmac('sha256', $token, $key, TRUE)), 'site_id' => $site_id);

            $ch = curl_init();

            curl_setopt($ch,CURLOPT_URL, $url);
            curl_setopt($ch,CURLOPT_POST, count($data));
            curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);
            if ($result) {
                return $result;
            } else {
                return "";
            }
        } else {
            return "";
        }
    }

    public function get_from_api_test(){
        $url = env('APIURL',"");
        $key = env('APIKEY',"");
        $get = 'squash_players';
        if ($url && $key && $get) {
            $url = $url.'/'.$get;
            $token = str_random(32);
            $data = array('token' => $token, 'encrypted_token' => base64_encode(hash_hmac('sha256', $token, $key, TRUE)));

            $ch = curl_init();

            curl_setopt($ch,CURLOPT_URL, $url);
            curl_setopt($ch,CURLOPT_POST, count($data));
            curl_setopt($ch,CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            curl_close($ch);
            if ($result) {
                return $result;
            } else {
                return "";
            }
        } else {
            return "";
        }
    }
}
