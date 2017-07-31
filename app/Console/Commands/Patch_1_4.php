<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Invoice;

class Patch_1_4 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'patch:number1_4';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Invoice payee and payer';

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
        echo 'Patch 1.4 started.'.PHP_EOL;
        $invoices = Invoice::whereNull('payer_info')->orWhereNull('payee_info')->take(5000)->get();
        foreach($invoices as $invoice) {
            $invoice->update_payee_payer_information();
            $invoice->save();
        }

        echo 'Patch 1.4 exit successfully. '.PHP_EOL;
    }
}
