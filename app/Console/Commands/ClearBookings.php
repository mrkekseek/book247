<?php

namespace App\Console\Commands;

use App\Booking;
use App\BookingInvoice;
use App\BookingInvoiceItem;
use App\Http\Controllers\AppSettings;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\User;

class ClearBookings extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookings:clear_pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear pending bookings that were not paid.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (AppSettings::get_setting_value_by_name('bookings_online_payment_rule')) {

            $bookings = Booking::where('status','active')->with('invoice')->get();

            foreach ($bookings as $booking) {
                //time check
                if ($booking->invoice_id > 0) {
                    $booking_date = Carbon::createFromFormat("Y-m-d H:i:s",$booking->created_at);
                    $now = date("Y-m-d H:i:s");
                    $now = Carbon::createFromFormat("Y-m-d H:i:s",$now);
                    if ($booking_date->addMinutes(10)->lt($now) && User::find($booking->by_user_id)->is_front_user()) {
                        if ($booking->invoice->status == 'pending') {
                            $booking->cancel_booking();
                        } else if ($booking->invoice->status == 'cancelled') {
                            $booking->cancel_booking();
                        }
                    }
                }
            }
        }
    }
}