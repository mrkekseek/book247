<?php

namespace App\Http\Controllers;

use App\MembershipPlan;
use App\UserMembership;
use Carbon\Carbon;
use Illuminate\Http\Request;

use App\Http\Requests;

class MembershipController extends Controller
{
    function create(User $user, MembershipPlan $plan, User $signed_by) {
        $to_be_paid = $plan->price();

        $fillable = [
            'user_id'       => $user->id,
            'membership_id' => $plan->id,
            'day_start' => Carbon::today()->toDateString(),
            'day_stop'  => Carbon::today()->toDateString(),
            'membership_name'   => $plan->name,
            'invoice_period'    => $plan->plan_period,
            'price'     => $to_be_paid->price,
            'discount'  => 0,
            'membership_restrictions'   => '',
            'signed_by' => $signed_by->id,
            'status'    => 'active'
        ];

        $membership_restriction = $plan->membership_plan_restrictions();
        $fillable['membership_restrictions'] = json_encode($membership_restriction);

        $validator = Validator::make($fillable, UserMembership::rules('POST'), UserMembership::$message, UserMembership::$attributeNames);
        if ($validator->fails()){
            return false;
        }

        try {
            $the_membership = UserMembership::create($fillable);
            Activity::log([
                'contentId'     => $user->id,
                'contentType'   => 'user_membership',
                'action'        => 'New Membership Assignment',
                'description'   => 'New membership plan assigned to customer : '.$the_membership->id,
                'details'       => 'Created by user : '.$signed_by->id,
                'updated'       => false,
            ]);

            return $the_membership;
        }
        catch (Exception $e) {
            return false;
        }
    }


}
