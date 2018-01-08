<?php

namespace App\Console\Commands\adrun;

use Illuminate\Console\Command;

use App\Models\Adrun\AdrunCustomerModel;
use App\Models\Adtech\AdtechCustomerModel;
use App\Models\Mail\ReportModel;
use App\Models\Terminal\Colors;

class Advertiser extends Command
{
    private static $_instance;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adrun:advertiserSync';
    

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync Advertiser';

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
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        echo PHP_EOL ."---ADVERTISER SYNC START---" . PHP_EOL;
        flush();
        
        $ADRUN_advertisers  = AdrunCustomerModel::getInstance()->getADTECHAdvertiserIDList();
        $ADTECH_advertisers = AdtechCustomerModel::getInstance()->getADTECHAdvertiserIDList()->return;
        
        $total_adrun_advertiser  = count( $ADRUN_advertisers );
        $total_adtech_advertiser = count( $ADTECH_advertisers );
        
        echo PHP_EOL ."ADRUN:  {$total_adrun_advertiser}" . PHP_EOL;
        flush();
        
        echo PHP_EOL ."ADTECH: {$total_adtech_advertiser}" . PHP_EOL;
        flush();
        
        $news = array();
        
        if( $total_adtech_advertiser > $total_adrun_advertiser ):
            
            foreach ( $ADTECH_advertisers as $advertiser ):
            
               $resp = AdrunCustomerModel::getInstance()->checkAdvertiserADRUN($advertiser);
        
               if(!$resp):
                   
                   $data = AdtechCustomerModel::getInstance()->getAdvertiserById($advertiser);
               
                   $add  = AdrunCustomerModel::getInstance()->addAdvertiserToADRUN($data->return);
               
                   $news[] = $data->return->name;
               
                   
               endif;
            
            endforeach;
            
            
        endif;
        
        
        echo PHP_EOL ."---ADVERTISER SYNC END---" . PHP_EOL;
        
        
    }
}
