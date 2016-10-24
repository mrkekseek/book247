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
            ->sendOutputTo('PlannedActions.log');

        $schedule->command('userFinance:issue_pending_invoices')
            //->everyMinute()
            ->dailyAt('01:00')
            ->sendOutputTo('PendingInvoice_output.log');

        $schedule->command('booking:daily_morning_bookings_plan')
            //->everyMinute()
            ->twiceDaily('00:00','05:00')
            ->sendOutputTo('BookingDailyPlanner_output.log');
    }
}
