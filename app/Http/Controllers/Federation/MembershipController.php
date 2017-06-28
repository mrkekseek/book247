<?php

namespace App\Http\Controllers\Federation;

use App\Http\Controllers\MembershipController as Base;
use App\MembershipPlan;
use App\MembershipPlanPrice;
use App\IframePermission;
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
use App\Invoice;
use App\InvoiceItem;
use App\Http\Requests;
use Validator;
use Regulus\ActivityLog\Models\Activity;

/*
 * This controller is linked to the User Membership Plan assigned to him. The actions here are linked to an active membership plan assigned to a user or a plan that will be assigned
 */
class MembershipController extends Base
{

    public function iframed($token ,$sso_id, $membership_id = null)
    {
//        $permission = IframePermission::where([['user_id','=',$sso_id],['permission_token','=',$token]])->first();
//        if(!isset($permission)) {
//            return "You have no permission";
//        } else {
//            $permissions = IframePermission::where('user_id',$sso_id)->get();
//            foreach ($permissions as $p) {
//                $p->delete();
//            }
//        }
        $membership = null;
        $membership_list = null;
        if (isset($membership_id)) {
            $membership = MembershipPlan::find($membership_id);
        }
        if (!$membership) {
            $membership_id = null;
            $membership_list = MembershipPlan::all();
        }

        return view('front/iframe/federation/buy_license_new' ,[
            'user_id' => $sso_id ,
            'membership' => $membership ,
            'membership_list' => $membership_list
        ]);
    }

    public function iframed_paypal_pay(Request $r)
    {

        if ($r->get('user_id')  && $r->get('payment_method') && $r->get('membership')) {
//            $user = User::where('sso_user_id',$r->get('user_id'))->first();
//            if (Auth::loginUsingId($user->id)) {
//                $r->request->add(['member_id' => $user->id, 'selected_plan' => $r->get('membership'), 'start_date' => date('Y-m-d')]);
//                $status = $this->assign_membership_to_member($r);
//                Auth::logout();
//                if($status['success'] == false ){
//                    return json_encode([
//                            'success' => false ,
//                            'message' => $status['errors']
//                        ]
//                    );
//                }
//            }
            if ($r->get('payment_method') == 'paypal') {
                $u = User::where('sso_user_id',$r->get('user_id'))->first();
                $membership = MembershipPlan::find($r->get('membership'));
                return json_encode([
                    'success' => true ,
                    'data' => [
                        'paying' => true,
                        'payment_method' => $r->get('payment_method'),
                        'user' => $u,
                        'membership_name' => $membership->name,
                        'price' => $membership->get_price()->price
                        ]
                    ]
                );
            } else {
                return json_encode([
                    'success' => true,
                    'data' => [
                        'paying' => true,
                        'payment_method' => $r->get('payment_method')
                    ]
                ]);
            }
        } else {
            return json_encode([
                'user_id' => null ,
                'membership' => null
            ]);
        }
    }
}
