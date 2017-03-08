<?php

namespace App\Console\Commands;

use App\Invoice;
use App\UserMembership;
use App\UserMembershipInvoicePlanning;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UserMembershipPendingInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'userFinance:issue_pending_invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will check each active/suspended membership for pending issues and will mark them as active + will create them';

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
        ini_set('max_execution_time', 500);
        $maxProcessedInvoices = 20000;
        $startNr = 0;

        echo Carbon::now()->format('d-m-Y H:i:s') . ' ######################################################################################## ' . PHP_EOL;

        $startTime = Carbon::now();
        $todayIs = Carbon::today();
        $allPendingInvoices =
            UserMembershipInvoicePlanning::whereIn('status',['pending','last'])
            ->where('issued_date','<=',$todayIs->format('Y-m-d'))
            ->take($maxProcessedInvoices)
            ->get();
        if ($allPendingInvoices){
            echo Carbon::now()->format('d-m-Y H:i:s') . ' - There are '. sizeof($allPendingInvoices) .' Invoices to process.' . PHP_EOL;

            foreach($allPendingInvoices as $invoice){
                $invoiceIssueDate = Carbon::createFromFormat('Y-m-d H:i:s',$invoice->issued_date.' 00:00:00');
                if ($invoiceIssueDate->lte($todayIs)){
                    $startNr++;

                    // invoice needs to be issued today, so we get all the necessary elements;
                    $userMembershipPlan = UserMembership::where('id','=',$invoice->user_membership_id)->take('1')->get()->first();
                    $firstMembershipPlannedInvoice = UserMembershipInvoicePlanning::where('user_membership_id','=',$invoice->user_membership_id)->orderBy('issued_date','ASC')->take('1')->get()->first();
                    $firstMembershipIssuedInvoice = Invoice::where('id','=',$firstMembershipPlannedInvoice->invoice_id)->take('1')->get()->first();

                    if (!$firstMembershipIssuedInvoice){
                        continue;
                    }
                    $member_invoice = new Invoice();
                    $member_invoice->user_id = $firstMembershipIssuedInvoice->user_id;
                    $member_invoice->employee_id = $firstMembershipIssuedInvoice->employee_id;
                    $member_invoice->invoice_type = 'membership_plan_invoice';
                    $member_invoice->invoice_reference_id = $invoice->user_membership_id;
                    $member_invoice->invoice_number = Invoice::next_invoice_number();
                    $member_invoice->status = 'pending';
                    $member_invoice->save();

                    $invoice_item = [
                        'item_name'         => $userMembershipPlan->membership_name,
                        'item_type'         => 'user_memberships',
                        'item_reference_id' => $invoice->user_membership_id,
                        'quantity'          => 1,
                        'price'             => $invoice->price,
                        'vat'               => 0,
                        'discount'          => $invoice->discount
                    ];
                    $member_invoice->add_invoice_item($invoice_item);

                    // we update the planned invoice status to old + we add the id to the issued invoice to it
                    $invoice->status = 'old';
                    $invoice->invoice_id = $member_invoice->id;
                    $invoice->save();

                    if ($startNr % 100 == 0){
                        echo 'New invoice updated ' . $startNr.' of '.$maxProcessedInvoices.'#' . $invoice->invoice_id . PHP_EOL;
                    }

                    if ($startNr>=$maxProcessedInvoices){
                        break;
                    }
                }
            }

            echo Carbon::now()->format('d-m-Y H:i:s') . ' - We issued '. ($startNr) .' new invoices.' . PHP_EOL;
        }
        else{
            echo Carbon::now()->format('d-m-Y H:i:s') . 'There are no Invoices to process.' . PHP_EOL;
        }

        $endTime = Carbon::now();
        echo 'Everything took : '.$endTime->diffInSeconds($startTime) . PHP_EOL . '########################################################################################' . PHP_EOL;
    }
}
