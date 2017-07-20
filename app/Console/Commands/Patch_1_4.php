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
        foreach(Invoice::all() as $invoice) {
            $invoice->update_payee_payer_information();
            $invoice->save();
        }
    }
}
