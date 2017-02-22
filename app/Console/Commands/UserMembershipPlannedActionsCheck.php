<?php

namespace App\Console\Commands;

use App\BookingInvoice;
use App\UserMembership;
use App\UserMembershipAction;
use App\UserMembershipInvoicePlanning;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Booking;

class UserMembershipPlannedActionsCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'userMembership:planned_actions_check';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will check planned actions for today and will apply the changes to invoices and membership plan';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        ini_set('max_execution_time', 600);

        $plannedActions = UserMembershipAction::where('status','=','active')->get();
        if ($plannedActions){
            foreach($plannedActions as $singleAction){
                $from_date  = Carbon::createFromFormat('Y-m-d H:i:s',$singleAction->start_date.' 00:00:00');
                $to_date    = Carbon::createFromFormat('Y-m-d H:i:s',$singleAction->end_date.' 00:00:00')->addDay(); // we need next day

                // get user active/suspended membership
                $userMembership = UserMembership::where('id','=',$singleAction->user_membership_id)->whereIn('status',['active','suspended'])->get()->first();
                if ($userMembership){
                    // we found an existing membership plan that is active or suspended/freezed

                    switch ($singleAction->action_type) {
                        case 'freeze' :
                            if ($singleAction->processed==0){
                                // we need to recalculate invoices so we search for next pending invoice in the membership
                                $nextInvoice = UserMembershipInvoicePlanning::where('user_membership_id','=',$userMembership->id)->where('status','=','pending')->orderBy('issued_date','ASC')->get()->first();
                                if ($nextInvoice){
                                    // if the freeze starts between next invoices start/end date, then we rebuild the invoices
                                    $invoiceStart   = Carbon::createFromFormat('Y-m-d H:i:s', $nextInvoice->issued_date.' 00:00:00');
                                    $invoiceEnd     = Carbon::createFromFormat('Y-m-d H:i:s', $nextInvoice->last_active_date.' 00:00:00');

                                    if ($from_date->between($invoiceStart, $invoiceEnd)) {
                                        // check the planned invoices that needs to be pushed out of the freeze period
                                        MembershipController::freeze_membership_rebuild_invoices($userMembership);

                                        $singleAction->add_note('Membership freeze schedule - invoices recalculation today : '.time().' : by System User');
                                        $singleAction->processed = 1;
                                        $singleAction->save();
                                    }
                                }
                            }
                            else{
                                // we need to freeze the membership plan if the freeze starts today
                                if (Carbon::today()->eq($from_date)){
                                    $userMembership->status = 'suspended';
                                    $userMembership->save();

                                    $singleAction->add_note('Membership frozen today : '.time().' : by System User');
                                    $singleAction->processed = 1;
                                    $singleAction->save();
                                }
                                // we need to unfreez the membership plan if the freeze stops today
                                elseif (Carbon::today()->eq($to_date)){
                                    $userMembership->status = 'active';
                                    $userMembership->save();

                                    $singleAction->status = 'old';
                                    $singleAction->add_note('Membership unfrozen today : '.time().' : by System User');
                                    $singleAction->save();
                                }
                                else{
                                    continue;
                                }
                            }
                            break;
                        case 'cancel' :
                            // we check if end_date + 1Day, for the planned action, is equal to today date, so we cancel the membership plan
                            if (Carbon::today()->eq($to_date)){
                                $userMembership->status = 'cancelled';
                                $userMembership->save();

                                $singleAction->processed = 1;
                                $singleAction->status = 'old';
                                $singleAction->add_note('Membership canceled today : '.time().' : by System User');
                                $singleAction->save();
                            }
                            break;
                        case 'update' :
                            if (Carbon::today()->eq($from_date)){
                                $additional_values = json_decode($singleAction->additional_values);

                                // check the planned invoices that needs to be pushed out of the freeze period
                                MembershipController::update_membership_rebuild_invoices($userMembership);

                                // change active membership details : name, price, discount, restrictions
                                $userMembership->membership_id          = $additional_values->new_membership_plan_id;
                                $userMembership->membership_name        = $additional_values->new_membership_plan_name;
                                $userMembership->price                  = $additional_values->new_membership_plan_price;
                                $userMembership->discount               = $additional_values->new_membership_plan_discount;
                                $userMembership->membership_restrictions = $additional_values->new_membership_restrictions;
                                $userMembership->save();

                                // mark action as old
                                $singleAction->processed = 1;
                                $singleAction->status = 'old';
                                $singleAction->save();
                            }
                            break;
                        default :
                            $singleAction->processed = 1;
                            $singleAction->status = 'old';
                            $singleAction->add_note('Unknown action type found; error returned.');
                            $singleAction->save();
                            break;
                    }

                    //exit;
                }
                else{
                    // no active or suspended membership plan found; we mark as old the planned action
                    $singleAction->processed = 1;
                    $singleAction->status = 'old';
                    $singleAction->add_note('No active or suspended membership found. Planned action marked as old and processed.');

                    $singleAction->save();
                    continue;
                    // continue
                }
            }
        }


    }
}
