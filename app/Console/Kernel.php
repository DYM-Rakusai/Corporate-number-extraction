<?php

namespace App\Console;

use Illuminate\Support\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;


class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $nowTimeData = Carbon::now('Asia/Tokyo')->format('Y-m-d H:i');
        $schedule->command('command:remind "'             . $nowTimeData . '"')->everyMinute();
        $schedule->command('command:resendSms "'          . $nowTimeData . '"')->everyMinute();
        $schedule->command('command:switchNoAnswer',     [7, $nowTimeData])->everyMinute();
        $schedule->command('command:deletePastSchedule "' . $nowTimeData . '"')->dailyAt('00:00');
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
