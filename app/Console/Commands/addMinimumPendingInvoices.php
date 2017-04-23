<?php

namespace App\Console\Commands;

use App\UserMembership;
use Illuminate\Console\Command;

class addMinimumPendingInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'userFinance:check_future_pending_invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        // get all active memberships
        $activeUsersMembership = UserMembership::whereIn('status',['active'])->get();
        if ($activeUsersMembership){
            foreach ($activeUsersMembership as $single){
                // get membership expiration date
                $single->add_minimum_planned_invoices();
            }
        }
        else{
            echo 'No active user memberships';
        }
    }
}
