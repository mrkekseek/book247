<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Config;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Inspire::class,
        Commands\BookingsCheck::class,
        Commands\UserMembershipPlannedActionsCheck::class,
        Commands\UserMembershipPendingInvoices::class,
        Commands\BookingDailyPlannerEmail::class,
        Commands\OptimizedMembersSearchRebuild::class,
        Commands\Patch_1_1::class,
        Commands\Patch_1_2::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')
                 ->hourly();

        $schedule->command('booking:check_past_bookings')
            ->withoutOverlapping()
            ->everyFiveMinutes()
            ->timezone('Europe/Oslo')
            ->appendOutputTo('storage/logs/CheckPastBookings.log');

        $schedule->command('userMembership:planned_actions_check')
            //->everyMinute()
            ->withoutOverlapping()
            ->dailyAt('00:01')
            ->timezone('Europe/Oslo')
            ->appendOutputTo('storage/logs/PlannedActions.log');

        $schedule->command('userFinance:issue_pending_invoices')
            ->everyMinute()
            ->withoutOverlapping()
            //->everyFiveMinutes()
            ->timezone('Europe/Oslo')
            ->when(function(){
                  date_default_timezone_set('Europe/Oslo');
                  return date('H') >= 22 && date('i') > 4 && date('H') < 6;
            })
            ->appendOutputTo('storage/logs/PendingInvoice_output.log');

        $schedule->command('booking:daily_morning_bookings_plan')
            //->everyMinute()
            ->withoutOverlapping()
            ->dailyAt('06:00')
            ->timezone('Europe/Oslo')
            ->appendOutputTo('storage/logs/BookingDailyPlanner_output.log');

        /* Optimizations tasks - Start */
        $schedule->command('optimize:rebuild_members_search')
            //->everyMinute()
            ->dailyAt('03:00')
            ->timezone('Europe/Oslo')
            ->appendOutputTo('storage/logs/Optimize_rebuild_search_members_output.log');
        /* Optimizations tasks - Stop */
    }
}
