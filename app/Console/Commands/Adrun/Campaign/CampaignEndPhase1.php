<?php

namespace App\Console\Commands\Adrun\Campaign;

use Illuminate\Console\Command;
use App\Models\Adrun\AdrunCampaignModel;
use Carbon\Carbon;
use DateTime;
use App\Models\Adtech\AdtechReportModel;
use App\Models\SingleBilanModel;
use App\Models\Mail\ReportModel;
use App\Models\Adrun\AdrunReportModel;

class CampaignEndPhase1 extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'adrun:campaignEndPhase1';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Synchronisation de données';
    
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
        
        echo PHP_EOL ."---START MISSION--" . PHP_EOL;
        flush();
        /**
         * Get impression/click
         */
        
        var_dump($last,$check);
        
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
        
        echo PHP_EOL ."---MISSION COMPLETED---" . PHP_EOL;
        flush();
        
    }
}
