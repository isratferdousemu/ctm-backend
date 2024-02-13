<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected $commands = [
     
        \App\Console\Commands\AddFinancialYear::class
    ];
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // // Run the command on every minutes
         $schedule->command('financial-year:add')->everyMinute();
         // Run the command ochaeck work or not
           
            // $schedule->command('financial-year:add')->cron('52
            //  13 12 2 *');

            // Run the command on June 1st
            // $schedule->command('financial-year:add')->cron('0 0 1 6 *');
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
