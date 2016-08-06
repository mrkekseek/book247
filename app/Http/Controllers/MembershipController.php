<?php

namespace App\Http\Controllers;

use App\MembershipPlan;
use App\MembershipPlanPrice;
use App\Role;
use App\UserMembership;
use App\ShopResourceCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Http\Requests;
use Validator;
use Regulus\ActivityLog\Models\Activity;

class MembershipController extends Controller
{
    function create(User $user, MembershipPlan $plan, User $signed_by) {
        $to_be_paid = $plan->get_price();

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

        $membership_restriction = $plan->get_restrictions();
        $fillable['membership_restrictions'] = json_encode($membership_restriction);

        $validator = Validator::make($fillable, UserMembership::rules('POST'), UserMembership::$message, UserMembership::$attributeNames);
        if ($validator->fails()){
            //echo json_encode($validator->getMessageBag()->toArray());
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

    public function assign_membership_to_member(Request $request){
        if (!Auth::check()) {
            return [
                'success'   => false,
                'title'     => 'An error occurred',
                'errors'    => 'You need to be logged in to have access to this function'
            ];
        }
        else{
            $user = Auth::user();
            $is_backend_employee = $user->can('members-management');
        }

        $vars = $request->only('member_id', 'selected_plan');
        if ($is_backend_employee==false && $user->id!=$vars['member_id']){
            return [
                'success'   => false,
                'errors'    => 'You don\'t have the permissions to apply this membership plan to this member.',
                'title'     => 'An error occurred'
            ];
        }

        if ($user->id!=$vars['member_id']){
            // we have an employee and a member
            $member = User::where('id','=',$vars['member_id'])->get()->first();
            if (!$member){
                return [
                    'success'   => false,
                    'errors'    => 'You don\'t have the permissions to apply this membership plan to this member.',
                    'title'     => 'An error occurred'
                ];
            }
        }
        else{
            $member = $user;
        }

        $the_plan = MembershipPlan::with('price')->where('id','=',$vars['selected_plan'])->where('status','=','active')->get()->first();
        if (!$the_plan){
            return [
                'success'   => false,
                'errors'    => 'The selected plan could not be found in the system; the plan might not be active. Please check.',
                'title'     => 'An error occurred'
            ];
        }

        // check for old plans that are active
        $old_plan = UserMembership::where('user_id','=',$member->id)->whereIn('status',['active','suspended'])->orderBy('created_at','desc')->get()->first();

        $new_plan = $this->create($member, $the_plan, $user);
        if ($new_plan){
            if ($old_plan) {
                $old_plan->status = 'canceled';
                $old_plan->save();
            }

            $memberRole = Role::where('name','=','front-member')->get()->first();
            $member->attachRole($memberRole);

            return [
                'success'   => true,
                'message'   => 'Membership <b>'.$new_plan->name.'</b> assigned to <b>'.$member->first_name.' '.$member->middle_name.' '.$member->last_name.'</b>',
                'title'     => 'Membership Plan Updated'
            ];
        }
        else{
            return [
                'success'   => false,
                'errors'    => 'Could not assign plan to member. Please logout/login then try again.',
                'title'     => 'An error occurred'
            ];
        }

    }

    public function cancel_membership_for_member(Request $request){
        if (!Auth::check()) {
            return [
                'success'   => false,
                'title'     => 'An error occurred',
                'errors'    => 'You need to be logged in to have access to this function'
            ];
        }
        else{
            $user = Auth::user();
            $is_backend_employee = $user->can('members-management');
        }

        $vars = $request->only('member_id');
        if ($is_backend_employee==false && $user->id!=$vars['member_id']){
            return [
                'success'   => false,
                'errors'    => 'You don\'t have the permissions to change membership plans.',
                'title'     => 'An error occurred'
            ];
        }

        if ($user->id!=$vars['member_id']){
            // we have an employee and a member
            $member = User::where('id','=',$vars['member_id'])->get()->first();
            if (!$member){
                return [
                    'success'   => false,
                    'errors'    => 'You don\'t have the permissions to change membership plans.',
                    'title'     => 'An error occurred'
                ];
            }
        }
        else{
            $member = $user;
        }

        $old_plan = UserMembership::where('user_id','=',$member->id)->whereIn('status',['active','suspended'])->orderBy('created_at','desc')->get()->first();
        if ($old_plan) {
            $old_plan->status = 'canceled';
            $old_plan->save();

            $memberRole = Role::where('name','=','front-member')->get()->first();
            $member->detachRole($memberRole);

            return [
                'success'   => true,
                'message'   => 'Membership status changed to canceled. Another plan can be applied now.',
                'title'     => 'Membership plan Canceled'
            ];
        }
        else{
            return [
                'success'   => false,
                'errors'    => 'Could not assign plan to member. Please logout/login then try again.',
                'title'     => 'An error occurred'
            ];
        }
    }
}
