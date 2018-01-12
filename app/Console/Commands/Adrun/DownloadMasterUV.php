<?php

namespace App\Console\Commands\adrun;

use Illuminate\Console\Command;

use App\Models\Mail\ReportModel;
use App\Models\Adrun\AdrunCampaignModel;

class DownloadMasterUV extends Command
{
    private static $_instance;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adrun:DownloadMasterUV';
    

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Download Master UV Report';

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
        
        $this->local_path  = ( $_SERVER['APP_ENV'] === 'local' ) ? storage_path().'/report/download' : '/var/www/html/adrun/services/dashboard/storage/report/download';
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
        
        $campaigns =  AdrunCampaignModel::getInstance()->getADRUNMasterUVID();
        
        foreach( $campaigns as $campaign ):
            
            if( $campaign->download === 0):
                
                $url = "https://console.oneadserver.aol.de/h2/reporting/showReport.do?action=showreportpage._._.57611517";

                 $c = curl_init();
                curl_setopt($c, CURLOPT_URL, $url);
                curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
//                if($post_paramtrs)
//                {
//                    curl_setopt($c, CURLOPT_POST,TRUE);
//                    curl_setopt($c, CURLOPT_POSTFIELDS, "var1=bla&".$post_paramtrs );
//                }
                curl_setopt($c, CURLOPT_SSL_VERIFYHOST,false);
                curl_setopt($c, CURLOPT_SSL_VERIFYPEER,false);
                curl_setopt($c, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; rv:33.0) Gecko/20100101 Firefox/33.0");
                curl_setopt($c, CURLOPT_COOKIE, 'CookieName1=Value;');
                curl_setopt($c, CURLOPT_MAXREDIRS, 10);
                $follow_allowed= ( ini_get('open_basedir') || ini_get('safe_mode')) ? false:true;
                if ($follow_allowed)
                {
                    curl_setopt($c, CURLOPT_FOLLOWLOCATION, 1);
                }
                curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 9);
                curl_setopt($c, CURLOPT_REFERER, $url);
                curl_setopt($c, CURLOPT_TIMEOUT, 3000);
                curl_setopt($c, CURLOPT_AUTOREFERER, true);
                curl_setopt($c, CURLOPT_ENCODING, 'gzip,deflate');
                $data=curl_exec($c);
                $status=curl_getinfo($c);
                curl_close($c);
                
                var_export($data);
                
            endif;
            
            //var_dump($campaign);
            
            die('toto');
        endforeach;
        
        
        
        
        echo PHP_EOL ."---SCAN COMPLETED---" . PHP_EOL;
        
        
    }
}
