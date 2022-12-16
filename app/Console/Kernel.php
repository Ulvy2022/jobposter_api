<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{

    protected function schedule(Schedule $schedule)
    {
        $schedule->call('App\Http\Controllers\JobsPosterController@setJobToExpired')->daily();
        $schedule->call('App\Http\Controllers\MailController@mailToNotifyUserSub')->everyMinute();
        $schedule->call('App\Http\Controllers\FeatureTicketController@restoreCharge')->everyMinute();
        $schedule->call('App\Http\Controllers\SubscribeController@setSubToExpired')->everyMinute();

    }


    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
