<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Config;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use Illuminate\Http\Request;
use Illuminate\Mail\Mailer;
//use App\Models\Adrun\AdrunADTECHCampaignModel;
use App\Models\Adrun\AdrunCampaignModel;
use App\Mail\AdrunDashboard;
use App\Mail\AdrunAdvertiser;
use App\Mail\AdrunCampaign;
use Mail;

use App\Models\Adtech\AdtechNetworkModel;
//use App\Models\Adtech\AdtechCustomerModel;
use App\Models\Adtech\AdtechReportModel;
use App\Models\Adtech\AdtecStatisticsModel;
//use App\Models\Adrun\AdrunCustomerModel;
use App\Models\Adrun\AdrunWebsiteModel;
use App\Models\Adtech\AdtechWebsiteModel;

use Carbon\Carbon;
use DateTime;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        
        
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $month = new Carbon('last month');
        
        AdtechReportModel::getInstance()->generateReport(2782);
        
        die('Hello World');
        
        
        
        
//        AdtechNetworkModel::getInstance()->getNetworkInfoList();
//        AdtechReportModel::getInstance()->xtremeStatisticGenerator();
//        
        
        //AdtechReportModel::getInstance()->reportLatestCampaignEnd();
        //AdtechReportModel::getInstance()->getSummaryStatisticsOneYear();
        
        $campaigns = AdrunCampaignModel::getInstance()->getCampaignTermineYesterday();
        $editeurs  = AdrunWebsiteModel::getInstance()->getAllEditeursLastMonth();
        
        //$this->createCampaignRerport();
        
        return view('home', compact('campaigns','editeurs','month'));
    }
    

    
    public function syncPhaseTwo () 
    {
        
        $ADRUN_advertisers  = AdrunCustomerModel::getInstance()->getADTECHAdvertiserIDList();
        $ADTECH_advertisers = AdtechCustomerModel::getInstance()->getADTECHAdvertiserIDList()->return;
        
        $ADRUN_editeurs     = AdrunWebsiteModel::getInstance()->getEditeurList();
        $ADTECH_editeurs    = AdtechWebsiteModel::getInstance()->getADTECHWebsiteIDList()->return;
        
        if( count( $ADTECH_editeurs  ) > count( $ADRUN_editeurs ) ):
            
            foreach ( $ADTECH_editeurs as $editeur ):
            
                $res = AdrunWebsiteModel::getInstance()->checkEditeurADRUN($editeur);
        
                if(!$res):
                    $data = AdtechWebsiteModel::getInstance()->getEditeurById($editeur);
                    $add  = AdrunWebsiteModel::getInstance()->addEditeurToADRUN($data->return);
                    
                endif;
            
            endforeach;
            
        endif;
        
        $news = array();
        
        if( count( $ADTECH_advertisers ) > count( $ADRUN_advertisers ) ):
            
            foreach ( $ADTECH_advertisers as $advertiser ):
            
               $resp = AdrunCustomerModel::getInstance()->checkAdvertiserADRUN($advertiser);
        
               if(!$resp):
                   
                   $data = AdtechCustomerModel::getInstance()->getAdvertiserById($advertiser);
               
                   $add  = AdrunCustomerModel::getInstance()->addAdvertiserToADRUN($data->return);
               
                   $news[] = $data->return->name;
               
                   
               endif;
            
            endforeach;
            
            
        endif;
        
  
    }
    
    
}
