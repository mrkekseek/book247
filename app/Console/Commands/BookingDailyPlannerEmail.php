<?php

namespace App\Console\Commands;

use App\ShopLocations;
use App\ShopResourceCategory;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Booking;
use Snowfire\Beautymail\Beautymail;
use Illuminate\Support\Facades\Config;

class BookingDailyPlannerEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'booking:daily_morning_bookings_plan';

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
        $today = Carbon::today()->format('Y-m-d');
        $email_body = '<h2>Bookings summary for '.Carbon::today()->format('d-m-Y').' generated at '.Carbon::now()->format('H:i:s').'</h2>';

        $allCategories = ShopResourceCategory::orderBy('name','asc')->get();
        $categories = [];
        if(sizeof($allCategories)>0){
            foreach($allCategories as $singleCategory){
                $categories[$singleCategory->id] = $singleCategory->name;
            }
        }

        $shopLocations = ShopLocations::with('resources')->with('address')->where('visibility','public')->orderBy('name','asc')->get();
        if ($shopLocations){
            foreach($shopLocations as $singleLocation){
                $location_name = $singleLocation->name;
                $resource_name = [];
                if (sizeof($singleLocation->resources)>0){
                    foreach($singleLocation->resources as $singleResource){
                        $resource_name[$singleResource->id] = $singleResource->name;
                    }
                }

                $email_body.='<h3> Location : '.$location_name.' bookings : </h3>';
                $bookings = Booking::with('for_user')->with('resource')
                    ->where('location_id','=',$singleLocation->id)
                    ->where('date_of_booking','=',$today)
                    ->where('status','=','active')
                    ->orderBy('booking_time_start','asc')
                    ->get();
                if (sizeof($bookings)>0){
                    foreach($bookings as $booking){
                        $playerName = $booking->by_user->first_name.' '.$booking->by_user->middle_name.' '.$booking->by_user->last_name;
                        $resourceName = isset($resource_name[$booking->resource_id])?$resource_name[$booking->resource_id]:'unknown';
                        $email_body.= '- '.Carbon::createFromFormat('H:i:s',$booking->booking_time_start)->format('H:i').' to '.Carbon::createFromFormat('H:i:s',$booking->booking_time_stop)->format('H:i').' ;
                        Player : '.$playerName.' ;
                        Room : '.$resourceName.' ;
                        Activity : '.(isset($categories[$booking->resource->category_id])?$categories[$booking->resource->category_id]:'unknown').' ;
                        Payment Type : '.$booking->payment_type.' <br />';
                    }
                }
                else{
                    $email_body.=' -- No bookings so far -- ';
                }
            }

            //echo $email_body; exit;

            $beautymail = app()->make(Beautymail::class);
            $beautymail->send('emails.booking.all_today_morning_summary',
                ['bookings'=>$email_body, 'logo' => ['path' => 'http://sqf.se/wp-content/uploads/2012/12/sqf-logo.png']],
                function($message) use ($bookings)
                {
                    $message
                        ->from(Config::get('constants.globalWebsite.system_email'))
                        ->to('stefan.bogdan@ymail.com', 'Booking System Admin')
                        //->to('stefan.bogdan@ymail.com', $player->first_name.' '.$player->middle_name.' '.$player->last_name)
                        ->subject('Booking System - morning bookings summary for all locations - '.Carbon::today()->format('d-m-Y'));
                });
        }
    }
}
