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

        $vars = $request->only('member_id', 'from_date', 'to_date', 'invoice_date', 'no_of_months');
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

        $the_plan = UserMembership::where('user_id','=',$member->id)->whereIn('status',['active','suspended'])->orderBy('created_at','desc')->get()->first();
        if ($the_plan) {
            if ($vars['invoice_date']=='-1' || $vars['no_of_months']=='-1'){
                try{
                    $from_date = Carbon::createFromFormat('d-m-Y H:i:s',$vars['from_date'].' 00:00:00');
                    $to_date = Carbon::createFromFormat('d-m-Y H:i:s',$vars['to_date'].' 00:00:00');
                }
                catch (\Exception $err){
                    return [
                        'success'   => false,
                        'errors'    => 'Invalid interval dates entered. Please check the dates again',
                        'title'     => 'An error occurred'
                    ];
                }
            }
            else{
                $pendingInvoice = UserMembershipInvoicePlanning::where('user_membership_id','=',$the_plan->id)->where('id','=',$vars['invoice_date'])->where('status','=','pending')->get()->first();
                if ($pendingInvoice){
                    $from_date = Carbon::createFromFormat('Y-m-d H:i:s',$pendingInvoice->issued_date.' 00:00:00');
                    $to_date = Carbon::createFromFormat('Y-m-d H:i:s',$pendingInvoice->issued_date.' 00:00:00')->addMonths(abs(ceil($vars['no_of_months'])))->addDays(-1);
                }
                else{
                    return [
                        'success'   => false,
                        'errors'    => 'Could not freeze the current membership plan or no active plan at this moment. Please logout/login then try again.',
                        'title'     => 'An error occurred'
                    ];
                }
            }

            $member->freeze_membership_plan($the_plan, $from_date, $to_date);
            //MembershipController::freeze_membership_rebuild_invoices($old_plan);

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

    public static function freeze_membership_rebuild_invoices($membershipPlan){
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

        $invoicesDate = UserMembership::invoice_membership_period(Carbon::createFromFormat('Y-m-d',$membershipPlan->day_start), $membershipPlan->invoice_period);
        while (Carbon::today()->addMonths(28)->gte($invoicesDate['first_day'])){
            if ($endDate->between($invoicesDate['first_day'], $invoicesDate['last_day']) or $endDate->lt($invoicesDate['first_day'])) {
                $allInvoicesDates[] = $invoicesDate;
            }
            $invoicesDate = UserMembership::invoice_membership_period(Carbon::instance($invoicesDate['last_day'])->addDay(), $membershipPlan->invoice_period);
        }

        $started = false;
        $nr = 0;
        $planned_invoices = UserMembershipInvoicePlanning::where('user_membership_id','=',$membershipPlan->id)->whereIn('status',['pending','last'])->orderBy('created_at','ASC')->get();
        if ($planned_invoices){
            foreach($planned_invoices as $invoice){
                $invoiceStart = Carbon::createFromFormat('Y-m-d', $invoice->issued_date);
                $invoiceEnd = Carbon::createFromFormat('Y-m-d', $invoice->last_active_date);
                // start freeze is in an invoice so we break it
                if ($startDate->between($invoiceStart, $invoiceEnd)){
                    if ($startDate->eq($invoiceStart)){
                        $started = true;
                    }
                    else{
                        $daysInInvoice = $invoiceStart->diffInDays($invoiceEnd);
                        $newDaysInInvoice = $invoiceStart->diffInDays(Carbon::instance($startDate)->addDays(-1));
                        $pricePerDay   = $invoice->price / $daysInInvoice;

                        $invoice->price = ceil(($newDaysInInvoice+1)*$pricePerDay);
                        $invoice->last_active_date  = Carbon::instance($startDate)->addDays(-1)->toDateString();

                        $invoice->save();
                        $started = true;
                        continue;
                    }
                }

                if ($started==true){
                    if ($nr==0 && $endDate->eq($allInvoicesDates[$nr]['last_day'])){
                        $nr++;
                    }

                    if ($nr==0){
                        $daysInInvoice = $invoiceStart->diffInDays($invoiceEnd);
                        $newDaysInInvoice = Carbon::instance($endDate)->addDay()->diffInDays($allInvoicesDates[$nr]['last_day']);
                        $pricePerDay   = $invoice->price / $daysInInvoice;

                        $invoice->price = ceil(($newDaysInInvoice+1)*$pricePerDay);
                        $invoice->issued_date = Carbon::instance($endDate)->addDay()->toDateString();
                    }
                    else{
                        $invoice->issued_date = $allInvoicesDates[$nr]['first_day']->toDateString();
                    }
                    $invoice->last_active_date = $allInvoicesDates[$nr]['last_day']->toDateString();

                    $invoice->save();
                    $nr++;
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

        $vars = $request->only('member_id', 'cancellation_date');
        if ($is_backend_employee==false && $user->id!=$vars['member_id']){
            return [
                'success'   => false,
                'errors'    => 'You don\'t have the permissions to plan cancellation membership plans.',
                'title'     => 'An error occurred'
            ];
        }

        if ($user->id!=$vars['member_id']){
            // we have an employee and a member
            $member = User::where('id','=',$vars['member_id'])->get()->first();
            if (!$member){
                return [
                    'success'   => false,
                    'errors'    => 'You don\'t have the permissions to plan a cancellation.',
                    'title'     => 'An error occurred'
                ];
            }
        }
        else{
            $member = $user;
        }

        $old_plan = UserMembership::where('user_id','=',$member->id)->whereIn('status',['active','suspended'])->orderBy('created_at','desc')->get()->first();
        if ($old_plan) {
            $plannedInvoiceCancelled = UserMembershipInvoicePlanning::where('id','=',$vars['cancellation_date'])->where('user_membership_id','=',$old_plan->id)->get()->first();
            if (!$plannedInvoiceCancelled){
                return [
                    'success'   => false,
                    'errors'    => 'The selected date is not valid and is outside the valid range',
                    'title'     => 'Error Validating Date'
                ];
            }

            $member->cancel_membership_plan($old_plan, $plannedInvoiceCancelled->issued_date);

            return [
                'success'   => true,
                'message'   => 'Membership plan is set to cancel on the requested date. Another plan can be applied after that date.',
                'title'     => 'Membership plan - Planned Canceled'
            ];
        }
        else{
            return [
                'success'   => false,
                'errors'    => 'Could not plan a cancellation to current membership plan or no active plan at this moment. Please logout/login then try again.',
                'title'     => 'An error occurred'
            ];
        }
    }

    public static function cancel_membership_rebuild_invoices($membershipPlan){
        // get cancel date from user_membership_invoice_planning that is unprocessed
        $cancelDate = UserMembershipAction::where('user_membership_id','=',$membershipPlan->id)
            ->where('processed','=','0')
            ->where('action_type','=','cancel')
            ->orderBy('created_at','DESC')
            ->get()
            ->first();
        if (!$cancelDate){
            return ['success' => false, 'errors' => 'no membership actions found for the given User Membership Plan'];
        }
        else{
            $cancelDate->processed = 1;
            $cancelDate->save();
        }

        $startDate  = Carbon::createFromFormat('Y-m-d',$cancelDate->start_date);

        $planned_invoices = UserMembershipInvoicePlanning::where('user_membership_id','=',$membershipPlan->id)->where('status','!=','old')->orderBy('created_at','ASC')->get();
        if ($planned_invoices){
            foreach($planned_invoices as $invoice){
                $invoiceStart = Carbon::createFromFormat('Y-m-d', $invoice->issued_date);
                if ($invoiceStart->gte($startDate)){
                    // delete the planned invoices that have the issue day greater or equal to cancelation date
                    $invoice->delete();
                }
            }

            $lastInvoice = UserMembershipInvoicePlanning::where('user_membership_id','=',$membershipPlan->id)->where('status','=','pending')->orderBy('created_at','DESC')
                ->get()->first()->update(['status'=>'last']);

            return ['success' => true, 'message' => 'Invoices updated'];
        }
        else{
            return ['success' => true, 'message' => 'No invoice updated'];
        }
    }

    public function cancel_membership_planned_action(Request $request){
        if (!Auth::check()) {
            return [
                'success'   => false,
                'title'     => 'An error occurred',
                'errors'    => 'You need to be logged in to have access to this function'
            ];
        }
        else{
            $user = Auth::user();
            $is_backend_employee = $user->is_back_user();
        }

        $vars = $request->only('member_id', 'selected_action');
        if ($is_backend_employee==false && $user->id!=$vars['member_id']){
            return [
                'success'   => false,
                'errors'    => 'You don\'t have the permissions to change membership planned actions.',
                'title'     => 'An error occurred'
            ];
        }

        if ($user->id!=$vars['member_id']){
            // we have an employee and a member
            $member = User::where('id','=',$vars['member_id'])->get()->first();
            if (!$member){
                return [
                    'success'   => false,
                    'errors'    => 'You don\'t have the permissions to change membership planned actions.',
                    'title'     => 'An error occurred'
                ];
            }
        }
        else{
            $member = $user;
        }

        $plan = UserMembership::where('user_id','=',$member->id)
            ->whereIn('status',['active','suspended'])
            ->orderBy('created_at','desc')
            ->get()->first();
        if ($plan) {
            $actionPlan = UserMembershipAction::where('id','=',$vars['selected_action'])
                ->where('user_membership_id','=',$plan->id)
                ->where('status','=','active')
                ->where('processed','=','0')
                ->get()->first();
            if ($actionPlan){
                $actionPlan->status = 'cancelled';
                $actionPlan->save();

                return [
                    'success'   => true,
                    'message'   => 'Membership planned action cancelled. Page will reload now to reflect changes.',
                    'title'     => 'Planned Action Cancelled'
                ];
            }
            else{
                return [
                    'success'   => true,
                    'message'   => 'Selected user does not have an active/frozen membership plan.',
                    'title'     => 'Planned Action Cancellation Error'
                ];
            }
        }
        else{
            return [
                'success'   => false,
                'errors'    => 'Could not find an active membership plan at this moment. Please logout/login then try again.',
                'title'     => 'An error occurred'
            ];
        }
    }
}
