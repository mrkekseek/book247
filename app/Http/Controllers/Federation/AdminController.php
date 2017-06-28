<?php

namespace App\Http\Controllers\Federation;

use App\Http\Controllers\AdminController as Base;
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

class AdminController extends Base
{

    /**
     * Show the application dashboard.
     *
     * @param Request $r
     * @return String
     */

    public function testRoute(Request $r){
//        return json_encode(Api::send_curl(['memberSSOid'=>5 , 'country'=> 'NO' ,'activity' => 1],'federation_member_has_valid_license','GET'));
//        return json_encode(Api::send_curl(['account_key' => '11381-46565-30640-84804-20809'],'validate_account_key','POST'));
//        return Api::send_curl(['memberSSOid' => 15 ,'activity' => null],'federation_buy_license','POST');

        if($r->method() == "POST"){
            return json_encode($r->all());
        }
        $result =  Api::send_curl(['memberSSOid' => 15 ,'membership_id' => null,'account_key' => '81381-46565-30640-84804-20809'],'federation_buy_license','POST');
        if(isset($result->iFrameUrl)) {
            return view('development',['link' => $result->iFrameUrl]);
        } else {
            if(is_string($result)){
                return $result;
            } else {
                return json_encode($result);
            }
        }
    }


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

    public function front_api_call(Request $r){
        $method = $r->get('method');
        if ($method) {
            $data = $r->all();
            unset($data['method']);
            $response = Api::send_curl($data, $method, $r->method());
//            dd($response);
            if ($response->code == 1) {
                return json_encode(array(
                        'success' => true ,
                        'data' => $response->data
                    )
                );
            } else if($response === false) {
                return json_encode(array(
                    'success' => false,
                    'error' => Api::$error
                    )
                );
            } else {
                return json_encode(array(
                        'error' => Api::$error,
                        'data' => json_encode($response)
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
