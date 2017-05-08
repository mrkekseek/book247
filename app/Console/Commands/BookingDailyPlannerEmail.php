<?php

namespace App\Console\Commands;

use App\ShopLocations;
use App\ShopResourceCategory;
use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;
use App\Booking;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Maatwebsite\Excel\Facades\Excel;
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
    protected $description = 'This task creates the booking list for courts and send it to admins and employees';

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
        URL::forceRootUrl( Config::get('constants.globalWebsite.baseUrl') );

        $today = Carbon::today()->format('Y-m-d');
        $email_body = '<strong>Booking summary for '.Carbon::today()->format('d-m-Y').' generated at '.Carbon::now()->format('H:i').'</strong> <br /><br />';

        $allCategories = ShopResourceCategory::orderBy('name','asc')->get();
        $categories = [];
        if(sizeof($allCategories)>0){
            foreach($allCategories as $singleCategory){
                $categories[$singleCategory->id] = $singleCategory->name;
            }
        }

        $shopLocations = ShopLocations::with('resources')->with('address')->where('visibility','public')->orderBy('name','asc')->get();
        if ($shopLocations){
            $bookingsData = [];
            foreach($shopLocations as $singleLocation){
                $bookingsData[$singleLocation->id][] = [
                    'From',
                    'To',
                    'Member Name',
                    'Resource Name',
                    'Activity',
                    'Payment Type'
                ];

                $location_name = $singleLocation->name;
                $resource_name = [];
                if (sizeof($singleLocation->resources)>0){
                    foreach($singleLocation->resources as $singleResource){
                        $resource_name[$singleResource->id] = $singleResource->name;
                    }
                }

                $email_body.=' Location : '.$location_name.' bookings : <br />';
                $bookings = Booking::with('for_user')->with('resource')
                    ->where('location_id','=',$singleLocation->id)
                    ->where('date_of_booking','=',$today)
                    ->where('status','=','active')
                    ->orderBy('booking_time_start','asc')
                    ->get();
                if (sizeof($bookings)>0){
                    $email_body.=' -- '.sizeof($bookings).' bookings so far -- <br />';
                    foreach($bookings as $booking){
                        $playerName = $booking->by_user->first_name.' '.$booking->by_user->middle_name.' '.$booking->by_user->last_name;
                        $resourceName = isset($resource_name[$booking->resource_id])?$resource_name[$booking->resource_id]:'unknown';
                        /*$email_body.= '- '.Carbon::createFromFormat('H:i:s',$booking->booking_time_start)->format('H:i').' to '.Carbon::createFromFormat('H:i:s',$booking->booking_time_stop)->format('H:i').' ;
                        Player : '.$playerName.' ;
                        Room : '.$resourceName.' ;
                        Activity : '.(isset($categories[$booking->resource->category_id])?$categories[$booking->resource->category_id]:'unknown').' ;
                        Payment Type : '.$booking->payment_type.' <br />';*/
                        $bookingsData[$singleLocation->id][] = [
                            Carbon::createFromFormat('H:i:s',$booking->booking_time_start)->format('H:i'),
                            Carbon::createFromFormat('H:i:s',$booking->booking_time_stop)->format('H:i'),
                            $playerName,
                            $resourceName,
                            (isset($categories[$booking->resource->category_id])?$categories[$booking->resource->category_id]:'unknown'),
                            $booking->payment_type,
                        ];
                    }
                }
                else{
                    $email_body.=' -- No bookings so far -- <br />';
                }

                // we prepare the attachment
                $locationBookings = $bookingsData[$singleLocation->id];
                $fileName = $singleLocation->name.' bookings for '.Carbon::today()->format('d-m-Y');
                $fileLocation = $singleLocation->id.'_bookings_'.Carbon::today()->format('d-m-Y');
                Excel::create( $fileLocation, function($excel) use($locationBookings) {
                    $excel->sheet('Today Bookings', function($sheet) use($locationBookings) {
                        $sheet->fromArray($locationBookings);
                    });
                })->save();
                $listOfBookings[$singleLocation->id] = [
                    'location'  => 'storage/exports/'.$fileLocation,
                    'name'      => $fileName
                ];
            }
            $main_message = $email_body.
                            '<br />Sincerely,<br>Book247 Team. <br /><br />'.
                            '<small><strong>***** Email confidentiality notice *****</strong><br />'.
                            'This message is private and confidential. If you have received this message in error, please notify us and remove it from your system.</small>';

            $allAdmins = User::
                WhereHas('roles', function($query){
                    $query->where('name', '=', 'owner');
                })
                /*->orWhereHas('roles', function($query){
                    $query->where('name', '=', 'manager');
                })*/
                //->take('1')
                ->get();

            foreach($allAdmins as $single){
                $top_title_message = 'Dear <span>'.$single->first_name.' '.$single->middle_name.' '.$single->last_name .'</span>,';
                $beautymail = app()->make(Beautymail::class);
                $beautymail->send('emails.email_default',
                    ['body_header_title'=>$top_title_message, 'body_message' => $main_message],
                    function($message) use ($single, $listOfBookings) {
                        $message
                            ->from(Config::get('constants.globalWebsite.system_email'))
                            ->to($single->email, $single->first_name.' '.$single->middle_name.' '.$single->last_name)
                            ->subject(Config::get('constants.globalWebsite.email_company_name_in_title').' -  morning bookings summary for all locations - '.Carbon::today()->format('d-m-Y'));
                        foreach($listOfBookings as $val){
                            $message->attach($val['location'].'.xls', ['as'=>$val['name'].'.xls', 'mime' => 'application/vnd.ms-excel']);
                        }
                    }
                );
            }
        }
    }
}
