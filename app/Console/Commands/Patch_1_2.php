<?php

namespace App\Console\Commands;

use App\UserMembershipInvoicePlanning;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\UserMembershipAction;
use App\UserMembership;

class Patch_1_2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'patch:number1_2';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check passed membership actions regarding cancellation.';

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
        // We check past cancellations and we mark the membership as cancelled + remove any other invoices that are issued after cancellation date
        // We scan and re-check all actions from the past that are still active

        $plannedActions = UserMembershipAction::where('status','=','active')->where('action_type','=','cancel')->where('end_date','<',Carbon::today())->take(1)->get();
        if ($plannedActions){
            foreach ($plannedActions as $singleAction){
                echo 'Planned action : '.$singleAction->id.' . '.PHP_EOL; //exit;
                $to_date    = Carbon::createFromFormat('Y-m-d H:i:s',$singleAction->end_date.' 00:00:00')->addDay(); // we need next day

                // get user active/suspended membership
                $userMembership = UserMembership::where('id','=',$singleAction->user_membership_id)->whereIn('status',['active','suspended'])->get()->first();
                if ($userMembership) {
                    echo 'Membership found : '.$userMembership->id.' for user '.$userMembership->user_id.'. '.PHP_EOL;
                    // we check if end_date + 1Day, for the planned action, is equal to today date, so we cancel the membership plan
                    if (Carbon::today()->gte($to_date)) {
                        $userMembership->status = 'cancelled';
                        $userMembership->save();

                        $singleAction->processed = 1;
                        $singleAction->status = 'old';
                        $singleAction->add_note('Membership canceled today : ' . time() . ' : by System User');
                        $singleAction->save();

                        // remove future planned invoices
                        $plannedInvoices = UserMembershipInvoicePlanning::where('user_membership_id','=',$userMembership->id)->whereIn('status',['pending','last'])->where('issued_date','>=',$to_date)->get();
                        if ($plannedInvoices){
                            echo 'Future invoices were deleted : '.sizeof($plannedInvoices).' invoices. '.PHP_EOL;
                            foreach($plannedInvoices as $singleInvoice){
                                $singleInvoice->delete();
                            }
                        }

                        echo 'Membership Cancelled : '.Carbon::today()->format('d-m-Y H:i').' . '.PHP_EOL;
                    }
                    else{
                        echo 'Please investigate user : '.$userMembership->user_id.' . '.PHP_EOL;
                    }
                }
                else{
                    // no active or suspended membership plan found; we mark as old the planned action
                    $singleAction->processed = 1;
                    $singleAction->status = 'old';
                    $singleAction->add_note('No active or suspended membership found. Planned action marked as old and processed.');
                    echo 'No active or suspended membership found. Planned action marked as old and processed.'.PHP_EOL;

                    $singleAction->save();
                    continue;
                    // continue
                }
            }
        }
        else{
            // there are no active planned actions
            echo 'There are no old planned actions regarding cancellation that were not processed. '.PHP_EOL;
        }
    }
}
