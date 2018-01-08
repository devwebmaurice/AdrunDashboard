<?php

namespace App\Models\Adtech;

use Illuminate\Database\Eloquent\Model;
use App\Models\Adrun\AdrunADTECHModel;
use HeliosSoapClient;
use App\Models\Adrun\AdrunWebsiteModel;
use App\Models\Adrun\AdrunCampaignModel;
use App\Models\Adrun\AdrunReportModel;
use Carbon;
use DOMDocument;
use SimpleXMLElement;

include_once base_path().'/vendor/adrun/dir-adtech-includes/dir-api-clientsystem/conf/HeliosSoapClient.php';

class AdtechReportModel extends Model
{
    private static $_instance;
    
    public function __construct()
    {
        
        $this->token                 = AdrunADTECHModel::getInstance()->setADTECHToken();
        $this->adtech_server_url     = \Config::get('adrun.adtech.ADTECH_SERVER_URL');
        $this->adtech_server_url_end = \Config::get('adrun.adtech.ADTECH_SERVER_URL_END');
        $this->adtech_soap_version   = \Config::get('adrun.adtech.ADTECH_SOAP_VERSION');
        $this->emails                = \Config::get('adrun.emails');
        $this->url_v3                = $this->adtech_server_url."WSReportAdmin_v3".$this->adtech_server_url_end;
        $this->client                = new HeliosSoapClient($this->url_v3, $this->adtech_soap_version, $this->token);
        $this->adrun_store_report    = base_path() . "/vendor/adrun/reports";
        
        //Report Details
        $this->report_uu_id                      = 219;
        $this->report_entity_type_mastercampaign = 13;
        $this->report_category_campaign          = 100;
        $this->master_campaign_overview          = 86442;
        
        $this->local_path  = ( $_SERVER['APP_ENV'] === 'local' ) ? storage_path().'/report/request' : '/var/www/html/adrun/services/dashboard/storage/report/request';
        
        $this->request_folder = (is_dir( $this->local_path )) ? $this->local_path : mkdir( $this->local_path, 0777, true);
        
        if (!file_exists($this->adrun_store_report)) {  mkdir($this->adrun_store_report, 0755, true); }
        
    }
    
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    
    public function generateReport($id = 0, $trigger = NULL)
    {
        
        $campaign  = AdrunCampaignModel::getInstance()->getADRUNCampaignByID($id);
        $this->masterCampaignOverview( $campaign );
        
        $file_name = $campaign->cid_adtech.'_uu.xml';
        $file_url  = 'http://localhost/adrun-dashboard-v1-8/storage/report/request/'.$file_name;
        
            if(!file_exists($this->request_folder.'/'.$file_name)):
                
                $xml = '<soap:Envelope
                xmlns:soap="http://www.w3.org/2003/05/soap-envelope">
                <soap:Body>
                    <ns2:requestReportByEntities
                        xmlns:ns2="http://ReportManagement_v3.lowLevel.helios.webservices.adtech.de/"
                        xmlns:ns3="http://rawdata.webservice.adtech.de/"
                        xmlns:ns4="http://ReportManagement.helios.adtech.de/"
                        xmlns:ns5="http://CampaignManagement.helios.adtech.de/"
                        xmlns:ns6="http://helios.adtech.de/"
                        xmlns:ns7="http://UserManagement.helios.adtech.de/">
                        <arg0>' . $this->report_uu_id . '</arg0>
                        <arg1>' . $campaign->absoluteStartDate . '</arg1>
                        <arg2>' . $campaign->absoluteEndDate . '</arg2>
                        <arg3>' . $this->report_entity_type_mastercampaign . '</arg3>
                        <arg4>' . $this->report_category_campaign  .'</arg4>
                        <arg5
                            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                            xmlns:xs="http://www.w3.org/2001/XMLSchema"
                            xsi:type="xs:long">
                            '.$campaign->cid_adtech.'
                            </arg5>
                        </ns2:requestReportByEntities>
                    </soap:Body>
                </soap:Envelope>';
            
                $response = $this->client->__doRequest($xml,$this->url_v3 ,'requestReportByEntities',$this->adtech_soap_version);
                
                var_dump($response);
                
                $dom = new DOMDocument;
                $dom->preserveWhiteSpace = FALSE;
                $dom->loadXML($response);

                //Save XML as a file
                $dom->save($this->request_folder.'/'.$file_name);
                
                chmod($this->request_folder.'/'.$file_name, 0777);  //changed to add the zero
                
            endif;
            

        
    }
    
    public function createFlightByWebsiteReport( $slaves, $master, $start, $end )
    {
        
        $file_name = $master.'_uu.xml';
        $file_url  = 'http://localhost/adrun-dashboard-v1-8/storage/report/request/'.$file_name;
        
        
            
        
            if(!file_exists($this->request_folder.'/cli/'.$file_name)):
                
                $xml = '<soap:Envelope
                xmlns:soap="http://www.w3.org/2003/05/soap-envelope">
                <soap:Body>
                    <ns2:requestReportByEntities
                        xmlns:ns2="http://ReportManagement_v3.lowLevel.helios.webservices.adtech.de/"
                        xmlns:ns3="http://rawdata.webservice.adtech.de/"
                        xmlns:ns4="http://ReportManagement.helios.adtech.de/"
                        xmlns:ns5="http://CampaignManagement.helios.adtech.de/"
                        xmlns:ns6="http://helios.adtech.de/"
                        xmlns:ns7="http://UserManagement.helios.adtech.de/">
                        <arg0>' . $this->report_uu_id . '</arg0>
                        <arg1>' . $start . '</arg1>
                        <arg2>' . $end . '</arg2>
                        <arg3>' . $this->report_entity_type_mastercampaign . '</arg3>
                        <arg4>' . $this->report_category_campaign  .'</arg4>
                        <arg5
                            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                            xmlns:xs="http://www.w3.org/2001/XMLSchema"
                            xsi:type="xs:long">
                            '. 18678333 .'
                            </arg5>
                        </ns2:requestReportByEntities>
                    </soap:Body>
                </soap:Envelope>';
            
                $response = $this->client->__doRequest($xml,$this->url_v3 ,'requestReportByEntities',$this->adtech_soap_version);
                
                $dom = new DOMDocument;
                $dom->preserveWhiteSpace = FALSE;
                $dom->loadXML($response);

                //Save XML as a file
                $dom->save($this->request_folder.'/cli/'.$file_name);
                
                chmod($this->request_folder.'/cli/'.$file_name, 0777);  //changed to add the zero
                
            else:
                
                unlink($this->request_folder.'/cli/'.$file_name);
                $this->createFlightByWebsiteReport( $slaves, $master, $start, $end );
                
            endif;
            

        
    }
    
    private function masterCampaignOverview( $campaign )
    {
        $file_name = $campaign->cid_adtech.'_master_overview.xml';
        
        $xml = '<soap:Envelope
                xmlns:soap="http://www.w3.org/2003/05/soap-envelope">
                <soap:Body>
                    <ns2:requestReportByEntities
                        xmlns:ns2="http://ReportManagement_v3.lowLevel.helios.webservices.adtech.de/"
                        xmlns:ns3="http://rawdata.webservice.adtech.de/"
                        xmlns:ns4="http://ReportManagement.helios.adtech.de/"
                        xmlns:ns5="http://CampaignManagement.helios.adtech.de/"
                        xmlns:ns6="http://helios.adtech.de/"
                        xmlns:ns7="http://UserManagement.helios.adtech.de/">
                        <arg0>' . $this->master_campaign_overview . '</arg0>
                        <arg1>' . $campaign->absoluteStartDate . '</arg1>
                        <arg2>' . $campaign->absoluteEndDate . '</arg2>
                        <arg3>' . $this->report_entity_type_mastercampaign . '</arg3>
                        <arg4>' . $this->report_category_campaign  .'</arg4>
                        <arg5
                            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
                            xmlns:xs="http://www.w3.org/2001/XMLSchema"
                            xsi:type="xs:long">
                            '.$campaign->cid_adtech.'
                            </arg5>
                        </ns2:requestReportByEntities>
                    </soap:Body>
                </soap:Envelope>';
        
        $response = $this->client->__doRequest($xml,$this->url_v3 ,'requestReportByEntities',$this->adtech_soap_version);
                
                $dom = new DOMDocument;
                $dom->preserveWhiteSpace = FALSE;
                $dom->loadXML($response);

                //Save XML as a file
                $dom->save($this->request_folder.'/'.$file_name);
                
                chmod($this->request_folder.'/'.$file_name, 0777);  //changed to add the zero
    }
    
    
    
    
    public function xtremeStatisticGenerator() 
    {
        
        //var_dump($this->client->__getTypes());
        
        ini_set('max_execution_time', 30000); //300 seconds = 5 minutes
        
        $campaigns = AdrunCampaignModel::getInstance()->getADRUNCampaignIDListXML(0,100);
        
        $i=0;
        $e=0;
        $r=0;
        $total_connection=0;
        foreach($campaigns as $campaign):
            
            $today = Carbon\Carbon::now();
        
            if($today > $campaign->adrunEndDate):
                $i++;
                
                $start = Carbon\Carbon::parse($campaign->adrunStartDate)->format('Ymd');
                $end   = Carbon\Carbon::parse($campaign->adrunEndDate)->format('Ymd');

                $file_dir = $this->adrun_store_report."/".$campaign->id_adtech."-$start-$end.xml";

                if( !file_exists( $file_dir ) ):
                    
                    $total_connection++;
                    $excep = FALSE;
                
                    $params   = array ( 
                                    "arg0" => "ADRUN",
                                    "arg1" => 1,
                                    "arg2" => $campaign->id_adtech,
                                    "arg3" => $start, 
                                    "arg4" => $end,

                                  );
                    
                    
                    try {
                            $obj = $this->client->retrieveSummaryBannerStatisticsForCampaign($params);
                            
                        }
                
                    catch(Exception $e) {
                        
                        echo 'Message: ' .$e->getMessage();
                        $excep = TRUE;
                        $obj = "";
                        
                    }    
                        
                        if( !empty( (array) $obj ) ):
                            
                            $r++;
                            AdrunCampaignModel::getInstance()->setADRUNReportXML($campaign->id,3);
                            
                        else:
                            
                            if(!$excep):
                                
                                AdrunCampaignModel::getInstance()->setADRUNReportXML($campaign->id,2);
                                
                            endif;
                            
                        endif;
                        
                else:
                    
                    $e++;
                    AdrunCampaignModel::getInstance()->setADRUNReportXML($campaign->id,1);
                        
                endif;
                
            endif;
            
        endforeach;
        
        $this->saveMiniReportXML();
        
        return $campaigns;
        
    }
    
    private function saveMiniReportXML() 
    {
        
        $campaigns = AdrunCampaignModel::getInstance()->getADRUNCampaignIDListXML(3,100);
        
        foreach($campaigns as $campaign):
            
            $start = Carbon\Carbon::parse($campaign->adrunStartDate)->format('Ymd');
            $end   = Carbon\Carbon::parse($campaign->adrunEndDate)->format('Ymd');

            $file_dir = $this->adrun_store_report."/".$campaign->id_adtech."-$start-$end.xml";
            
            $params   = array ( 
                                    "arg0" => "ADRUN",
                                    "arg1" => 1,
                                    "arg2" => $campaign->id_adtech,
                                    "arg3" => $start, 
                                    "arg4" => $end,

                                  );
                    
            try { $obj = $this->client->retrieveSummaryBannerStatisticsForCampaign($params); }
            
            catch(Exception $e) {
                        
                echo 'Message: ' .$e->getMessage();
                $excep = TRUE;
                $obj = "";
                continue;
                        
            }
            
            
        if (!empty((array) $obj)):

            fopen( $file_dir, "w ") or die("Unable to open file!");
            $json         = json_decode( file_get_contents( $file_dir ),TRUE);

            $json[] = $obj->return;
            file_put_contents($file_dir, json_encode($json));

            $resp = AdrunReportModel::getInstance()->setADTECHBanner($json[0]);

            if($resp):

                AdrunCampaignModel::getInstance()->setADRUNReportXML($campaign->id,1);

            endif;


       endif;

            
        endforeach;
        
    }
    
    //Data mining st@rt September 2017
    public function getSummaryStatisticsOneYear()
    {
        $params   = array ( 
            "arg0" => "ADRUN",
            "arg1" => "ADRUN",
            "arg2" => 20171126, 
            "arg3" => 20171130,

            );
                    
        $objs = $this->client->retrieveSummaryStatistics($params);
        
        foreach ($objs->return as $obj):
            
            $res = AdrunCampaignModel::getInstance()->addSummaryAdrun($obj);
        
        endforeach;
             
                
        die();
                
                 
        
    }
    
    public function getSummaryStatisticsDay($start)
    {
        //$start = 20171216;
        
        $params   = array ( 
            "arg0" => "ADRUN",
            "arg1" => "ADRUN",
            "arg2" => $start, 
            "arg3" => $start,

            );
        
        try {
                $objs = $this->client->retrieveSummaryStatistics($params);
                
            } catch (\SoapFault $e) {
                
                $start = $start + 1;
                
                $objs = $this->getSummaryStatisticsDay($start);
                
            }
        
            $arr = (array) $objs;
            
            if(!empty($arr)):
                
               foreach ($objs->return as $obj):

                    $res = AdrunCampaignModel::getInstance()->addSummaryAdrun($obj);

                endforeach;
                
            endif; 
            
        
                
    }
    
    public function reportLatestCampaignEnd()
    {
        $campaigns = AdrunCampaignModel::getInstance()->getADRUNCampaignLastestEnd();
        
        $reporting = [];
        
        $reporting['total'] = count($campaigns);
        $reporting['datas'] = [];
        
        $i=0;
        foreach($campaigns as $campaign):
            
            $i++;
            $start    = Carbon\Carbon::parse($campaign->adrunStartDate)->format('Ymd');
            $end      = Carbon\Carbon::parse($campaign->adrunEndDate)->format('Ymd');
            $file_dir = $this->adrun_store_report."/".$campaign->id_adtech."-$start-$end.xml";
            
            $reporting['datas'][$i]['name']  = $campaign->name;
            $reporting['datas'][$i]['start'] = $start;
            $reporting['datas'][$i]['end']   = $end;
            
            if( file_exists( $file_dir ) ):
                
                $myfile = fopen( $file_dir, "r") or die("Unable to open file!");
                $json   = json_decode( file_get_contents( $file_dir ),TRUE);
                
                
                $impressions = 0;
                $click       = 0;
                foreach($json[0] as $banner):
                    
                    $impressions = $impressions + $banner['impressions'];
                    $click       = $click + $banner['clicks'];
                    
                endforeach;
                
                $reporting['datas'][$i]['impressions'] = $impressions;
                $reporting['datas'][$i]['click']        = $click;
                
            
            endif;
        
        endforeach;
        
        
        var_dump($reporting);
        
        die('toto');
    }
    

    
}
