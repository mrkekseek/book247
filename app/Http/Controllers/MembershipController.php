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

        $vars = $request->only('member_id', 'selected_plan', 'start_date');
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

        if (!isset($vars['start_date'])){
            $startDate = Carbon::today()->format('Y-m-d');
        }
        else{
            try{
                $startDate = Carbon::createFromFormat('d-m-Y', $vars['start_date'])->format('Y-m-d');
            }
            catch(\Exception $ex){
                $startDate = Carbon::today()->format('Y-m-d');
            }
        }

        // check for old plans that are active
        $old_plan = UserMembership::where('user_id','=',$member->id)->whereIn('status',['active','suspended'])->orderBy('created_at','desc')->get()->first();

        $new_plan = new UserMembership();
        if ($new_plan->create_new($member, $the_plan, $user, $startDate)){
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

        $vars = $request->only('member_id', 'cancellation_date', 'is_overwrite', 'custom_cancellation_date');
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
            if (isset($vars['is_overwrite']) && $vars['is_overwrite']==1 && $user->can('general-permission-overwrite')){
                try{
                    $cancellation_date = Carbon::createFromFormat('d-m-Y H:i:s',$vars['custom_cancellation_date'].' 00:00:00')->format('Y-m-d');
                }
                catch (\Exception $err){
                    // do nothing
                }

                if (isset($cancellation_date)){
                    $member->cancel_membership_plan($old_plan, $cancellation_date, $cancellation_date);
                }
            }
            else{
                $plannedInvoiceCancelled = UserMembershipInvoicePlanning::where('id','=',$vars['cancellation_date'])->where('user_membership_id','=',$old_plan->id)->get()->first();
                if (!$plannedInvoiceCancelled){
                    return [
                        'success'   => false,
                        'errors'    => 'The selected date is not valid and is outside the valid range',
                        'title'     => 'Error Validating Date'
                    ];
                }
                $member->cancel_membership_plan($old_plan, $plannedInvoiceCancelled->issued_date, $plannedInvoiceCancelled->last_active_date);
            }

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

    public function change_active_membership_for_member(Request $request){
        $user = Auth::user();
        if (!$user || !$user->is_back_user()) {
            return [
                'success' => false,
                'errors'  => 'Error while trying to authenticate. Login first then use this function.',
                'title'   => 'Not logged in'];
        }
        /*elseif (!$user->can('manage-calendar-products')){
            return [
                'success'   => false,
                'errors'    => 'You don\'t have permission to access this page',
                'title'     => 'Permission Error'];
        }*/

        $vars = $request->only('selected_plan', 'start_date', 'member_id');

        // check if user is valid
        $member = User::where('id','=',$vars['member_id'])->get()->first();
        if (!$member){
            return [
                'success' => false,
                'errors'  => 'Could not find the selected member to upgrade/downgrade',
                'title'   => 'Member error'];
        }

        // check if updated membership exists and get price
        $new_plan = MembershipPlan::with('price')->where('id','=',$vars['selected_plan'])->get()->first();
        if (!$new_plan){
            return [
                'success' => false,
                'errors'  => 'Could not find the new membership plan',
                'title'   => 'Membership plan error'];
        }
        $membership_restriction = $new_plan->get_restrictions(true);

        // check if membership active and get price
        $old_plan = $member->get_active_membership();
        if (!$old_plan){
            return [
                'success' => false,
                'errors'  => 'No active membership plan found for selected member',
                'title'   => 'Member plan error'];
        }

        $updatePlannedActions = UserMembershipAction::where('user_membership_id','=',$old_plan->id)->where('action_type','=','update')->where('status','=','active')->get()->first();
        if ($updatePlannedActions){
            return [
                'success' => false,
                'errors'  => 'Could not add a planned update/downgrade action because another exists',
                'title'   => 'Another action found'];
        }

        // get first day for new membership plan
        if ($vars['start_date']==1){
            // next invoice period
            $nextInvoice = UserMembershipInvoicePlanning::where('user_membership_id','=',$old_plan->id)->whereIn('status',['pending','last'])->orderBy('issued_date','ASC')->get()->first();
            $start_date = Carbon::createFromFormat('Y-m-d H:i:s',$nextInvoice->issued_date.' 00:00:00');
        }
        else{
            // today date
            $start_date = Carbon::today();
        }

        // check if invoice period is the same for both plans, if not no actions can be performed
        if ($old_plan->invoice_period!=$new_plan->plan_period){
            return [
                'success' => false,
                'errors'  => 'The old and the new membership plan have different invoice periods; must be the same',
                'title'   => 'Invoice Period Mismatched'];
        }

        $additional_values = [
            'old_membership_plan_id'        => $old_plan->membership_id,
            'old_membership_plan_name'      => $old_plan->membership_name,
            'old_membership_invoice_period' => $old_plan->invoice_period,
            'old_membership_binding_period' => $old_plan->binding_period,
            'old_membership_sign_out_period'=> $old_plan->sign_out_period,
            'old_membership_price'          => $old_plan->price,
            'old_membership_discount'       => $old_plan->discount,
            'old_membership_restrictions'   => $old_plan->membership_restrictions,
            'new_membership_plan_id'        => $new_plan->id,
            'new_membership_plan_name'      => $new_plan->name,
            'new_membership_plan_price'     => $new_plan->get_price()->price,
            'new_membership_plan_discount'  => $new_plan->get_price()->discount,
            'new_membership_restrictions'   => json_encode($membership_restriction),
        ];

        // check if is an update or an downgrade
        if ( $new_plan->price[0]->price >= $old_plan->price){
            // we have an upgrade
            $additional_values['is_update'] = true;
            $return =  $member->update_membership_plan($old_plan, $start_date, $additional_values);

            return [
                'success' => true,
                'message' => 'From '.$old_plan->membership_name.' to '.$new_plan->name.' starting '.$start_date->format('d M Y'),
                'title'   => 'Membership Planned Update'];
        }
        else{
            // we have a downgrade and we check restrictions : verify if the member is outside binding period
            $firstPlanInvoice = UserMembershipInvoicePlanning::where('user_membership_id','=',$old_plan->id)->orderBy('issued_date','ASC')->get()->first();
            if (!$firstPlanInvoice){
                return [
                    'success' => false,
                    'errors'  => 'Could not get first issued invoice for this membership plan',
                    'title'   => 'Upgrade/Downgrade error'];
            }

            // we add the binding period to the first invoice data (start of the membership) and check it against start date
            $verification_date = Carbon::createFromFormat('Y-m-d H:i:s', $firstPlanInvoice->issued_date.' 00:00:00')->addMonths($old_plan->binding_period);
            if (Carbon::today()->gte($verification_date)){
                $additional_values['is_update'] = false;

                // for downgrade, the first day for new membership is the first day of next active invoice
                $nextInvoice = UserMembershipInvoicePlanning::where('user_membership_id','=',$old_plan->id)->where('status','=','pending')->orderBy('issued_date','ASC')->get()->first();
                $start_date = Carbon::createFromFormat('Y-m-d H:i:s',$nextInvoice->issued_date.' 00:00:00');

                $return =  $member->update_membership_plan($old_plan, $start_date, $additional_values);
            }
            else{
                return [
                    'success' => false,
                    'errors'  => 'The current membership plan is in it\'s binding period until '.$verification_date->format('d-m-Y').', after that it can be downgraded',
                    'title'   => 'Upgrade/Downgrade error'];
            }

            return [
                'success' => true,
                'message' => 'From '.$old_plan->membership_name.' to '.$new_plan->day.' starting '.$start_date->format('d M Y'),
                'title'   => 'Membership Planned Downgrade'];
        }
    }

    public static function update_membership_rebuild_invoices($membershipPlan){
        // get freeze period from user_membership_invoice_planning that is unprocessed
        $updateAction = UserMembershipAction::where('user_membership_id','=',$membershipPlan->id)
            ->where('processed','=','0')
            ->where('action_type','=','update')
            ->orderBy('created_at','DESC')
            ->get()
            ->first();
        if (!$updateAction){
            return [
                'success' => false,
                'title'   => 'Error Processing',
                'errors'  => 'no membership actions found for the given User Membership Plan'
            ];
        }
        else{
            $updatedActionValues = json_decode($updateAction->additional_values);
            $updateAction->processed = 1;
            $updateAction->save();
        }

        $startDate  = Carbon::createFromFormat('Y-m-d',$updateAction->start_date);
        // get all invoices and process the action
        $planned_invoices = UserMembershipInvoicePlanning::where('user_membership_id','=',$membershipPlan->id)->whereIn('status',['old','pending','last'])->orderBy('created_at','ASC')->get();
        if ($planned_invoices){
            $started = false;

            foreach($planned_invoices as $keys=>$invoice){
                $invoiceStart   = Carbon::createFromFormat('Y-m-d', $invoice->issued_date);
                $invoiceEnd     = Carbon::createFromFormat('Y-m-d', $invoice->last_active_date);
                // start freeze is in an invoice so we break it
                if ($startDate->between($invoiceStart, $invoiceEnd)){
                    $started = true;
                    if ( (!$startDate->eq($invoiceStart) || $keys==0 ) && $updatedActionValues->is_update==true){
                        // we only create the difference invoice if it's an update
                        $daysSoFar     = $invoiceStart->diffInDays(Carbon::instance($startDate));
                        $daysInInvoice = $invoiceStart->diffInDays(Carbon::instance($invoiceEnd))+1;
                        $newDaysInInvoice = ceil($daysInInvoice-$daysSoFar);
                        $pricePerDay      = $membershipPlan->price / $daysInInvoice;

                        $updateAction['notes'] = json_encode([
                            'daysSoFar'         => $daysSoFar,
                            'daysininvoice'     => $daysInInvoice,
                            'newdaysininvoice'  => $newDaysInInvoice,
                            'priceperday'       => $pricePerDay,
                            'oldplanprice'      => $updatedActionValues->old_membership_price,
                            'newplanprice'      => $updatedActionValues->new_membership_plan_price,

                        ]);
                        $updateAction->save();

                        $diff_price = ceil( ($updatedActionValues->new_membership_plan_price - $updatedActionValues->old_membership_price) - ($daysSoFar*$pricePerDay) );
                        if ($diff_price<0){
                            $diff_price = 0;
                        }

                        // add new planned invoice and/or generate it if starts today or start date less than today
                        $fillable = [
                            'user_membership_id'=> $membershipPlan->id,
                            'item_name'         => 'Membership Update Difference - '.$updatedActionValues->old_membership_plan_name.' to '.$updatedActionValues->new_membership_plan_name.' - '.$newDaysInInvoice.' days',
                            'price'             => $diff_price,
                            'discount'          => 0,
                            'issued_date'       => $startDate->format('Y-m-d'),
                            'last_active_date'  => $invoiceEnd->format('Y-m-d'),
                            'status'            => 'pending'
                        ];
                        $diffInvoice = UserMembershipInvoicePlanning::create($fillable);
                        $diffInvoice->issue_invoice();
                        continue;
                    }
                }

                // first invoice was changed so from now we change all invoices - change price and name of the item
                if ($started==true && $invoice->status!='old'){
                    $re = '/#([0-9]+)/';
                    $str = $invoice->item_name;
                    preg_match_all($re, $str, $matches); //xdebug_var_dump($matches); exit;

                    $invoice->item_name = "Invoice #".$matches[1][0].' for '.$updatedActionValues->new_membership_plan_name;
                    $invoice->price     = $updatedActionValues->new_membership_plan_price;
                    $invoice->discount  = $updatedActionValues->new_membership_plan_discount;
                    $invoice->save();
                }
            }

            return ['success' => true, 'message' => 'Invoices updated'];
        }
        else{
            return ['success' => true, 'message' => 'No invoice updated'];
        }
    }
}
