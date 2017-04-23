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
                $singleAction->proces_action();
            }
        }
    }
}
