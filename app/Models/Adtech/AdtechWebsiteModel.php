<?php

namespace App\Models\Adtech;

use Illuminate\Database\Eloquent\Model;
use App\Models\Adrun\AdrunADTECHModel;
use HeliosSoapClient;
use App\Models\Adrun\AdrunWebsiteModel;
use App\Models\Adrun\AdrunCampaignModel;
use App\Models\Adrun\AdrunReportModel;
use Carbon;

include_once base_path().'/vendor/adrun/dir-adtech-includes/dir-api-clientsystem/conf/HeliosSoapClient.php';

class AdtechWebsiteModel extends Model
{
    private static $_instance;
    
    public function __construct()
    {
        
        $this->token                 = AdrunADTECHModel::getInstance()->setADTECHToken();
        $this->adtech_server_url     = \Config::get('adrun.adtech.ADTECH_SERVER_URL');
        $this->adtech_server_url_end = \Config::get('adrun.adtech.ADTECH_SERVER_URL_END');
        $this->adtech_soap_version   = \Config::get('adrun.adtech.ADTECH_SOAP_VERSION');
        $this->emails                = \Config::get('adrun.emails');
        $this->url_v3                = $this->adtech_server_url."WSWebsiteAdmin_v4".$this->adtech_server_url_end;
        $this->client                = new HeliosSoapClient($this->url_v3, $this->adtech_soap_version, $this->token);
        
        
    }
    
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    
    public function getADTECHWebsiteIDList() 
    {
        
        $Websites = $this->client->getWebsiteIdList();
        
        return $Websites;
        
    }
    
    public function getEditeurById($editeur, $display = null)
    {
        $params = array ( "arg0" => $editeur );
       
        if( is_null( $display ) ):
            
            try {
            $data = $this->client->getWebsiteById($params);
            ob_end_clean();
            return TRUE;
        
            } catch (SoapFault $fault) {

               ob_end_clean();          
               return FALSE;

            }
            
        else:    
            
            $data = $this->client->getWebsiteById($params);
        
            return $data;
            
        endif;
        
        
        
    }
    
    
    

    
}
