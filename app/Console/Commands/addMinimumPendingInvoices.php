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
    protected $signature = 'userFinance:add_minimum_planned_pending_invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Each membership plan has a minimum retention period; so, if you want to cancel you need to pay the minimum invoices before the membership gets cancelled';

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
        $isAtest = false;

        // get all active memberships
        $activeUsersMembership = UserMembership::whereIn('status',['active'])->get();
        if ($activeUsersMembership){
            $count = 1;
            foreach ($activeUsersMembership as $single){
                // get membership expiration date
                $nr = $single->add_minimum_planned_invoices();

                if ($nr>0){
                    if ($isAtest){ echo PHP_EOL; }
                    echo ' - '.$single->user_id.' - new pending invoices : '.$nr.' '.PHP_EOL;
                    if ($isAtest){ echo PHP_EOL; }
                    $count++;
                }
                elseif ($isAtest){
                    echo $single->user_id.' - no | ';
                }

                if ($count>10 && $isAtest){
                    exit;
                }
            }
        }
        else{
            echo 'No active user memberships';
        }
    }
}
