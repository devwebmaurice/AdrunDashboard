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
        
        '\App\Console\Commands\Adrun\Editeur',
        '\App\Console\Commands\Adrun\Advertiser',
        '\App\Console\Commands\Adrun\Campaign',
        '\App\Console\Commands\Adrun\CampaignReports',
        '\App\Console\Commands\Adrun\CampaignEndReport',
        '\App\Console\Commands\Adrun\Dashboard',
        '\App\Console\Commands\Adrun\CampaignCLIControl',
        
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('adrun:dashboardSync')
                  ->everyMinute();
         
        $schedule->command('adrun:campaignEndReport')
                  ->dailyAt('0:55');
         
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
