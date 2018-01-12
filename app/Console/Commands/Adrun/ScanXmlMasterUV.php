<?php

namespace App\Console\Commands\adrun;

use Illuminate\Console\Command;

use App\Models\Mail\ReportModel;
use App\Models\Adrun\AdrunCampaignModel;

class ScanXmlMasterUV extends Command
{
    private static $_instance;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adrun:ScanXmlMasterUV';
    

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Xml Master for details';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    
    
    public function __construct()
    {
        parent::__construct();
        
        $this->local_path  = ( $_SERVER['APP_ENV'] === 'local' ) ? storage_path().'/report/request' : '/var/www/html/adrun/services/dashboard/storage/report/request';
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        echo PHP_EOL ."---SCAN START---" . PHP_EOL;
        flush();
        
        $files = glob($this->local_path . "/*.xml");
        
        foreach($files as $file):
            
            $name = preg_replace('/\\.[^.\\s]{3,4}$/', '', basename($file) );
            $name = explode("_", $name);
            
            $muv_done = AdrunCampaignModel::getInstance()->getADRUNReportMasterUVStatut($name[0]);
            
            if( end( $name ) === 'overview' && $muv_done === false):
               
                $xml         = file_get_contents($file, FILE_USE_INCLUDE_PATH);
                $string      = str_replace([ 'soap:','ns4:', 'ns2:' ],"", $xml);
                $xml         = simplexml_load_string($string);
                $report_id   = (int) $xml->Body->requestReportByEntitiesResponse->return->id;
                $campaign_id = (int) $xml->Body->requestReportByEntitiesResponse->return->entityId;     
                
                AdrunCampaignModel::getInstance()->addADRUNReportMasterUVID( $campaign_id, $report_id );
                
                echo PHP_EOL . "INSERT - {$report_id}" . PHP_EOL ;
                flush();
                
            elseif (end( $name ) === 'uu') :
                
                echo PHP_EOL . "FLIGHT REPORT - {$name[0]}" . PHP_EOL ;
                flush();
                
            else:     
                
                echo PHP_EOL . "EXIST UV REPORT- {$name[0]}" . PHP_EOL ;
                flush();
                
            endif;
            
            
        endforeach;
        
        echo PHP_EOL ."---SCAN COMPLETED---" . PHP_EOL;
        
        
    }
}
