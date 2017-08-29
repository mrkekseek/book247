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
use App\Http\Controllers\Federation\AnyApi;
use App\Http\Libraries\ApiAuth;
use App\User;

class AdminController extends Base
{

    /**
     * Show the application dashboard.
     *
     * @param Request $r
     * @return String
     */

    public function testRoute(Request $r){
//        $user = User::find(112);
//        dd($user->get_active_membership());
//        return json_encode(Api::send_curl(['memberSSOid'=>5 , 'country'=> 'NO' ,'activity' => 1],'federation_member_has_valid_license','GET'));
//        return json_encode(Api::send_curl(['account_key' => '11381-46565-30640-84804-20809'],'validate_account_key','POST'));
//        return json_encode(Api::send_curl(['memberSSOid' => 115 ,'account_key'=>'45788-52757-20554-64471-70259'],'federation_member_has_valid_license','POST'));
//        return ApiAuth::accounts_get(124);
//        $x = ApiAuth::send('','http://rankedinbookingsso-test.azurewebsites.net/api/Accounts/136','GET');
//        $x = AnyApi::send_curl('http://rankedinbookingsso-test.azurewebsites.net',[],'/api/Accounts/28','GET');


//        if($r->method() == "POST"){
//            return json_encode($r->all());
//        }
        $result =  Api::send_curl([
            'memberSSOid' => 88 ,
            'membership_id' => null,
            'account_key' => '72382-58756-30167-30349-70891',
            'return_url' => "https://rankedin.com/Tournament/Index/408" ],
            'federation_buy_license',
            'POST');
        //$result =  Api::send_curl(['memberSSOid' => 116 ,'membership_id' => null, 'account_key' => '41422-65673-68269-90561-57420', 'return_url' => "https://rankedin.com/Tournament/Index/408" ],'federation_buy_license','POST');
        if(isset($result->iFrameUrl)) {
            return view('development',['link' => $result->iFrameUrl]);
        } else {
            if(is_string($result)){
                return $result;
            } else {
                return json_encode($result);
            }
        }

//        return view('development');
    }

    public function ajaxPay(Request $r) {

    }

    public function localMemberships(Request $r) {
        $user = Auth::user();
        if (!$user || !$user->is_front_user()) {
            return redirect()->intended(route('admin/login'));
        }
        $r->request->add(['memberSSOid' => $user->sso_user_id ,'return_url' => route('homepage')]);
        $response = json_decode(FederationApi::get_buy_url($r));
        return view('development',['link' => $response->iFrameUrl]);
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

        return view('admin/errors/federation/permission_denied', [
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

        return view('admin/errors/federation.not_found', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
        ]);
    }

    public function members_growth() {
        $response =  json_decode(FederationApi::local_get_members_growth());
        $paying_members = $response->paying_members;
        $paying_members_return = [];
        foreach($paying_members as $key => $members) {
            $paying_members_return[] = [$key,$members];
        }

        $non_paying_members = $response->non_paying_members;
        $non_paying_members_return = [];
        foreach($non_paying_members as $key => $members) {
            $non_paying_members_return[] = [$key,$members];
        }

        $all_members = $response->all_members;
        $all_members_return = [];
        foreach( $all_members as $key => $members) {
            $all_members_return[] = [$key,$members];
        }

        return json_encode([
            'success' => true,
            'data' => [
                'visitors' => $non_paying_members_return,
                'pageviews' => $paying_members_return,
                'all_members' => $all_members_return
            ]
        ], JSON_FORCE_OBJECT);
    }

    public function front_api_call(Request $r){
        $method = $r->get('method');
        if ($method) {
            $data = $r->all();
            unset($data['method']);
            $response = Api::send_curl($data, $method, $r->method());

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
