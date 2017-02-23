<?php

namespace App\Console\Commands;

use App\Address;
use App\Invoice;
use Illuminate\Console\Command;

class Patch_1_1 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'patch:number1_1';

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
        // fix the invoice number issue - invoices over 9999 remained at 10000
        $allInvoices = Invoice::orderBy('id','ASC')->get();
        if ($allInvoices){
            $changed = 0;
            $allInvoiceNumbers = [];
            foreach ($allInvoices as $invoice){
                if (isset($allInvoiceNumbers[$invoice->invoice_number])){
                    // we need to generate another invoice number

                    $lastInvoiceNr = $invoice->invoice_number + 1;
                    while(isset($allInvoiceNumbers[$lastInvoiceNr])){
                        $lastInvoiceNr++;
                    }

                    $invoice->invoice_number = $lastInvoiceNr;
                    $invoice->save();
                    $changed++;

                    if ($changed%100==0){
                        echo 'We changed ' . $changed . ' invoices so far' . PHP_EOL;
                    }
                }

                //$lastInvoiceNr = $invoice->invoice_number;
                $allInvoiceNumbers[$invoice->invoice_number] = 1;
            }
        }
        // end of fixing

        // checking the zip codes that have less than 4 characters in length and are missing the 0 in front
        $allAddresses = Address::get();
        if ($allAddresses){
            $all_nr = 0;
            foreach ($allAddresses as $address){
                if (strlen($address->postal_code)==3){
                    $address->postal_code = '0'.$address->postal_code;
                    $address->save();
                    $all_nr++;
                }
            }

            echo 'Issues found : '.$all_nr.' and fixed '.PHP_EOL;
        }
        else{
            echo 'No issues with the zipcodes found' . PHP_EOL;
        }
        // end of checking the zip codes

    }
}
