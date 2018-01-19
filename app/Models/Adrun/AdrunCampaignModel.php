<?php

namespace App\Models\Adrun;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Config;

use App\Models\Adrun\AdrunADTECHModel;
use DB;
use stdClass;

use Carbon\Carbon;

class AdrunCampaignModel extends Model
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
    
    public function getStartCampaignHome()
    {
        
        $campaigns = DB::table($this->tbl_campaign.' AS c')
            ->join($this->tbl_advertiser.' AS a', 'c.advertiserId', '=', 'a.id_adtech')
            //->leftJoin('adrun_report_summary AS r', 'c.id_adtech', '=', 'r.campaignId')
            ->select('c.id_adtech AS id_adtech','c.id AS id','c.name AS cname','a.name AS aname','c.adrunStartDate AS start','c.adrunEndDate AS end')
            //->whereDate('absoluteStartDate', DB::raw('CURDATE()'))
            //->groupBy('r.campaignId')
            ->orderBy('c.id', 'desc')
            ->limit(200)
            ->get();
        
        return $campaigns;
        
        
    }
    
    public function getAllCampaignMasterFix()
    {
        $campaigns = DB::table($this->tbl_campaign.' AS c')
            ->select('c.id_adtech AS id_adtech','c.id AS id')
            ->where([
                ['natureType','=', 0],
                ['masterCampaignId','=', 0],
            ])
            ->orderBy('c.id', 'desc')
            ->get();
        
        return $campaigns;
        
    }
    
    
    public function getCampaignTermine()
    {
        
        $campaigns = DB::table($this->tbl_campaign.' AS c')
            ->join($this->tbl_advertiser.' AS a', 'c.advertiserId', '=', 'a.id_adtech')
            //->leftJoin('adrun_report_summary AS r', 'c.id_adtech', '=', 'r.campaignId')
            ->select('c.id_adtech AS id_adtech','c.id AS id','c.name AS cname','a.name AS aname','c.adrunStartDate AS start','c.adrunEndDate AS end')
            ->whereDate('absoluteEndDate',  '<', Carbon::now()->subDays(1)->endOfDay())
            //->groupBy('r.campaignId')
            ->orderBy('c.id', 'desc')
            ->limit(200)
            ->get();
        
        return $campaigns;
        
    }
    
    public function getCampaignTermineYesterday($date = 14)
    {
        $campaigns = DB::table($this->tbl_campaign.' AS c')
            ->join( $this->tbl_advertiser.' AS a', 'c.advertiserId', '=', 'a.id_adtech' )
            ->select('c.resultURL','c.download','c.id_adtech AS id_adtech','c.id AS id','c.name AS cname','a.name AS aname','c.adrunStartDate AS start','c.adrunEndDate AS end','c.absoluteStartDate','c.absoluteEndDate','a.categorie AS categorie')
            ->whereBetween('absoluteEndDate', array(Carbon::now()->subDays($date)->endOfDay(), Carbon::now()->subDays(1)->endOfDay()))
            ->where('masterCampaignId', '=', -1)
            ->orderBy('c.absoluteEndDate', 'desc')
            ->limit(200)
            ->get();
        
        if( $campaigns->isEmpty() ):
            
            $date++;
        
            $campaigns = $this->getCampaignTermineYesterday( $date );
        
        endif;
        
        return $campaigns;
        
        
    }
    
    
    public function getCampaignHome() 
    {
        
        $campaigns = DB::table($this->tbl_campaign.' AS c')
            ->join($this->tbl_advertiser.' AS a', 'c.advertiserId', '=', 'a.id_adtech')
            ->select('c.id AS id','c.name AS cname','a.name AS aname','c.adrunStartDate AS start','c.adrunEndDate AS end')
            ->orderBy('c.id', 'desc')
            ->limit(50)
            ->get();
        
        return $campaigns;
    }
    
    public function getADRUNCampaignIDList() 
    {
        
        $campaigns = DB::table($this->tbl_campaign.' AS c')
            ->select('c.id AS id','c.id_adtech','c.adrunStartDate','c.adrunEndDate')
            ->orderBy('c.id', 'desc')
            ->get();
        
        return $campaigns;
        
    }
    
    public function getADRUNCampaignLastestEnd()
    {
        $campaigns = DB::table($this->tbl_campaign.' AS c')
            ->select('c.id AS id','c.id_adtech','c.adrunStartDate','c.adrunEndDate','c.xml','c.name')
            ->where([
                ['adrunEndDate','=', Carbon::now()->subDays(1)->endOfDay()],
                
            ])
            ->orderBy('c.id', 'desc')
            ->get();
        
        return $campaigns;
    }
    
    
    public function getADRUNCampaignIDListXML($status = 0,$day = 90) 
    {
        
        $campaigns = DB::table($this->tbl_campaign.' AS c')
            ->select('c.id AS id','c.id_adtech','c.adrunStartDate','c.adrunEndDate','c.xml')
            ->where([
                ['xml','=', $status],
                ['adrunEndDate','>', Carbon::now()->subDays($day)],
                
            ])
            ->orderBy('c.id', 'desc')
            ->get();
        
        return $campaigns;
        
    }
    
    public function updateADRUNCampaignMaster($id,$masterCampaignId,$natureType) 
    {
        DB::table($this->tbl_campaign)
            ->where('id', $id)
            ->update(['masterCampaignId' => $masterCampaignId,'natureType' => $natureType]);
        
    }
    
    public function setADRUNCampaignTime() 
    {
        
        $campaigns = DB::table($this->tbl_campaign.' AS c')
            ->select('c.id AS id','c.absoluteStartDate AS start','c.absoluteEndDate AS end','c.adrunEndDate AS checker')
            ->orderBy('c.id', 'desc')
            ->get();
        
        foreach($campaigns as $campaign):
            
           // if($campaign->checker === null):
                
                $format         = "Y-m-d H:i:s"; //or something else that date() accepts as a format
                $adrunEndDate   = date_format(date_create($campaign->end), $format); 
                $adrunStartDate = date_format(date_create($campaign->start), $format);
                
                DB::table($this->tbl_campaign)->where('id', $campaign->id)->update(['adrunEndDate' => $adrunEndDate,'adrunStartDate' => $adrunStartDate]);
            
                echo $campaign->id."<hr/>";
                ob_flush();
                flush();
                
            //endif;
            
            
        endforeach;
        
        return $campaigns;
        
    }
    
    
    public function checkCampaignADRUN($id)
    {
        $campaign = DB::table($this->tbl_campaign)->where('id_adtech', '=', $id)->first();
        
        if(is_null($campaign)):
            
            return FALSE;
        
        endif;
        
        return TRUE;
    }
    
    public function addSummaryAdrun($data)
    {
        $adrun_day = Carbon::parse($data->day)->format('Y-m-d 23:59:59');
        
        //To change table name
        $id = DB::table('adrun_report_summary')->insertGetId(
            [ 'campaignId'    => $data->campaignId, 
              'websiteId'     => $data->websiteId,
              'imps'          => $data->imps, 
              'clicks'        => $data->clicks,
              'transactions'  => $data->transactions, 
              'adtech_day'    => $data->day,
              'adrun_day'     => $adrun_day, 
                
            ]
            );
        
        
        return $id;
    }

    public function addCampaignToADRUN($data)
    {
        
        $campaign       = $data->return;
        $format         = "Y-m-d H:i:s"; //or something else that date() accepts as a format
        $placement      = (isset($campaign->placementIdList)) ? serialize($campaign->placementIdList) : "";
        $adrunEndDate   = date_format(date_create($campaign->absoluteEndDate), $format); 
        $adrunStartDate = date_format(date_create($campaign->absoluteStartDate), $format);
        
            $id = DB::table($this->tbl_campaign)->insertGetId(
            [ 'id_adtech'                   => $campaign->id, 
              'name'                        => $campaign->name,
              'absoluteEndDate'             => $campaign->absoluteEndDate, 
              'absoluteEndTimestamp'        => $campaign->absoluteEndTimestamp,
              'adrunEndDate'                => $adrunEndDate, 
              'absoluteStartDate'           => $campaign->absoluteStartDate,
              'absoluteStartTimestamp'      => $campaign->absoluteStartTimestamp, 
              'adrunStartDate'              => $adrunStartDate,
              'archiveDate'                 => $campaign->archiveDate, 
              'archiveStatus'               => $campaign->archiveStatus,
              'advertiserId'                => $campaign->advertiserId, 
              'customerId'                  => $campaign->customerId,
              'placementIdList'             => $placement,
              'bannerTimeRangeList'         => serialize($campaign->bannerTimeRangeList), 
              'priority'                    => $campaign->priority,
              'campaignFeatures'            => serialize($campaign->campaignFeatures), 
              'campaignFrequencyCategory'   => $campaign->campaignFrequencyCategory,
              'campaignTypeCategoryId'      => $campaign->campaignTypeCategoryId,
              'campaignTypeId'              => $campaign->campaignTypeId, 
              'categoryId'                  => $campaign->categoryId,
              'dateRangeList'               => serialize($campaign->dateRangeList), 
              'description'                 => $campaign->description,
              'extId'                       => $campaign->extId, 
              'statusTypeId'                => $campaign->statusTypeId,
              'treeTypeId'                  => $campaign->treeTypeId,
                'natureType'                  => $campaign->natureType,
                'masterCampaignId'            => $campaign->masterCampaignId,
              'rateTypeId'                  => $campaign->rateTypeId,
              'createdAt'                   => $campaign->createdAt, 
              'createdBy'                   => $campaign->createdBy,
              'lastModifiedBy'              => $campaign->lastModifiedBy, 
              'lastModifiedAt'              => $campaign->lastModifiedAt
                
            ]
            );
            
            return $id;
        
    }
    
    public function setADRUNReportXML($id,$status) 
    {
        
        DB::table($this->tbl_campaign.' AS c')
            ->where('id', $id)
            ->update(['xml' => $status]);
        
    }
    
    public function getLastDateReportDone()
    { 
        //To change table name
        $last = DB::table('adrun_report_summary')->select('adrun_day')->orderBy('adrun_day', 'desc')->first();
        
        return $last;
    }
    
    public function getADRUNCampaignByID($id)
    { 
        //To change table name
        $campaign = DB::table($this->tbl_campaign.' AS c')
            ->join($this->tbl_advertiser.' AS a', 'c.advertiserId', '=', 'a.id_adtech')
            ->select('a.id_adtech AS aid_adtech','c.id_adtech AS cid_adtech','c.name AS cname','a.name AS aname','c.description AS cdescription','c.adrunStartDate AS start','c.adrunEndDate AS end','a.*','c.*')
            ->where('c.id', '=', $id)->first();
        
        return $campaign;
    }
    
    public function getADRUNSlaveCampaignByMID($mid)
    {
        $campaigns = DB::table($this->tbl_campaign.' AS c')
            ->select('c.*')
            ->where('c.masterCampaignId', '=', $mid)
            ->orderBy('c.id', 'asc')
            ->get();
        
        return $campaigns;
    }
    
    public function getADRUNSlaveSumBySID($sid)
    {
        
        $sum = DB::table($this->tbl_report_sum.' AS r')
            ->select(DB::raw('SUM(r.imps) AS timps'),DB::raw('SUM(r.clicks) AS tclicks'),DB::raw('SUM(r.clicks)/SUM(r.imps) * 100 AS percentage') )
            ->where([
                ['r.campaignId','=', $sid],
                
            ])
            ->groupBy('r.campaignId')
            ->first();
        
        return $sum;
    }
    
    public function addADRUNReportMasterUVID($c_id,$r_id)
    {
        DB::table( $this->tbl_campaign ) ->where( 'id_adtech', $c_id ) ->update(['masterReportUVId' => $r_id]);
        
        
    }
    
    public function getADRUNReportMasterUVStatut($id)
    {
        
        $res = DB::table( $this->tbl_campaign ) ->select( 'masterReportUVId' ) ->where([ ['id_adtech','=', $id], ])->first();
        
        if($res->masterReportUVId === 0): return false; else: return true; endif;
        
    }
    
    
    public function getADRUNMasterUVID()
    {
        
        $campaigns = DB::table( $this->tbl_campaign ) ->select( '*' ) ->where([ ['masterReportUVId','!=', 0 ], ])->get();
        
        return $campaigns;
        
    }
    
}
