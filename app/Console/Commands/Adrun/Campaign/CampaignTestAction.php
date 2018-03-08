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

class CampaignTestAction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adrun:campaignTestAction';

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
        
        $this->format         = \Config::get('adrun.report.xml');
        $this->local_path     = ( $_SERVER['APP_ENV'] === 'local' ) ? storage_path().'/test' : '/var/www/html/adrun/services/dashboard/storage/report/request';
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
    public function handle($id)
    {
        echo PHP_EOL ."---START MISSION--" . PHP_EOL;
        flush();
        
        $campaign = AdrunCampaignModel::getInstance()->getADRUNCampaignByID($id);
        $xml      = simplexml_load_file($campaign->resultURL . $this->format);
        $vu       = preg_replace('/\s+/','', $xml->table->row->cell[10][0]);
        $clic     = preg_replace('/\s+/','',$xml->table->row->cell[8][0]);
        $imps     = preg_replace('/\s+/','',$xml->table->row->cell[4][0]);
        
        $rep = [ 
                'vu'   => $vu,
                'clic' => $clic,
                'imps' => $imps,
                ];
            
        $fac = SingleBilanModel::getInstance()->createBilan($campaign->id, $rep, 'test');
        
        if( !is_null( $fac ) ): $details[] = $fac; endif;
                
        
        if(!empty($details)):
            
            ReportModel::getInstance()->sendDailyEndOfCampaignReport($details);
            
        endif;
        
        echo PHP_EOL ."---MISSION COMPLETED---" . PHP_EOL;
        flush();
        
    }
}


