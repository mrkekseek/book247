<?php

namespace App\Http\Controllers\Federation;

use App\Http\Controllers\MembershipController as Base;
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

/*
 * This controller is linked to the User Membership Plan assigned to him. The actions here are linked to an active membership plan assigned to a user or a plan that will be assigned
 */
class MembershipController extends Base
{

    public function iframed($token ,$sso_id, $membership_id)
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
        $redirect_url = Input::get('redirect_url',false);
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
            'membership_list' => $membership_list,
            'redirect_url' => $redirect_url
        ]);
    }

    public function iframed_paypal_pay(Request $r)
    {

        if ($r->get('user_id')  && $r->get('payment_method') && $r->get('membership')) {

            $user = User::where('sso_user_id',$r->get('user_id'))->first();
            $status = AuthLocal::loginUsingId($user->id);
            if ( $status ) {

                $r->request->add([
                    'member_id' => $user->id,
                    'selected_plan' => $r->get('membership'),
                    'start_date' => date('Y-m-d')
                ]);

                $membership = UserMembership::where([
                    ['user_id','=',$user->id],
                    ['membership_id','=',$r->get('membership')]
                ])->first();

                if(!isset($membership)) {
                    $status = $this->assign_membership_to_member($r,'pending');
                    if($status['success'] == false ){
                        return json_encode([
                                'success' => false ,
                                'message' => $status['errors']
                            ]
                        );
                    }
                }

                AuthLocal::logout();

            }
            if ($r->get('payment_method') == 'paypal') {
                $u = User::where('sso_user_id',$r->get('user_id'))->first();
                $membership = MembershipPlan::find($r->get('membership'));
                $userMembership = UserMembership::where([
                    ['user_id','=',$u->id],
                    ['membership_id','=',$r->get('membership')]
                ])->first();

                $invoicePlan = UserMembershipInvoicePlanning::where('user_membership_id', $userMembership->id)->first();

                $invoices = InvoiceItem::where('invoice_id',$invoicePlan->invoice_id)->get();

                return json_encode([
                    'success' => true ,
                    'data' => [
                        'paying' => true,
                        'payment_method' => $r->get('payment_method'),
                        'user' => $u,
                        'invoices' => $invoices
//                        'membership_name' => $membership->name,
//                        'price' => $membership->get_price()->price
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
    public function ipn(Request $r)
    {
        $log = new Paypal();
        $log->fill([
            'invoice_id' => -1,
            'paypal_response' => json_encode($r->all())
        ]);
        $log->save();
    }

    public function paypal_success(Request $r)
    {
        $amount = $r->get('amt');
        $curency = $r->get('cc');
        $transactionId = $r->get('tx');
        $url = $r->get('cm');

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
        return view('front/iframe/federation/redirect_page',[
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'text' => 'Payment successful!',
            'status' => 'Success',
            'link' => 'http://book.net/admin/test_api_call',
            'url' => $url
        ]);
    }

    public function paypal_cencel(Request $r)
    {
        $url = $r->get('cm');
        return view('front/iframe/federation/redirect_page',[
            'text' => 'Payment Canceled :(',
            'status' => 'Failed',
            'link' => 'http://book.net/admin/test_api_call',
            'url' => $url
        ]);
    }
}
