<?php

namespace App\Console\Commands\adrun;

use Carbon\Carbon;
use DateTime;
use Illuminate\Console\Command;
use App\Console\Commands\adrun\Advertiser;
use App\Console\Commands\Adrun\Campaign;
use App\Models\Adrun\AdrunDashboardSynchronizationModel;
use App\Models\Mail\ReportModel;

class Dashboard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adrun:dashboardSync';
    
    private static $_instance;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Dashboard';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }
    
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $time        = (int) Carbon::now()->format('i');
        $report_time = date("H:i");
        $sync_time   = date("i");
        
        Advertiser::getInstance()->handle();
        $campaigns = Campaign::getInstance()->handle();
        //AdrunDashboardSynchronizationModel::getInstance()->action($campaigns);
        
        if($report_time === '12:00' || $report_time === '00:00'):
            
            $datas['name']     = 'Jacques D. L. Rima';
            $datas['surname']  = 'devwebmaurice@adrun.re';
            
            ReportModel::getInstance()->sendReminderDashboard($datas);
            
        endif;
        
        if( $sync_time === '15' || $sync_time === '30' || $sync_time === '45' || $sync_time === '05'):
            
            AdrunDashboardSynchronizationModel::getInstance()->action($campaigns);
            
        endif;
        
        
    }
}
