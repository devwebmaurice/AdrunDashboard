<?php

namespace App\Models\Adrun;

use Illuminate\Database\Eloquent\Model;
use App\Models\Adrun\AdrunADTECHModel;
use HeliosSoapClient;

include_once base_path().'/vendor/adrun/dir-adtech-includes/dir-api-clientsystem/conf/HeliosSoapClient.php';

class AdrunADTECHCampaignModel extends Model
{
    private static $_instance; // L'attribut qui stockera l'instance unique
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
        libxml_disable_entity_loader(false);
        
        
        $this->token                 = AdrunADTECHModel::getInstance()->setADTECHToken();
        $this->adtech_server_url     = \Config::get('adrun.adtech.ADTECH_SERVER_URL');
        $this->adtech_server_url_end = \Config::get('adrun.adtech.ADTECH_SERVER_URL_END');
        $this->adtech_soap_version   = \Config::get('adrun.adtech.ADTECH_SOAP_VERSION');
        $this->url_v4                = $this->adtech_server_url."WSCampaignAdmin_v2".$this->adtech_server_url_end;
        
        $this->client    = new HeliosSoapClient($this->url_v4, $this->adtech_soap_version, $this->token);
        
    }
    
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    

    
    public function getADTECHCampaignIDList() 
    {
        
        $Campaigns = $this->client->getCampaignIdList();
        
        return $Campaigns;
        
    }
    
    public function getCampaignById($campaign)
    {
        
        $params = array ( "arg0" => $campaign );
        
        $Campaign = $this->client->getCampaignById($params);
        
        return $Campaign;
        
    }
    
    public function getADTECHAdGoalTypeList() 
    {
        
        $Campaigns = $this->client->getAdGoalTypeList();
        
        return $Campaigns;
        
    }
    
    public function deleteCampaignById($campaign)
    {
        
        $params = array ( "arg0" => $campaign );
        
        $Campaign = $this->client->deleteCampaign($params);
        
        return $Campaign;
        
    }
    
    
}
