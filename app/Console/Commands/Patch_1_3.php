<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Booking;
use App\UserBookedActivity;


class Patch_1_3 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'patch:number1_3';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Add bookings in users_booked_activities';

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
        echo 'Enter patch 1.3 '.PHP_EOL;
        $nr = 0;    $all = 0;

        $booking = Booking::with('resource')->orderBy('date_of_booking','ASC')->groupBy('for_user_id','resource_id')->get();
        foreach ($booking as $item)
        {
            $all++;
            $userBookedActivity = UserBookedActivity::firstOrNew([
                'user_id' => $item->for_user_id,
                'activity_id' => $item->resource->category_id,
            ]);

            if ( ! $userBookedActivity->exists )
            {
                $nr++;
                $userBookedActivity->save();

                if ($nr%100 == 0){
                    echo 'Found so far : '.$nr.' new users without activities. Processed : '.$all.' @@ '.PHP_EOL;
                }
            }
        }

        echo 'Exiting patch 1.3 - processed : '.$all.' and found new : '.$nr.' membebers that were added to activity. '.PHP_EOL;
    }
}
