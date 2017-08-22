<?php

namespace App\Http\Controllers\Federation;

use App\Http\Controllers\MembershipController as Base;
use App\Http\Controllers\Optimizations;
use App\MembershipPlan;
use App\MembershipPlanPrice;
use App\IframePermission;
use App\Paypal;
use App\Role;
use App\UserMembership;
use App\ShopResourceCategory;
use App\UserMembershipAction;
use App\UserMembershipInvoicePlanning;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\InvoiceItem;
use Illuminate\Support\Facades\Input;
use Validator;
use Illuminate\Support\Facades\Auth as AuthLocal;
use App\Http\Libraries\ApiAuth;

/*
 * This controller is linked to the User Membership Plan assigned to him. The actions here are linked to an active membership plan assigned to a user or a plan that will be assigned
 */
class MembershipController extends Base
{

    public function iframed($token ,$sso_id, $membership_id)
    {
        $permission = IframePermission::where([['user_id','=',$sso_id],['permission_token','=',$token]])->first();
        if(!isset($permission)) {
            return "You have no permission";
        }
        $permission->delete();

        $synchronized = ApiAuth::synchronize($sso_id);
        if($synchronized != true) {
            if (is_string($synchronized)) {
                return [
                    'success' => false,
                    'message' => $synchronized
                ];
            } else {
                return $synchronized;
            }

        }
        $redirect_url = Input::get('redirect_url',false);
        $user = User::where('sso_user_id',$sso_id)->first();
        if($user) {
            // let's update optimize table
            \App\Http\Controllers\Optimizations::add_new_members_to_table();

            $m = $user->get_active_membership();
            if( $m ) {
                if ($m->status = 'active'){
                    return view('front/iframe/federation/buy_license_new' ,[
                        'membership_active' => true,
                        'redirect_url' => $redirect_url,
                    ]);
                } else {
                    return view('front/iframe/federation/buy_license_new' ,[
                        'membership_suspended' => true,
                        'redirect_url' => $redirect_url,
                    ]);
                }
            }
        }

        $membership = null;
        $membership_list = null;
        if (isset($membership_id)) {
            $membership = MembershipPlan::find($membership_id);
        }
        if (!$membership) {
            $membership_id = null;
            $membership_list = MembershipPlan::where('status','=','active')->where('id','!=',1)->get();
        }

        return view('front/iframe/federation/buy_license_new' ,[
            'user_id' => $sso_id ,
            'membership' => $membership ,
            'membership_list' => $membership_list,
            'redirect_url' => $redirect_url,
            'paypal_email' => AppSettings::get_setting_value_by_name('finance_simple_paypal_payment_account')
        ]);
    }


}
