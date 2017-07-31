<?php

namespace App\Console\Commands;

use App\BookingInvoice;
use App\ShopLocations;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Booking;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\AppSettings;

class BookingsCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:check_past_bookings';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This will search for bookings that have a starting date in the past and are still active and change the status accordingly.';

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
        if (env('FEDERATION',false)){
            exit;
        }

        $default_automatic_bookings_mark_as_show = AppSettings::get_setting_value_by_name('globalWebsite_auto_show_status_change');
        $shops = ShopLocations::get();

        if ($shops){
            foreach ($shops as $shop){
                $shop_option = $shop->get_system_option('automatic_bookings_mark_as_show');

                if ($shop_option==0 || (($shop_option==-1 || $shop_option==false ) && $default_automatic_bookings_mark_as_show===0)){
                    echo ' Location ['. $shop->id .'] has the "Automatic option for mark bookings OFF "' . "\n";
                    continue;
                }
                else{
                    echo ' Location ['. $shop->id .'] has the "Automatic option for mark bookings ON " - ' . "\n";
                }

                $bookings = Booking::where('status','=','active')->where('location_id','=',$shop->id)->get();
                if ($bookings){
                    $updated_bookings = 0;
                    foreach($bookings as $booking){
                        // paid -   booking that has an invoice and is paid
                        // unpaid - booking that has an invoice and is not paid
                        // old -    booking that is membership free

                        // we check all the starting dates and based on that we update the status
                        $currentTime = Carbon::now();
                        $bookingDate = Carbon::createFromFormat('Y-m-d H:i:s',$booking->date_of_booking.' '.$booking->booking_time_start);
                        if ($currentTime->lte($bookingDate)){
                            continue;
                        }

                        switch ($booking->payment_type) {
                            case 'cash' :
                                $invoiceID = $booking->invoice_id;
                                $invoice = BookingInvoice::where('id','=',$invoiceID)->get()->first();

                                if (!$invoice){
                                    continue;
                                }

                                if ($invoice->status == "completed"){
                                    $booking->status = 'paid';
                                }
                                else{
                                    $booking->status = 'unpaid';
                                }

                                break;
                            case 'membership' :
                                $booking->status = 'old';
                                break;
                            case 'recurring':
                                $booking->status = 'old';
                                break;
                        }

                        $booking->save();
                        $updated_bookings++;
                    }

                    echo 'For shop : '.$shop->name.' we have '.$updated_bookings.' bookings! ' . PHP_EOL;
                }
            }
        }
    }
}
