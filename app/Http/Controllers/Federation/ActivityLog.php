<?php

namespace App\Http\Controllers\Federation;

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
use App\Http\Controllers\ActivityLog as Base;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use Carbon\Carbon;
use Regulus\ActivityLog\Models\Activity;

class ActivityLog extends Base{

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

        return view('admin/activity_log/federation/activity_log', [
            'breadcrumbs' => $breadcrumbs,
            'text_parts'  => $text_parts,
            'in_sidebar'  => $sidebar_link,
            'action_list'=> $action_list,
        ]);


    }


}