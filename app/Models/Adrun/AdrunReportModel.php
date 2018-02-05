<?php

namespace App\Models\Adrun;

use Illuminate\Database\Eloquent\Model;

use DB;
use stdClass;

class AdrunReportModel extends Model
{
    private static $_instance;
    
    public function __construct()
    {
        
        $this->tbl_campaign   = \Config::get('adrun.table.campaign');
        $this->tbl_advertiser = \Config::get('adrun.table.advertiser');
        $this->tbl_banner     = \Config::get('adrun.table.banner');
        $this->tbl_editeur    = \Config::get('adrun.table.editeur');
        $this->tbl_report_sum = \Config::get('adrun.table.TBL_REPORT_SUMMARY');
        
    }
    
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    
    public function setADTECHBanner($banners) 
    {
        if (!empty((array) $banners)):
        foreach($banners as $data):
            
            if (!empty((array) $data)):
                if (!empty($data) && is_array($data)):
                    
                $id = DB::table($this->tbl_banner)->insertGetId(
                [   'bannerExtId'     => $data->bannerExtId, 
                    'bannerId'        => $data->bannerId,
                    'bannerfilename'  => "$data->bannerfilename",
                    'bannername'      => "$data->bannername",
                    'campaignExtId'   => "$data->campaignExtId",
                    'campaignId'      => $data->campaignId,
                    'clicks'          => $data->clicks,
                    'impressions'     => $data->impressions,
                    'linkUrl'         => $data->linkUrl,
                    'views'           => $data->views,
                    'weight'          => $data->weight,
                ]
                );
                
                return 1;
                endif;
            endif;
        endforeach;
        endif;
        
        return 0;
    }
    
    public function getImpression($id)
    {
        
        $report = DB::table($this->tbl_report_sum.' AS c')
            ->select(DB::raw('SUM(imps) AS ti'))
            ->where([
                ['campaignId','=', $id],
                
            ])
            ->groupBy('campaignId')
            //->orderBy('c.id', 'desc')
            ->get();
        
        if(count($report) > 0):
            
            return $report[0]->ti;
        
        endif;
        
    
        
        return 0;
    }
    
    public function getSlaveByMasterId($id)
    {
        
        $ids = DB::table($this->tbl_campaign.' AS c')
            ->select('c.id','c.id_adtech')
            ->where([
                ['masterCampaignId','=', $id],
                
            ])
            ->get();
        
        $return = $ids;
        
        
        return $return;
    }
    
    
    public function getSlavesByMasterId($id)
    {
        
        $return = DB::table($this->tbl_campaign.' AS c')
            ->select('c.id','c.id_adtech')
            ->where([
                ['masterCampaignId','=', $id],
                
            ])
            ->get();
        
        return $return;
    }
    
    public function getImpressionPerWebsiteByCampaign($id)
    {
        $websites = DB::table($this->tbl_report_sum.' AS r')
            ->join($this->tbl_editeur.' AS e', 'r.websiteId', '=', 'e.id_adtech')
            ->join($this->tbl_campaign.' AS c', 'r.campaignId', '=', 'c.id_adtech')
             ->select('e.name AS editeur','r.*')
            ->where([
                ['r.campaignId','=', $id],
                
            ])
            ->get();
    
        return $websites;
    }
    
    public function getImpClickGroupByDate($master)
    {
        $values = DB::table($this->tbl_report_sum.' AS r')
            ->join($this->tbl_campaign.' AS c', 'r.campaignId', '=', 'c.id_adtech')
             ->select('c.masterCampaignId','r.*','r.adtech_day')
            ->where([
                ['c.masterCampaignId','=', $master],
                
            ])
            ->orderBy('r.adtech_day', 'desc')    
            ->get();
        
//        $values = \DB::select("select 'c.masterCampaignId','r.adtech_day','r.*' from `{$this->tbl_report_sum}` as `r` inner join
//                               `{$this->tbl_campaign}` as `c` on `r`.`campaignId` = `c`.`id_adtech` 
//                                WHERE (`c`.`masterCampaignId` = {$master}) order by `r`.`adtech_day` desc");
        
        
        return $values;
    }
    
    public function addReportId($report, $campaign)
    {
        
        DB::table($this->tbl_campaign)
            ->where('id_adtech', $campaign)
            ->update(['masterReportUVId' => $report]);
        
    }
    
    
    public function addResultURL($url, $campaign)
    {
        
        DB::table($this->tbl_campaign)
            ->where('id_adtech', $campaign)
            ->update(['resultURL' => $url]);
        
    }
    
    
}
