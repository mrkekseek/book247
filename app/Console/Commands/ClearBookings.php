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

            $bookings = Booking::where('status','active')->get();
            $bookings_array = [];
            foreach ($bookings as $booking) {
                $bookings_array[$booking->id] = $booking;
                $to_check[$booking->id] = true;
            }
            foreach ($bookings as $booking) {
                if (isset($to_check[$booking->id])) {
                    //time check
                    $booking_date = Carbon::createFromFormat("Y-m-d H:i:s",$booking->created_at);
                    $now = date("Y-m-d H:i:s");
                    $now = Carbon::createFromFormat("Y-m-d H:i:s",$now);
                    if ($booking_date->addMinutes(10)->lt($now) && User::find($booking->by_user_id)->is_front_user()) {
                        echo $booking->id.'<br>';
                        $booking_invoice = BookingInvoice::where('booking_id',$booking->id)->first();
                        if ($booking_invoice) {;
                            $booking_invoice_items = BookingInvoiceItem::where('booking_invoice_id',$booking_invoice->id)->get();
                            if ($booking_invoice->status == 'completed') {
                                foreach ($booking_invoice_items as $item) {
                                    unset($to_check[$item->booking_id]);
                                }
                            } else if ($booking_invoice->status == 'pending') {
                                foreach ($booking_invoice_items as $item) {
                                    $bookings_array[$item->booking_id]->cancel_booking();
                                    unset($to_check[$item->booking_id]);
                                }
                            } else if ($booking_invoice->status == 'cancelled') {
                                foreach ($booking_invoice_items as $item) {
                                    $bookings_array[$item->booking_id]->cancel_booking();
                                    unset($to_check[$item->booking_id]);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
}