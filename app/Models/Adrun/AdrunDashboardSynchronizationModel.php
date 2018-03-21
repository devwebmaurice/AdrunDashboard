<?php

namespace App\Models\Adrun;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Config;

use App\Models\Adrun\AdrunADTECHModel;
use App\Models\Adrun\AdrunCampaignModel;
use App\Models\Adrun\AdrunADTECHCampaignModel;

use DB;
use stdClass;
use Carbon\Carbon;


class AdrunDashboardSynchronizationModel extends Model
{
    
    private static $_instance; // L'attribut qui stockera l'instance unique
    private $tbl_campaign;
    
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
        $this->tbl_campaign   = \Config::get('adrun.table.campaign');
        $this->tbl_advertiser = \Config::get('adrun.table.advertiser');
        $this->tbl_report_sum = \Config::get('adrun.table.TBL_REPORT_SUMMARY');
        
    }
    
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    
    public function action($campaigns)
    {
        
        echo $this->clearCampaignPhase( $campaigns );
        flush();
        ob_flush();
        echo $this->checkCampaignStatusPhase( $campaigns );
        flush();
        ob_flush();
        
        
    }
    
    private function searchForCampaign($campaign, $array) {
        
        foreach ($array as $key => $val) {
            
            if ($val === $campaign->id_adtech) {
                return true;
            }
            
        }
        
        return false;
    }
    
    private function clearCampaignPhase($campaigns) {
        
        foreach ( $campaigns['adrun'] as $campaign ):
            
            $resp = $this->searchForCampaign($campaign, $campaigns['adtech']);
            
            if($resp === false):
                
                AdrunCampaignModel::getInstance()->deleteADRUNCampaign($campaign->id);
            
            endif;
        
        endforeach;
        
        return "<hr/>--PHASE ONE  COMPLETED--<hr/>";
        
    }
    
    private function checkCampaignStatusPhase($campaigns) {
        
        $i=0;
        foreach ( $campaigns['adrun'] as $campaign ):
            
            $i++;
            $data = AdrunADTECHCampaignModel::getInstance()->getCampaignById($campaign->id_adtech);
            
            echo "<hr/>--{$i}-{$campaign->id}--<hr/>";
            flush();
            ob_flush();
            
            if( $campaign->lastModifiedAt != $data->return->lastModifiedAt ):
                
                AdrunCampaignModel::getInstance()->SYNCADRUNCampaign($campaign->id,$data);
                echo "<hr/>--MODIF--<hr/>";
                
            endif;
            
            if($i === 100):
               
                break;
                
            endif;
            
        
        endforeach;
        
        
        return "<hr/>--PHASE TWO  COMPLETED--<hr/>";
    }
    
    
}
