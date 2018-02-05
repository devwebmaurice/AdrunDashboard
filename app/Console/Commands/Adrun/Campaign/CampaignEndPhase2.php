<?php

namespace App\Console\Commands\Adrun\Campaign;

use Illuminate\Console\Command;
use App\Models\Adrun\AdrunCampaignModel;
use Carbon\Carbon;
use DateTime;
use App\Models\Adtech\AdtechReportModel;
use App\Models\SingleBilanModel;
use App\Models\Mail\ReportModel;
use App\Models\Adrun\AdrunReportModel;

class CampaignEndPhase2 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adrun:campaignEndPhase2';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creation des fichiers xml + Acquisition report ADTECH';
    
    private static $_instance;
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
        echo PHP_EOL ."---START MISSION--" . PHP_EOL;
        flush();
        
        $campaigns = AdrunCampaignModel::getInstance()->getCampaignTermineYesterday(2);
        $details = [];
        
        foreach( $campaigns as $campaign ):
            
            if($campaign->download === 0):
                
                $campaign->cname = strtoupper( $campaign->cname );
                echo PHP_EOL ."---$campaign->cname---" . PHP_EOL;
                
                //Get flight from Master
                $flights    = AdrunReportModel::getInstance()->getSlaveByMasterId($campaign->id_adtech);
                $report_res = AdtechReportModel::getInstance()->generateReport($flights,$campaign->id_adtech);
                
               AdrunCampaignModel::getInstance()->setADRUNDownload($campaign->id,1);
               
            endif;
            
        endforeach;
        
        
        echo PHP_EOL ."---MISSION COMPLETED---" . PHP_EOL;
        flush();
        
    }
}
