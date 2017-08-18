<?php

namespace App\Http\Controllers;

use App\Booking;
use App\Http\Requests;
use App\Role;
use App\ShopLocations;
use App\ShopResource;
use App\ShopResourceCategory;
use Illuminate\Http\Request;
use App\User;
use App\FinancialProfile;
use App\StoreCreditProducts;
use App\MembershipPlan;
use App\BookingInvoiceItem;
use Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use Carbon\Carbon;
use Regulus\ActivityLog\Models\Activity;

class ActivityLog extends Controller{

    public function show_activity_log() {
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return redirect()->intended(route('admin/login'));
        }
        $breadcrumbs = [
            'Home'              => route('admin'),
            'Administration'    => route('admin'),
            'Reports'     => route('admin'),
            'Activity Logs' => '',
        ];
        $text_parts  = [
            'title'     => 'Activity Log',
            'subtitle'  => 'view all users activity',
            'table_head_text1' => 'Activity Log'
        ];
        $sidebar_link= 'admin-activity-log';
        $action_list = [];
        foreach (Activity::all() as $activity) {
            $action_list[$activity->content_type] =  ucwords(str_replace('_',' ',$activity->content_type));
        }

        return view('admin/activity_log/activity_log', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'action_list'=> $action_list,
        ]);


    }

    public function get_activity_log(Request $r) {
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return [
                'success'   => false,
                'title'     => 'You must log in!',
                'errors'    => 'You must be logged!'
            ];
        }

        $vars = $r->only('action','ip','from_date','to_date');

        if($vars['action'] || $vars['ip'] || $vars['from_date'] || $vars['to_date']) {
            $filters = [];
            if ($vars['action']) {
                $filters[] = ['content_type','=',$vars['action']];
            }

            if ($vars['ip']) {
                $filters[] = ['ip_address', 'LIKE', '%'.$vars['ip'].'%'];
            }

            if ($vars['from_date']) {
                $filters[] = ['created_at', '>', Carbon::createFromFormat('d/m/Y', $vars['from_date'])->format('Y-m-d 0:0:0')];
            }

            if ($vars['to_date']) {
                $filters[] = ['created_at', '<', Carbon::createFromFormat('d/m/Y', $vars['to_date'])->format('Y-m-d 0:0:0')];
            }


            $activity_log = Activity::where($filters)->get();
        } else {
            $activity_log = Activity::all();
        }

        $iTotalRecords = sizeof($activity_log);
        $iDisplayLength = intval($_REQUEST['length']);
        $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength;
        $iDisplayStart = intval($_REQUEST['start']);

        $records = array();
        $records["data"] = array();

        $end = $iDisplayStart + $iDisplayLength;
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;
        for($i = $iDisplayStart; $i < $end; $i++) {
            $log = $activity_log[$i];
            switch ($log->content_type) {
                case 'financial_profile':
                    $description = explode(':',$log->description);
                    if (!isset($description[1])) {
                        break;
                    }
                    $profile = FinancialProfile::find(trim($description[1]));
                    if ($profile) {
                        $log->description = str_replace(trim($description[1]),'<a href="'.route('admin/settings_financial_profiles/edit',['id' => $profile->id]).'">' . $profile->profile_name . '</a>',$log->description);
                    }
                    break;
                case 'membership_plan':
                    $description = explode(':',$log->description);
                    if (!isset($description[1])) {
                        break;
                    }
                    $plan = MembershipPlan::find(trim($description[1]));
                    if ($plan) {
                        $log->description = str_replace(trim($description[1]),'<a href="'.route('admin.membership_plan.edit',['id' => $plan->id]).'">'.$plan->name. '</a>',$log->description);
                    }
                    break;
                case 'store_credit_products':
                    $description = explode(':',$log->description);
                    if (!isset($description[1])) {
                        break;
                    }
                    $product = StoreCreditProducts::find(trim($description[1]));
                    if ($product) {
                        $log->description = str_replace(trim($description[1]),'<a href="'.route('admin.store_credit_products.show',['id' => $product->id]).'">'.$product->name. '</a>',$log->description);
                    }
                    break;
                case 'bookings':
                    $description = explode(':', $log->description);
                    if (!isset($description[1])) {
                        break;
                    }
                    $booking_item = BookingInvoiceItem::where('booking_id', trim($description[1]))->first();
                    if ($booking_item) {
                        $log->description = str_replace(trim($description[1]), $booking_item->resource_name . ' at ' . $booking_item->location_name, $log->description);
                        $booking = Booking::find($booking_item->booking_id);
                        if ($booking) {
                            $u = User::find($booking->for_user_id);
                            $log->description .= ' for <a href="'.route('admin/front_users/view_user',['id' => $u->id]).'">' . $u->first_name . ' ' . $u->last_name .'</a>';
                        }
                    }
                    break;
                case 'user_membership':
                    $description = explode(':',$log->description);
                    if (!isset($description[1])) {
                        break;
                    }
                    $u = User::find(trim($description[1]));
                    if ($u) {
                        $log->description = str_replace(trim($description[1]),'<a href="'.route('admin/front_users/view_user',['id' => $u->id]).'">' . $u->first_name . ' ' . $u->last_name .'</a>',$log->description);
                    }
                    break;
                default:
                    break;
            }
            $u = User::find($log->user_id);
            if ($u) {
                $records["data"][] = array(
                    $i + 1,
                    '<a href="'.route('admin/front_users/view_user',['id' => $u->id]).'">' . $u->first_name . ' ' . $u->last_name .'</a>',
                    ucwords(str_replace('_',' ',$log->content_type)),
                    $log->description,
                    $log->ip_address,
                    (string)$log->created_at,
                );
            }
        }

        if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
            $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
            $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
        }

        $records["draw"] = intval($_REQUEST['draw']);
        $records["recordsTotal"] = sizeof($activity_log);
        $records["recordsFiltered"] = sizeof($activity_log);

        return $records;

    }

}