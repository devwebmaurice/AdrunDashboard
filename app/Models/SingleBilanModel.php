<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Adrun\AdrunCampaignModel;
use App\Models\Adrun\AdrunReportModel;
use App\Models\Adrun\AdrunADTECHCampaignModel;
use App\Models\Adrun\AdrunWebsiteModel;

use Excel;
use Carbon\Carbon;

class SingleBilanModel extends Model
{
    private static $_instance;
   
    
    public function __construct()
    {
        
        $this->tbl_campaign      = \Config::get('adrun.table.campaign');
        $this->tbl_advertiser    = \Config::get('adrun.table.advertiser');
        $this->tbl_banner        = \Config::get('adrun.table.banner');
        $this->tbl_report_sum    = \Config::get('adrun.table.TBL_REPORT_SUMMARY');
        $this->TAB_1             = "TITRE";
        $this->TAB_2             = "PAR SITE WEB";
        $this->TAB_3             = "PAR FLIGHT PAR SITE WEB";
        $this->TAB_1_TITLE       = "RAPPORT DE CAMPAGNE";
        $this->TAB_2_TITLE       = "PAR SITE WEB";
        $this->TAB_3_TITLE       = "PAR FLIGHT PAR SITE WEB";
        $this->TAB_1_SUB_1_TITLE = "PÉRIODE DU RAPPORT";
        $this->creator           = "Me";
        $this->company           = "ADRUN LTD";
        
        $this->report_main_path  = ( $_SERVER['APP_ENV'] === 'local' ) ? storage_path()."/report" : '/var/www/html/adrun/services/dashboard/storage/report';
        $this->report_campaign   = $this->report_main_path . "/campaign/";
    }
    
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    
    public function createBilan($campaign_id)
    {
        $day = Carbon::now()->subDays(2)->format('d-m-Y');
        
        $campaign             = AdrunCampaignModel::getInstance()->getADRUNCampaignByID($campaign_id);
        $this->campaign_id    = $campaign->cid_adtech;
        $this->detail         = $this->createFileDetail($campaign,$day);
            
        if(!is_dir($this->report_main_path)):

            mkdir($this->report_main_path,0777,true);
            mkdir($this->report_campaign,0777,true);

        endif;
        
        
        if(!is_dir($this->report_campaign.$day)):

            mkdir($this->report_campaign.$day,0777,true);
        
            chmod($this->report_campaign.$day, 0777);  //changed to add the zero
        
        endif;
        
        $namexcx = str_replace("/","_",$this->detail['name']);
        
        
        if (!file_exists($this->report_campaign.$day.'/'.$namexcx.".xlsx")):
            
            Excel::create($namexcx, function($excel) use($campaign){

                $date = $this->createDateDetail($campaign->start, $campaign->end);

                // Set the title
                $excel->setTitle($this->detail['title']);

                // Chain the setters
                $excel->setCreator($this->creator)->setCompany($this->company);

                $excel->setDescription($this->detail['description']);

                $data = [ ];

                $excel->sheet($this->TAB_1, function ($sheet) use ($data,$date) {

                    $sheet->setOrientation('landscape');
                    $sheet->mergeCells('A1:C1');
                    $sheet->setBorder('A2:B4', 'thin');
                    $sheet->setPageMargin( array( 0.25, 0.30, 0.25, 0.30 ) );


                    $sheet->cell('A1', function($cell) {
                        // manipulate the cell
                        $cell->setValue($this->TAB_1_TITLE);
                        // Set font
                        $cell->setFont(array(
                            'family'     => 'Ariel',
                            'size'       => '14',
                            'bold'       =>  true
                        ));

                    });

                    $sheet->row(2, array( 'Annonceur:', $this->detail['annoceur_label'] ));
                    $sheet->row(3, array( 'Devis:', $this->detail['devis'] ));
                    $sheet->row(4, array( 'Campagne:', $this->detail['campagne'] ));

                    $sheet->cell('A6', function($cell) {
                        // manipulate the cell
                        $cell->setValue($this->TAB_1_SUB_1_TITLE);
                        // Set font
                        $cell->setFont(array(
                            'family'     => 'Ariel',
                            'size'       => '12',
                            'bold'       =>  true
                        ));

                    });

                    $sheet->row(7, array( 'de:', $date['start'] ));
                    $sheet->row(8, array( 'à:', $date['end'] ));
                    $sheet->row(9, array( 'Durée de campagne:', $date['duration']->days.' Jours' ));
                    $sheet->row(11, array( 'Date de création:', $date['today'] ));

                    $sheet->row(13, function($row) {

                        // call cell manipulation methods
                        $row->setFont( array(
                            'family'     => 'Ariel',
                            'size'       => '11',
                            'bold'       =>  true
                        ) );

                    });

                    $sheet->row( 13, array('FLIGHT', 'IMPRESSIONS','CLICS', 'TAUX DE CLICS') );

                    $sheet->fromArray( $this->createTableSheetOne(), NULL, 'A14',FALSE,FALSE );

                    $sheet->appendRow( array( ' ') );
                    $sheet->appendRow( array( 'Visiteurs uniques', 0 ) );
                    $sheet->appendRow( array( 'Répétition', 0 ) );

                });

                $excel->setActiveSheetIndex(0);

            })->store('xlsx', $this->report_campaign.$day.'/');
            
            
            return $this->detail;
        
         endif;
    }
    
    private function createFileDetail($data,$day)
    {
        $detail                    = [ ];
        $ANNOCEUR                  = str_replace(" ","_",$data->aname);
        $CAMPAGNE_DEVIS            = explode("-",$data->cname);
        $CAMPAGNE_DEVIS[1]         =(isset($CAMPAGNE_DEVIS[1])) ? $CAMPAGNE_DEVIS[1] : 'UNDEFINED';
        $CAMPAGNE                  = mb_strtoupper(preg_replace('/\s+/', '', $CAMPAGNE_DEVIS[1]));
        $DEVIS                     = mb_strtoupper(preg_replace('/\s+/', '', $CAMPAGNE_DEVIS[0]));
        $detail['name']            = mb_strtoupper(html_entity_decode("BILAN_{$ANNOCEUR}_{$DEVIS}_{$CAMPAGNE}"));
        $detail['title']           = mb_strtoupper(html_entity_decode("BILAN_{$ANNOCEUR}_{$DEVIS}_{$CAMPAGNE}"));
        $detail['description']     = mb_strtoupper($data->cdescription);
        $detail['annoceur']        = html_entity_decode($ANNOCEUR);
        $detail['annoceur_label']  = mb_strtoupper(html_entity_decode($data->aname));
        $detail['devis']           = $DEVIS;
        $detail['campagne']        = $CAMPAGNE;
        $detail['day']             = $day;
        
        return $detail;
    }
    
    private function createDateDetail($start, $end)
    {
        $detail                = [ ];
        $dtStart               = date_create(Carbon::parse($start)->format('Y-m-d'));
        $dtEnd                 = date_create(Carbon::parse($end)->format('Y-m-d'));
        $detail['start']       = Carbon::parse($start)->format('d - m - Y');
        $detail['end']         = Carbon::parse($end)->format('d - m - Y');
        $detail['duration']    = date_diff($dtStart,$dtEnd);
        $detail['today']       = Carbon::now()->format('d - m - Y');
        
        return $detail;
    }
    
    
    private function createTableSheetOne()
    {
        
        $data   = $this->getImpressionsClicks();
        
        
        return $data;
        
    }
    
    private function getImpressionsClicks()
    {
        
        $this->slaves = AdrunCampaignModel::getInstance()->getADRUNSlaveCampaignByMID($this->campaign_id);
        
          $datax = $this->getImpressionsClicksPerWebsite();
          $i_total = 0;
          $c_total = 0;
          foreach ($datax as $key => $value ):
              
                $impressions = isset($value['total']['impressions']) ?$value['total']['impressions']: '0';
                $clics        = isset($value['total']['clics']) ? $value['total']['clics'] : '0';
          
                $percentage = ($clics / $impressions) * 100;
              
                    $data[] =   [
                        'FLIGHT'        => $key, 
                        'IMPRESSIONS'   =>  number_format($impressions), 
                        'CLICS'         =>  number_format($clics), 
                        'TAUX DE CLICS' => isset($percentage) ? number_format($percentage, 2).' %' : number_format('0', 2).' %'
                    ];
                    
            $i_total = $i_total += $impressions;
            $c_total = $c_total += $clics; 
          endforeach;
          
           $tx_percentage = $c_total / $i_total * 100;
          
            array_push( $data,array('', number_format($i_total), number_format($c_total), number_format($tx_percentage, 2).' %' ) );
            array_push( $data,array('', '', '', '') );
            array_push( $data,array('WEBSITE', 'IMPRESSIONS', 'CLICS', 'TAUX DE CLICS') );
          
          
        foreach ($datax as $key => $values ): 
            foreach ($values as $key => $value):
            foreach ($value as $keyx => $valuex):
                
                if(!is_null($valuex['clics'])):
                    
                    $impressions = isset($valuex[$keyx]['impressions']) ? $valuex[$keyx]['impressions']: '0';
                
                $website[$keyx][] =   [
                        'IMPRESSIONS'   => $valuex['impressions'],
                        'CLICS'         => $valuex['clics'], 
                        
                    ];
                
                endif;
            
            endforeach;
            endforeach;
        endforeach;   
            
        foreach ($website as $key => $values ):
            
            $loop    = count($values);
            $t_imps  = 0;
            $t_click = 0;
        
            for ($x = 0; $x < $loop; $x++) {
                
                $t_imps  = $t_imps  + $values[$x]['IMPRESSIONS'];
                $t_click = $t_click + $values[$x]['CLICS'];
                
            } 
            
            $t_percentage = $t_click / $t_imps * 100;
            
            $data[] =   [
                        'WEBSITE'        => mb_strtoupper($key), 
                        'IMPRESSIONS'   =>  number_format($t_imps), 
                        'CLICS'         =>  number_format($t_click), 
                        'TAUX DE CLICS' =>  number_format($t_percentage,2).' %'
                    ];
            
            
        endforeach;  
        
        array_push( $data,array('', number_format($i_total), number_format($c_total), number_format($tx_percentage, 2).' %' ) );
        array_push( $data,array('', '', '', '') );
        
        return $data;
        
    }
    
    private function getImpressionsClicksPerWebsite()
    {
        
        $items= [];
        $i=1;
        foreach( $this->slaves as $slave ):
          
            $datas = AdrunReportModel::getInstance()->getImpressionPerWebsiteByCampaign($slave->id_adtech);
            
            if(!$datas->isEmpty()):
                
                $total =0;
                $sum =0;
                
                foreach ($datas as $data) :
                    
                $ttc =isset($items[$slave->name]['data'][$data->editeur]['clics']) ? $items[$slave->name]['data'][$data->editeur]['clics']: '0';
                $tti =isset($items[$slave->name]['data'][$data->editeur]['impressions']) ? $items[$slave->name]['data'][$data->editeur]['impressions']: '0';
                
                $slave->name = mb_strtoupper(str_replace(" ","_",$slave->name));
                
                $items[$slave->name]['data'][$data->editeur] =  [
                                                                    'clics'       => $ttc+= $data->clicks,
                                                                    'impressions' => $tti+= $data->imps,

                                                                ];
                
                $items[$slave->name]['total'] = [
                                                    'impressions'   => $total += $data->imps,
                                                    'clics'         => $sum   += $data->clicks,
                                                ];
                endforeach;
                
            endif;
            
        endforeach;
        
//        $empty[] = array('', '', '', '');
//        
//        $result = array_merge($items, $empty );
        
        
        return $items;
    }
    
    //Fix for Master/Slave Campaign
    private function fixMasterSlave()
    {
        ini_set('max_execution_time', 6000); //300 seconds = 5 minutes
        
        $campaigns = AdrunCampaignModel::getInstance()->getAllCampaignMasterFix();
        $i=0;
        foreach($campaigns as $campaign):
            $i++;
            $data = AdrunADTECHCampaignModel::getInstance()->getCampaignById($campaign->id_adtech);
            
            if(isset($data->return)):
                
                AdrunCampaignModel::getInstance()->updateADRUNCampaignMaster($campaign->id,$data->return->masterCampaignId,$data->return->natureType);
                echo $i." ".$campaign->id."<hr/>";
                flush();
                ob_flush();
                
            endif;
            
            
        endforeach;
        
        die('toto');
    }
    
    
}
