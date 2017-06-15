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
use App\Http\Controllers\Api;

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

        return view('admin/main_page_public_federation',[
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

    public function front_api_call(Request $r){
        $method = $r->get('method');
        if ($method) {
            $data = $r->all();
            if ($r->method() == 'POST') {
                $data[] = ['site_id' => env('MY_API_ID',"")];
            }
            $response = Api::send_curl($data, $method, $r->method());
            if ($response) {
                return json_encode(array(
                        'success' => true ,
                        'data' => $response
                    )
                );
            } else if($response === false) {
                return json_encode(array(
                    'success' => false,
                    'error' => Api::$error,
                    'c_url_error' => Api::$curl_error
                    )
                );
            } else {
                return json_encode(array(
                        'error' => Api::$error
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

}
