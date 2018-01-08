<?php

namespace App\Models\Adtech;

use Illuminate\Database\Eloquent\Model;
use App\Models\Adrun\AdrunADTECHModel;
use HeliosSoapClient;

include_once base_path().'/vendor/adrun/dir-adtech-includes/dir-api-clientsystem/conf/HeliosSoapClient.php';

class AdtecStatisticsModel extends Model
{
    private static $_instance;
    
    public function __construct()
    {
        
        $this->token                 = AdrunADTECHModel::getInstance()->setADTECHToken();
        $this->adtech_server_url     = \Config::get('adrun.adtech.ADTECH_SERVER_URL');
        $this->adtech_server_url_end = \Config::get('adrun.adtech.ADTECH_SERVER_URL_END');
        $this->adtech_soap_version   = \Config::get('adrun.adtech.ADTECH_SOAP_VERSION');
        $this->url_v3                = $this->adtech_server_url."WSStatisticsAdmin_v2".$this->adtech_server_url_end;
        
        $this->client    = new HeliosSoapClient($this->url_v3, $this->adtech_soap_version, $this->token);
        
        
        $this->adrun_store_report    = base_path() . "/vendor/adrun/reports";
        
        if (!file_exists($this->adrun_store_report)) {  mkdir($this->adrun_store_report, 0755, true); }
        
    }
    
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    
    public function xtremeStatisticGenerator() 
    {
         //var_dump($this->client->__getTypes());
         ini_set('max_execution_time', 3000); //300 seconds = 5 minutes
         
         $obj = $this->client->getCampaignStatisticsIdList(); 
         
         var_dump($obj);
         
         die();
         
    }
}
