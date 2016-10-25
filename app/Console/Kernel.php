<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

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
            ->everyFiveMinutes();

        $schedule->command('userMembership:planned_actions_check')
            //->everyMinute()
            ->dailyAt('00:01')
            ->sendOutputTo('storage/logs/PlannedActions.log');

        $schedule->command('userFinance:issue_pending_invoices')
            //->everyMinute()
            ->dailyAt('01:00')
            ->sendOutputTo('storage/logs/PendingInvoice_output.log');

        $schedule->command('booking:daily_morning_bookings_plan')
            //->everyMinute()
            ->dailyAt('06:00')
            ->sendOutputTo('storage/logs/BookingDailyPlanner_output.log');
    }
}
