<?php

namespace App\Console\Commands\adrun;

use Illuminate\Console\Command;
use App\Models\Adrun\AdrunCampaignModel;
use Carbon\Carbon;
use DateTime;
use App\Models\Adtech\AdtechReportModel;
use App\Models\SingleBilanModel;
use App\Models\Mail\ReportModel;
use App\Models\Adrun\AdrunReportModel;

use App\Console\Commands\adrun\Advertiser;
use App\Console\Commands\Adrun\Campaign;
use App\Console\Commands\Adrun\CampaignEndReport;

class Dashboard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adrun:dashboardSync';
    

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

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        
        $time        = (int) Carbon::now()->format('i');
        $report_time = date("H:i"); 
        
        Advertiser::getInstance()->handle();
        Campaign::getInstance()->handle();
        
        if($report_time === '12:00' || $report_time === '00:00'):
            
            $datas['name']     = 'Jacques D. L. Rima';
            $datas['surname']  = 'devwebmaurice@adrun.re';
            
            ReportModel::getInstance()->sendReminderDashboard($datas);
            
        endif;
        
//        if($report_time === '02:00'):
//            
//            CampaignEndReport::getInstance()->handle();
//            
//        endif;
        
        
        
    }
}
