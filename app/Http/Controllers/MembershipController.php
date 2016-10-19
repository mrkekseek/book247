<?php

namespace App\Http\Controllers;

use App\MembershipPlan;
use App\MembershipPlanPrice;
use App\Role;
use App\UserMembership;
use App\ShopResourceCategory;
use App\UserMembershipAction;
use App\UserMembershipInvoicePlanning;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\Http\Requests;
use Validator;
use Regulus\ActivityLog\Models\Activity;

class MembershipController extends Controller
{
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

        $new_plan = new UserMembership();
        if ($new_plan->create_new($member, $the_plan, $user)){
            if ($old_plan) {
                $old_plan->status = 'canceled';
                $old_plan->save();
            }

            $memberRole = Role::where('name','=','front-member')->get()->first();
            if ($member->hasRole('front-member')===false) {
                $member->attachRole($memberRole);
            }

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

    public function freeze_membership_for_member(Request $request){
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

        $vars = $request->only('member_id', 'from_date', 'to_date');
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

        try{
            $from_date = Carbon::createFromFormat('d-m-Y',$vars['from_date']);
            $to_date = Carbon::createFromFormat('d-m-Y',$vars['to_date']);
        }
        catch (\Exception $err){
            return [
                'success'   => false,
                'errors'    => 'Invalid interval dates entered. Please check the dates again',
                'title'     => 'An error occurred'
            ];
        }

        $old_plan = UserMembership::where('user_id','=',$member->id)->whereIn('status',['active','suspended'])->orderBy('created_at','desc')->get()->first();
        if ($old_plan) {
            $member->freeze_membership_plan($old_plan, $from_date, $to_date);
            $this->freeze_membership_rebuild_invoices($old_plan);

            return [
                'success'   => true,
                'message'   => 'Membership status changed to freeze. At the end of the selected period the membership will automatically re-activate.',
                'title'     => 'Membership plan froze'
            ];
        }
        else{
            return [
                'success'   => false,
                'errors'    => 'Could not freeze the current membership plan or no active plan at this moment. Please logout/login then try again.',
                'title'     => 'An error occurred'
            ];
        }
    }

    public function freeze_membership_rebuild_invoices($membershipPlan){
        // get freeze period from user_membership_invoice_planning that is unprocessed
        $freezePeriod = UserMembershipAction::where('user_membership_id','=',$membershipPlan->id)
            ->where('processed','=','0')
            ->where('action_type','=','freeze')
            ->orderBy('created_at','DESC')
            ->get()
            ->first();
        if (!$freezePeriod){
            return ['success' => false, 'errors' => 'no membership actions found for the given User Membership Plan'];
        }
        else{
            $freezePeriod->processed = 1;
            $freezePeriod->save();
        }

        $startDate  = Carbon::createFromFormat('Y-m-d',$freezePeriod->start_date);
        $endDate    = Carbon::createFromFormat('Y-m-d',$freezePeriod->end_date);

        $planned_invoices = UserMembershipInvoicePlanning::where('user_membership_id','=',$membershipPlan->id)->where('status','=','pending')->orderBy('created_at','ASC')->get();
        if ($planned_invoices){
            foreach($planned_invoices as $invoice){
                $invoiceStart = Carbon::createFromFormat('Y-m-d', $invoice->issued_date);
                if ($invoiceStart->gte($startDate)){
                    $dates = UserMembership::invoice_membership_period($endDate->addDay(), $membershipPlan->invoice_period);

                    // freeze start date is less or equal to invoice start date
                    $invoice->issued_date       = $dates['first_day']->toDateString();
                    $invoice->last_active_date  = $dates['last_day']->toDateString();
                    $invoice->save();

                    $endDate = $dates['last_day'];
                }
            }

            return ['success' => true, 'message' => 'Invoices updated'];
        }
        else{
            return ['success' => true, 'message' => 'No invoice updated'];
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
            $member->cancel_membership_plan($old_plan);

            return [
                'success'   => true,
                'message'   => 'Membership status changed to canceled. Another plan can be applied now.',
                'title'     => 'Membership plan Canceled'
            ];
        }
        else{
            return [
                'success'   => false,
                'errors'    => 'Could not cancel the current membership plan or no active plan at this moment. Please logout/login then try again.',
                'title'     => 'An error occurred'
            ];
        }
    }
}
