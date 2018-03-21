<?php

//Jacques D. L. Rims

namespace App\Console\Commands\Adrun;

use Illuminate\Console\Command;

use App\Models\Adrun\AdrunADTECHCampaignModel;
use App\Models\Adrun\AdrunCampaignModel;
use App\Models\Mail\ReportModel;

class Campaign extends Command
{
    private static $_instance;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adrun:campaignSync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Campaign';

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    
    
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
        
        echo PHP_EOL ."---CAMPAIGN SYNC START---" . PHP_EOL;
        flush();
        
        //Update time format
        //AdrunCampaignModel::getInstance()->setADRUNCampaignTime();
        
        $ADRUN_campaigns       = AdrunCampaignModel::getInstance()->getADRUNCampaignIDList();
        $ADTECH_campaigns      = AdrunADTECHCampaignModel::getInstance()->getADTECHCampaignIDList()->return;
        
        $campaigns             = [ ];
        $campaigns['adrun']    = $ADRUN_campaigns;
        $campaigns['adtech']   = $ADTECH_campaigns;
        
        $total_adrun_campaign  = count( $ADRUN_campaigns );
        $total_adtech_campaign = count( $ADTECH_campaigns );
        
        echo PHP_EOL ."ADRUN:  {$total_adrun_campaign}" . PHP_EOL;
        flush();
        
        echo PHP_EOL ."ADTECH: {$total_adtech_campaign}" . PHP_EOL;
        flush();
        
        if( $total_adtech_campaign > $total_adrun_campaign ):
            
            foreach ($ADTECH_campaigns as $campaign):
            
                $resp = AdrunCampaignModel::getInstance()->checkCampaignADRUN($campaign);
        
                if(!$resp):
                    
                    $data = AdrunADTECHCampaignModel::getInstance()->getCampaignById($campaign);
                    $arr  = (array) $data;
                    
                    if (!empty($arr)):
                        $add  = AdrunCampaignModel::getInstance()->addCampaignToADRUN($data);
                        
                        echo PHP_EOL ."CAMPAIGN: {$arr["return"]->name}" . PHP_EOL;
                        flush();
                        
                    else:  
                        
                        AdrunADTECHCampaignModel::getInstance()->deleteCampaignById($campaign);
                    
                    endif;
                    
                endif;
            
            endforeach;
            
            
        endif;
        
        return $campaigns;
        
    }
}
