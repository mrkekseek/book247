<?php

namespace App\Console;

use Carbon\Carbon;
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
        Commands\BookingsCheck::class,
        Commands\UserMembershipPlannedActionsCheck::class,
        Commands\UserMembershipPendingInvoices::class,
        Commands\BookingDailyPlannerEmail::class,
        Commands\OptimizedMembersSearchRebuild::class,
        Commands\Patch_1_1::class,
        Commands\Patch_1_2::class,
        Commands\addMinimumPendingInvoices::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // check each 5 min for past bookings that were active and must be switched to checked-in or not
        $schedule->command('booking:check_past_bookings')
            ->withoutOverlapping()
            ->everyFiveMinutes()
            ->timezone('Europe/Oslo')
            ->appendOutputTo('storage/logs/CheckPastBookings.log');

        // check planned actions for existing and active memberships - twice at 00:10 and 00:15
        $schedule->command('userMembership:planned_actions_check')
            ->withoutOverlapping()
            ->cron('10,15 0 * * *')
            ->appendOutputTo('storage/logs/PlannedActions.log');

        // check planned invoices and issue the ones that are to be issued for today, and issue them - three times at 1:01, 1:06, 1:11
        $schedule->command('userFinance:issue_pending_invoices')
            ->withoutOverlapping()
            ->cron('1,6,11 1 * * *')
            ->timezone('Europe/Oslo')
            ->appendOutputTo('storage/logs/PendingInvoice_output.log');

        // send the location bookings for the day to each employee/manager/admin/owner - once per day at 06:00
        $schedule->command('booking:daily_morning_bookings_plan')
            //->everyMinute()
            ->withoutOverlapping()
            ->dailyAt('06:00')
            ->timezone('Europe/Oslo')
            ->appendOutputTo('storage/logs/BookingDailyPlanner_output.log');

        // send the location bookings for the day to each employee/manager/admin/owner - once per day at 02:00
        $schedule->command('userFinance:add_minimum_planned_pending_invoices')
            //->everyMinute()
            ->withoutOverlapping()
            ->dailyAt('00:01')
            ->timezone('Europe/Oslo')
            ->appendOutputTo('storage/logs/AddMinimumPlannedInvoices_output.log');

        /* Optimizations tasks - Start */

        // creates a table optimized for quick searches - once per day at 03:00
        $schedule->command('optimize:rebuild_members_search')
            //->everyMinute()
            ->dailyAt('03:00')
            ->timezone('Europe/Oslo')
            ->appendOutputTo('storage/logs/Optimize_rebuild_search_members_output.log');

        /* Optimizations tasks - Stop */
    }
}
