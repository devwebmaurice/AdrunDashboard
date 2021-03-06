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

class CampaignEndReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adrun:campaignEndReport';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate end Report';
    
    private static $_instance;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
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
        $last  = AdrunCampaignModel::getInstance()->getLastDateReportDone();
        $last  = Carbon::parse($last->adrun_day)->format('Y-m-d H:i:s');
        $check = Carbon::now()->subDays(1)->endOfDay();
        $check = Carbon::parse($check)->format('Y-m-d H:i:s');
        
        echo PHP_EOL ."---CREATE END OF CAMPAIGN REPORT---" . PHP_EOL;
        flush();
        /**
         * Get impression/click
         */
        if( $last < $check ):
           
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
        
        $campaigns = AdrunCampaignModel::getInstance()->getCampaignTermineYesterday(2);
        $details = [];
        
        foreach( $campaigns as $campaign ):
            
            if($campaign->download === 0):
                
                $campaign->cname = strtoupper( $campaign->cname );
                echo PHP_EOL ."---$campaign->cname---" . PHP_EOL;
                
                //Get flight from Master
                $flights         = AdrunReportModel::getInstance()->getSlaveByMasterId($campaign->id_adtech);
                
                $i=0;
                foreach($flights as $flight):
                    
                    if($i === 0):
                     
                        $report_res = AdtechReportModel::getInstance()->generateReport($flight->id);
                        
                    else:
                        
                        continue;
                        
                    endif;
                    
                    var_dump($report_res);
                    $i++;
                    
                endforeach;
            
                $fac = SingleBilanModel::getInstance()->createBilan($campaign->id);

//                

                

                

    //            if(!is_null($fac)):
    //                
    //                $details[] = $fac;
    //            
    //            endif;
                
                
            endif;
            
        endforeach;
        
        
        ReportModel::getInstance()->sendDailyEndOfCampaignReport($details);
        
        
        echo PHP_EOL ."---MISSION COMPLETED---" . PHP_EOL;
        flush();
        
        die();
    }
}
