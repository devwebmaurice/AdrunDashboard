<?php

namespace App\Models\Adtech;

use Illuminate\Database\Eloquent\Model;
use App\Models\Adrun\AdrunADTECHModel;
use HeliosSoapClient;

include_once base_path().'/vendor/adrun/dir-adtech-includes/dir-api-clientsystem/conf/HeliosSoapClient.php';

class AdtechNetworkModel extends Model
{
    private static $_instance;
    
    public function __construct()
    {
        
        $this->token                 = AdrunADTECHModel::getInstance()->setADTECHToken();
        $this->adtech_server_url     = \Config::get('adrun.adtech.ADTECH_SERVER_URL');
        $this->adtech_server_url_end = \Config::get('adrun.adtech.ADTECH_SERVER_URL_END');
        $this->adtech_soap_version   = \Config::get('adrun.adtech.ADTECH_SOAP_VERSION');
        $this->url_v3                = $this->adtech_server_url."WSNetworkAdmin_v3".$this->adtech_server_url_end;
        
        $this->client    = new HeliosSoapClient($this->url_v3, $this->adtech_soap_version, $this->token);
        
    }
    
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    
    public function getNetworkInfoList() 
    {
         //var_dump($this->client->__getTypes());
         
            $params = array ( "arg0" => "ADRUN", "arg1" => 1, "arg2" => 20170809, "arg3" => 20171009);
         
            $Campaigns = $this->client->getNetworkInfoList();
        
        return $Campaigns;
        
    }
    
}
