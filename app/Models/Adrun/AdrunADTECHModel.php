<?php


namespace App\Models\Adrun;

use Illuminate\Database\Eloquent\Model;
use App\Http\Controllers\Config;

use DB;
use stdClass;
use Firebase\JWT\JWT;
use SimpleXMLElement;

include base_path().'/vendor/adrun/dir-adtech-includes/dir-firebase/vendor/autoload.php';

class AdrunADTECHModel extends Model
{
    private static $_instance; // L'attribut qui stockera l'instance unique
    
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
        $this->adtech_server_url     = \Config::get('adrun.adtech.ADTECH_SERVER_URL');
        $this->adtech_server_url_end = \Config::get('adrun.adtech.ADTECH_SERVER_URL_END');
        $this->adtech_soap_version   = \Config::get('adrun.adtech.ADTECH_SOAP_VERSION');
        $this->url_v4                = $this->adtech_server_url."WSCampaignAdmin_v4".$this->adtech_server_url_end;
        $this->adrun_store_path      = storage_path()."/adrun";
        $this->token_json_file       = $this->adrun_store_path . "/token_json.txt";
        $this->key_xml_dir           = base_path() . "/vendor/adrun/dir-adtech-includes/xml/api_adtech_access.xml";
        
        $this->grant_type = "client_credentials";
        $this->url        = 'https://id.corp.aol.com/identity/oauth2/access_token?realm=oneadserver';
        
        $this->createFolder();
        
    }
    
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    
    
    // méthode commune
    private function getSoapOptions() { 
        
        return array('soap_version'   => $this->adtech_soap_version);
    }
   
     private function createClientTikenXML($data,$dir) {
        
        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><config_key></config_key>');
        $xml->addAttribute('version', '1.0');
        $xml->addChild('datetime', date('Y-m-d H:i:s'));

        $key = $xml->addChild('key');
        $key->addChild('sub', $data['token']['sub']);
        $key->addChild('iss', $data['token']['iss']);
        $key->addChild('aud', $this->url);
        $key->addChild('iat', $data['token']['iat']);
        $key->addChild('exp', $data['token']['exp']);
        $key->addChild('jwt', $data['jwt']);
        $key->addChild('scope', $data['scope']);
        $key->addChild('data', htmlspecialchars($data['data']));
        $key->addChild('client_token', $data['client_token']->access_token);

        $xml->asXML($dir);
        
    }
    
    private function createFolder() {
        
        if (!file_exists($this->adrun_store_path)) {
            mkdir($this->adrun_store_path, 0755, true);
        }
        
        if(!file_exists($this->token_json_file)):
            
            fopen( $this->token_json_file, "w ") or die("Unable to open file!");
            
        endif;
        
        
    }
   
    public function setADTECHToken()
    {    
        $soap_options = $this->getSoapOptions();
        $json         = json_decode( file_get_contents( $this->token_json_file ),TRUE);
        
        $last_token = end($json);
        
        if( time() >= $last_token['exp']):
            
            $api_params = $this->createClientToken();
            $json[] = $api_params;
            file_put_contents($this->token_json_file, json_encode($json));
            
            return $api_params['token'];
            
         else: 
            
             return $last_token['token'];
             
        endif;
        
    }
    
    public function createClientToken() {
        
        $key_xml_dir = $this->key_xml_dir;
        
        if( !file_exists( $key_xml_dir ) ){
            
            $res         = $this->getPostFields();
            
            $res['client_token'] = $this->getClientToken($res['data']);
            $this->createClientTikenXML($res,$key_xml_dir);
        }
        
        $xml = simplexml_load_file($key_xml_dir) or die("Error: Cannot create object");
        
        
        if( time() >= $xml->key[0]->exp ){
            
            //if( file_exists( $key_xml_dir ) ){ unlink($key_xml_dir); }
            
            $res         = $this->getPostFields();
            $res['client_token'] = $this->getClientToken($res['data']);
            $this->createClientTikenXML($res,$key_xml_dir);
            
            return array( 'token' => (string) $res['client_token']->access_token, 'exp' =>  (int) $res['token']['exp']);
            
        } else {
            
            return  array( 'token' => (string) $xml->key[0]->client_token, 'exp' =>   (int) $xml->key[0]->exp);
        }
        
    }
    
    private function getPostFields() {
        
        $res_jwt          = $this->encodeJWT();
        $res_jwt['scope'] = $this->getScope();
        
        $data = array(
     
            'grant_type'            => $this->grant_type,
            'client_assertion_type' => 'urn:ietf:params:oauth:client-assertion-type:jwt-bearer',
            'client_assertion'      => $res_jwt['jwt'],
            'realm'                 => 'webapi',
            'scope'                 => $res_jwt['scope']
            );
        
        $res_jwt['data'] = http_build_query($data, '', '&');
        
        return $res_jwt;
    }
    
    private function encodeJWT () {
        
        $client_secret    = $this->getClientSecret();
        $respond['token'] = $this->getJWTToken();
        $respond['jwt']   = JWT::encode($respond['token'], $client_secret);
        
        return $respond;
        
        
        if( time() >= $xml->key[0]->exp ){
            
            $jwt = JWT::encode($token, $client_secret);
            
            $xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><config_key></config_key>');
            $xml->addAttribute('version', '1.0');
            $xml->addChild('datetime', date('Y-m-d H:i:s'));
            
            $key = $xml->addChild('key');
            $key->addChild('sub', $token['sub']);
            $key->addChild('iss', $token['iss']);
            $key->addChild('aud', $this->url);
            $key->addChild('iat', $token['iat']);
            $key->addChild('exp', $token['exp']);
            $key->addChild('jwt', $jwt);
            
            $xml->asXML($key_xml_dir);
            
            return $jwt;
            
        } else {
            
            return (string) $xml->key[0]->jwt;
        }
        
    }
    
    private function decodeJWT ($jwt) {
        
        $client_secret = $this->getClientSecret();
        
        $decoded = JWT::decode($jwt, $client_secret, array('HS256'));
        
        return $decoded;
    }
    
    private function getClientSecret() {
        
        $client_secret = \Config::get('adrun.adtech.ADTECH_CLIENT_SECRET');
        
        return $client_secret;
    }
    
        private function getScope() {
        
        $scope = \Config::get('adrun.adtech.ADTECH_SCOPE');
        
        return $scope;
    }
    
    private function getClientID() {
        
        $client_id = \Config::get('adrun.adtech.ADTECH_CLIENT_ID');
        
        return $client_id;
        
    }
    
    private function getClientLogin() {
        
        $client_login = \Config::get('adrun.adtech.ADTECH_LOGIN');
                
        return $client_login;
        
    }
    
    private function getJWTToken () {
        
        $time      = $this->getTimeSettings();
        $client_id = $this->getClientID();
        
        $key = array(
                "sub" => $client_id,
                "iss" => $client_id,
                "aud" => $this->url,
                "iat" => $time['start'],
                "exp" => $time['end']
            );
        
        return $key;
    }
    
    private function getTimeSettings() {
        
        $time = array( 'start' => time() , 'end' => time() + (10 * 60));
        
        return $time;
    }
    
    private function getClientToken($data){
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$this->url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/x-www-form-urlencoded', 'accept: application/json'));
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);
        curl_setopt($ch, CURLINFO_HEADER_OUT, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        $server_output = curl_exec ($ch);
        
        //$info = curl_getinfo($ch);
        curl_close ($ch);
        
        $server_output = json_decode($server_output);
        
        
        return $server_output;
        
    }
    
    
}
