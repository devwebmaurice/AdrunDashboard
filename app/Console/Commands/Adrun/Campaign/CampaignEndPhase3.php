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

class CampaignEndPhase3 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adrun:campaignEndPhase3';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';
    
    private static $_instance;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->local_path  = ( $_SERVER['APP_ENV'] === 'local' ) ? storage_path().'/test' : '/var/www/html/adrun/services/dashboard/storage/report/request';
        $this->request_folder = (is_dir( $this->local_path )) ? $this->local_path : mkdir( $this->local_path, 0777, true);
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
        
        foreach ( $campaigns as $campaign ):
            
            $master = $campaign->id_adtech.'_master_overview.xml';
            $uu     = $campaign->id_adtech.'_uu.xml';
            
            $xml         = file_get_contents($this->request_folder .'/'.$master, FILE_USE_INCLUDE_PATH);
            $string      = str_replace([ 'soap:','ns4:', 'ns2:' ],"", $xml);
            $xml         = simplexml_load_string($string);
            $report_id   = (int) $xml->Body->requestReportByEntitiesResponse->return->id;
            $campaign_id = (int) $xml->Body->requestReportByEntitiesResponse->return->entityId; 
            $add_report  = AdrunReportModel::getInstance()->addReportId($report_id,$campaign->id_adtech);
            $detail      = AdtechReportModel::getInstance()->getReportDetailsByID($report_id);
            $add_url     = AdrunReportModel::getInstance()->addResultURL("$detail->resultURL",$campaign->id_adtech);
            
            echo PHP_EOL . $report_id . PHP_EOL;
            
            
        endforeach;
        
        echo PHP_EOL ."---MISSION COMPLETED---" . PHP_EOL;
        flush();
        
    }
}
