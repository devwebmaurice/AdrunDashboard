<?php

namespace App\Console\Commands\Adrun;

use Illuminate\Console\Command;
use App\Models\Adrun\AdrunCampaignModel;
use Carbon\Carbon;
use DateTime;
use App\Models\Adtech\AdtechReportModel;
use App\Models\SingleBilanModel;
use App\Models\Mail\ReportModel;
use App\Models\Adrun\AdrunReportModel;

class CampaignCLIControl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adrun:campaignCLIControl {action*}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'CLI Control Dashboard';
    
    protected $last;
    protected $check;

    private static $_instance;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        
        $last                 = AdrunCampaignModel::getInstance()->getLastDateReportDone();
        $this->last           = Carbon::parse($last->adrun_day)->format('Y-m-d H:i:s');
        $check                = Carbon::now()->subDays(1)->endOfDay();
        $this->check          = Carbon::parse($check)->format('Y-m-d H:i:s');
        $this->local_path     = ( $_SERVER['APP_ENV'] === 'local' ) ? storage_path().'/report/request' : '/var/www/html/adrun/services/dashboard/storage/report/request';
        $this->request_folder = (is_dir( $this->local_path )) ? $this->local_path : mkdir( $this->local_path, 0777, true);
        $this->campaigns      = AdrunCampaignModel::getInstance()->getCampaignTermineYesterday(30);
        
    }

    
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $action = $this->argument('action');
        
        switch ($action[0]):
            
            case "rollback":
            $this->rollback( $action[1] );
            break;
        
            case "create":
            $this->create( $action[1] );
            break;
        
            default:
            echo "this action could not be completed. try again /n";
            
        endswitch;
    
        die();
        
        echo PHP_EOL ."---CREATE END OF CAMPAIGN REPORT---" . PHP_EOL;
        flush();
        
        if($last < $check):
           
            $date1 = new DateTime($check);
            $date2 = new DateTime($last);
            $diff = $date1->diff($date2);
            
            $x = 1; 
            while($x <= $diff->d):
                $x++;
                $date2->modify('+1 day');
                $start =  $date2->format('Ymd');
                
                $objs = AdtechReportModel::getInstance()->getSummaryStatisticsDay($start);
                
            endwhile;
            
        endif;
        
        $campaigns = AdrunCampaignModel::getInstance()->getCampaignTermineYesterday();
        $details = [];
        
        foreach($campaigns as $campaign):
            
            $slave_id = AdrunReportModel::getInstance()->getSlaveByMasterId($campaign->id_adtech);
            
            AdtechReportModel::getInstance()->generateReport($slave_id);
            
            $campaign->cname = strtoupper( $campaign->cname );
            
            echo PHP_EOL ."---$campaign->cname---" . PHP_EOL;
            
            $test = SingleBilanModel::getInstance()->createBilan($campaign->id);
            
            if(!is_null($test)):
                
                $details[] = $test;
            
            endif;
        endforeach;
        
        
        ReportModel::getInstance()->sendDailyEndOfCampaignReport($details);
        
        
        echo PHP_EOL ."---MISSION COMPLETED---" . PHP_EOL;
        flush();
    }
    
    private function rollback ($number)
    {
        
        $campaigns = AdrunCampaignModel::getInstance()->getCampaignTermineYesterday($number);
        
        foreach($campaigns as $campaign):
            
            $file_name = $campaign->id_adtech.'_uu.xml';
        
            //echo $file_name."\r\n";
        
            if(file_exists($this->request_folder.'/'.$file_name)):
                
                unlink($this->request_folder.'/'.$file_name);
                
            endif;
        
            
        endforeach;
        
    }
    
    private function create ($number)
    {
        
        $campaigns = AdrunCampaignModel::getInstance()->getCampaignTermineYesterday($number);
        
        $i=0;
        foreach($campaigns as $campaign):
            
            $file_name = $campaign->id_adtech.'_uu.xml';
        
            if(!file_exists($this->request_folder.'/'.$file_name)):
                $i++;
            
                $cname = explode("-",$campaign->cname);
                $cname[1] = (array_key_exists(1,$cname) ) ? $cname[1] : NULL ;
                $cname[1] = ( !empty( $cname[1] ) or $cname[1] != ' ' or $cname[1] != '') ? $cname[1] : NULL ;
                
                if(!is_null($cname[1]) && !empty($cname[1])):
                    
                    $id    = trim($cname[0]);
                    $name  = trim($cname[1]);
                    
                    $odd = array("/");
                    $name  = strtoupper(str_replace($odd,"_",$name));
               
                    $slaves = AdrunReportModel::getInstance()->getSlavesByMasterId($campaign->id_adtech);
                    
                    $arrays = [];
                    
                    foreach($slaves as $slave):
                        
                        $arrays[] = $slave->id_adtech;
                        
                    endforeach;
                    
                   AdtechReportModel::getInstance()->createFlightByWebsiteReport($arrays, $campaign->id_adtech,$campaign->absoluteStartDate,$campaign->absoluteEndDate);
                    
                    var_dump($arrays);
                    
                    
            
                echo "{$campaign->id_adtech} {$id} {$name} {$i} \r\n";
                flush();
                    
                endif;
                
                
                
                
            endif;
        
            
        endforeach;
        
    }
    
    
    
}
