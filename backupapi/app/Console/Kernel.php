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
        '\App\Console\Commands\AppointmentPushNotifications',
        '\App\Console\Commands\ActivityPushNotifications',
        '\App\Console\Commands\ResetWrongAttempt',
        '\App\Console\Commands\AppointmentStatusChange',
        '\App\Console\Commands\AppointmentReminder',
        '\App\Console\Commands\PaymentReminder',
        '\App\Console\Commands\PartnerShipReminder',
        '\App\Console\Commands\WeeklyDoctorSettlement',
        '\App\Console\Commands\BiWeeklyDoctorSettlement',
        '\App\Console\Commands\MonthlyDoctorSettlement',
        '\App\Console\Commands\PaymentStatusCheck',

    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('AppointmentPushNotifications:notify')->everyFiveMinutes();
        $schedule->command('ActivityPushNotifications:notify')->everyFiveMinutes();
        $schedule->command('ResetWrongPasswordAttempts:run')->everyFiveMinutes();
        $schedule->command('AppointmentStatusChange:cron')->everyFiveMinutes();
        $schedule->command('AppointmentReminder:cron')->hourly();
        $schedule->command('PaymentReminder:cron')->hourly();
        $schedule->command('PartnerShipReminder:cron')->dailyAt('09:00');
        $schedule->command('PaymentStatusInquiry:check')->everyFiveMinutes();
        
        $schedule->command('WeeklyDoctorSettlement:cron')->weekly();
        $schedule->command('BiWeeklyDoctorSettlement:cron')->twiceMonthly(1, 16, '00:00');
        $schedule->command('MonthlyDoctorSettlement:cron')->monthly();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
