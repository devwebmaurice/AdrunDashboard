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
        '\App\Console\Commands\Adrun\ScanXmlMasterUV',
        
        '\App\Console\Commands\Adrun\Campaign\CampaignEndPhase1',
        '\App\Console\Commands\Adrun\Campaign\CampaignEndPhase2',
        '\App\Console\Commands\Adrun\Campaign\CampaignEndPhase3',
        '\App\Console\Commands\Adrun\Campaign\CampaignEndPhase4',
        '\App\Console\Commands\Adrun\Campaign\CampaignTestAction',
        
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
         
        $schedule->command('adrun:campaignEndPhase1')
                  ->dailyAt('0:05');
        
        $schedule->command('adrun:campaignEndPhase2')
                  ->dailyAt('0:15');
        
        $schedule->command('adrun:campaignEndPhase3')
                  ->dailyAt('0:35');
        
        $schedule->command('adrun:campaignEndPhase4')
                  ->dailyAt('1:35');
         
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
