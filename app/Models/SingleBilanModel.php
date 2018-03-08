<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Adrun\AdrunCampaignModel;
use App\Models\Adrun\AdrunReportModel;
use App\Models\Adrun\AdrunADTECHCampaignModel;
use App\Models\Adrun\AdrunWebsiteModel;

use Excel;
use Carbon\Carbon;
use PHPExcel_Worksheet_Drawing;

class SingleBilanModel extends Model
{
    private static $_instance;
   
    
    public function __construct()
    {
        
        $this->tbl_campaign      = \Config::get('adrun.table.campaign');
        $this->tbl_advertiser    = \Config::get('adrun.table.advertiser');
        $this->tbl_banner        = \Config::get('adrun.table.banner');
        $this->tbl_report_sum    = \Config::get('adrun.table.TBL_REPORT_SUMMARY');
        $this->TAB_1             = "MESURE ET STATISTIQUES";
        $this->TAB_2             = "PAR SITE WEB";
        $this->TAB_3             = "PAR FLIGHT PAR SITE WEB";
        $this->TAB_1_TITLE       = "BILAN DE CAMPAGNE";
        $this->TAB_2_TITLE       = "PAR SITE WEB";
        $this->TAB_3_TITLE       = "PAR FLIGHT PAR SITE WEB";
        $this->TAB_1_SUB_1_TITLE = "////// > Global";
        $this->creator           = "Me";
        $this->company           = "ADRUN LTD";
        
        $this->report_main_path  = ( $_SERVER['APP_ENV'] === 'local' ) ? base_path()."/adrun" : '/var/www/html/adrun/services/dashboard/adrun';
        $this->report_campaign   = $this->report_main_path . "/campaign/";
        
        $this->section_path      = base_path('app/Models/partials/bilan');
    }
    
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    
    //Create The Excel File
    public function createBilan($campaign_id, $extra ,$path = true)
    {
        $day                  = Carbon::now()->subDays(2)->format('d-m-Y');
        $campaign             = AdrunCampaignModel::getInstance()->getADRUNCampaignByID($campaign_id);
        $this->campaign_id    = $campaign->cid_adtech;
        $this->detail         = $this->createFileDetail($campaign,$day);
        $this->extra          = $extra;
        
        if(!is_dir($this->report_main_path)):

            mkdir($this->report_main_path,0777,true);
            mkdir($this->report_campaign,0777,true);

        endif;
        
        if(!is_dir($this->report_campaign.$day)):

            mkdir($this->report_campaign.$day,0777,true);
        
            chmod($this->report_campaign.$day, 0777);  //changed to add the zero
        
        endif;
        
        $namexcx       = str_replace("/","_",$this->detail['name']);
        
        if( $path === true ):
            
            $path_trigger  = $this->report_campaign.$day;
            
         else:
            
            $path_trigger  = $this->report_campaign.$path;
         
            if (file_exists ($path_trigger.'/'.$namexcx.".xlsx")):

                unlink($path_trigger.'/'.$namexcx.".xlsx");

            endif;
            
            
        endif;
        
        AdrunCampaignModel::getInstance()->setADRUNDownloadURL($campaign_id, $namexcx);
        
        if (!file_exists($path_trigger.'/'.$namexcx.".xlsx")):
            
            Excel::create($namexcx, function($excel) use($campaign,$extra){
                
                $date = $this->createDateDetail( $campaign->start, $campaign->end );

                $excel->setTitle($this->detail['title']);
                $excel->setCreator($this->creator)->setCompany($this->company);
                $excel->setDescription($this->detail['description']);

                $data = [ ];

                $excel->sheet($this->TAB_1, function ($sheet) use ($data,$date,$extra) {

                    $objDrawing = new PHPExcel_Worksheet_Drawing;
                    $objDrawing->setPath( public_path('img/header-style-1.png') );
                    $objDrawing->setCoordinates( 'A1' );
                    $objDrawing->setName( "ADRUN" );
                    $objDrawing->setDescription( "ADRUN" );  
                    $objDrawing->setWorksheet( $sheet );
                    
                    $sheet->setStyle(array( 'font' => array( 'name' =>  'Calibri', 'size'      =>  13, )  ));
                    
                    $sheet->setOrientation('portrait');
                    $sheet->mergeCells('A1:F1');
                    $sheet->mergeCells('B3:E3');
                    
                    $sheet->cells('B3:B6', function($cells) { $cells->setFontColor('#00538c'); });
                    
                    $sheet->cells('A9:D13', function($cells) {
                        $cells->setFontColor('#000000');
                        $cells->setAlignment('left');

                    });
                    
                    $sheet->cells('A9:A13', function($cells) { $cells->setFont(array( 'bold'       =>  true )); });
                    
                    // Set width for multiple cells
                    $sheet->setWidth(array(
                        'A'     =>  5,
                        'B'     =>  25,
                        'C'     =>  25,
                        'D'     =>  25,
                        'E'     =>  25,
                        'F'     =>  5
                    ));
                    
                    // Set height for a single row
                    $sheet->setHeight(1, 110);
                    // Set font with ->setStyle()`
                    $sheet->setStyle(array( 'font' => array( 'name'      =>  'Calibri', 'size'      =>  13, ) ));
                    
                    $sheet->cell('B3', function($cell) { $cell->setFont(array( 'bold'  =>  true )); });
                    
                    $sheet->cell('B3', function($cell) {
                        // manipulate the cell
                        $cell->setValue('Annonceur');
                        // Set font
                        $cell->setFont(array(
                            'family'     => 'Calibri',
                            'size'       => '17',
                            'bold'       =>  true
                        ));
                        
                        $cell->setFontColor('#4B88C7');
                        $cell->setAlignment('left');

                    });
                    
                    // Set height for multiple rows
                    $sheet->setHeight(array(
                        4     =>  3,
                        5     => 40,
                        11    => 70,
                        15    =>  3,
                        16    => 40,
                    ));
                    
                    //Banner Seperator 
                    $sheet->cell('B4', function($cell) { $cell->setBackground('#00538C'); });
                    $sheet->cell('C4', function($cell) { $cell->setBackground('#3953A4'); });
                    $sheet->cell('D4', function($cell) { $cell->setBackground('#4C86C6'); });
                    $sheet->cell('E4', function($cell) { $cell->setBackground('#AED8E6'); });
                    //Banner Seperator 
                    $sheet->cell('B15', function($cell) { $cell->setBackground('#00538C'); });
                    $sheet->cell('C15', function($cell) { $cell->setBackground('#3953A4'); });
                    $sheet->cell('D15', function($cell) { $cell->setBackground('#4C86C6'); });
                    $sheet->cell('E15', function($cell) { $cell->setBackground('#AED8E6'); });
                    
                    //WEBSITE BANNER
                    $sheet->cells("B5:E5", function($cells) {
                        
                        $cells->setValignment('center');
                        $cells->setFontColor('#00538C');
                        $cells->setBackground('#F0F8FF');
                        $cells->setFont(array( 'bold' => true ));

                    });
                    //Banner Seperator 
                    $sheet->row(5, array( '', 'NOM','CAMPAIGN' , 'DATE DE DÉBUT', 'DATE DE FIN' ));
                    $sheet->row(6, array( '', $this->detail['annoceur_label'],$this->detail['campagne'], $date['start'], $date['end']));
                    
                    $sheet->cell('A8', function($cell) {
                    
                        $cell->setFont(array(
                            'family'     => 'Calibri',
                            'size'       => '9',
                            'bold'       =>  false
                        ));

                    });
                    
                    require_once( $this->section_path .'/SectionOne.php');
                    require_once( $this->section_path .'/SectionTwo.php');
                    require_once( $this->section_path .'/SectionThree.php');
                    require_once( $this->section_path .'/SectionFooter.php');
                    
                });

                $excel->setActiveSheetIndex(0);

            })->store('xlsx', $path_trigger.'/');
            //})->export('pdf', $path_trigger.'/');
               
            return $this->detail;
        
         endif;
         
      
         
            return null;
    }
    /*
     * Create the different files name
     */
    private function createFileDetail($data,$day)
    {
        
        $START                     = Carbon::parse($data->start)->format('d-m-Y');
        $END                       = Carbon::parse($data->end)->format('d-m-Y');
        
        $detail                    = [ ];
        $ANNOCEUR                  = str_replace(" ","_",$data->aname);
        $CAMPAGNE_DEVIS            = explode("-",$data->cname);
        $CAMPAGNE_DEVIS[1]         =(isset($CAMPAGNE_DEVIS[1])) ? $CAMPAGNE_DEVIS[1] : 'UNDEFINED';
        $CAMPAGNE                  = mb_strtoupper(preg_replace('/\s+/', '', $CAMPAGNE_DEVIS[1]));
        $DEVIS                     = mb_strtoupper(preg_replace('/\s+/', '', $CAMPAGNE_DEVIS[0]));
        $detail['name']            = mb_strtoupper(html_entity_decode("BILAN_{$ANNOCEUR}_{$DEVIS}_{$CAMPAGNE}_{$START}_{$END}"));
        $detail['title']           = mb_strtoupper(html_entity_decode("BILAN_{$ANNOCEUR}_{$DEVIS}_{$CAMPAGNE}_{$START}_{$END}"));
        $detail['description']     = mb_strtoupper($data->cdescription);
        $detail['annoceur']        = html_entity_decode($ANNOCEUR);
        $detail['annoceur_label']  = mb_strtoupper(html_entity_decode($data->aname));
        $detail['devis']           = $DEVIS;
        $detail['campagne']        = $CAMPAGNE;
        $detail['day']             = $day;
        
        return $detail;
    }
    
    /*
     * Create the date Detail format
     */
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
    
    
    /*
     * Get campaign impression/click per date
    */
   private function getDateImpCliTDC($row,$i_total,$c_total,$tx_percentage)
   {
       
       $datas = AdrunReportModel::getInstance()->getImpClickGroupByDate($this->campaign_id);
       
       $dates     = [];
       $sum_imps  = 0;
       $sum_click = 0;
       
       foreach ($datas as $data):
           
           if(!is_null($data)):
               
               $dates[$data->adtech_day]['sum_click'] = isset( $dates[$data->adtech_day]['sum_click'] ) ? $dates[$data->adtech_day]['sum_click'] : $sum_click;
               $dates[$data->adtech_day]['sum_imps']  = isset( $dates[$data->adtech_day]['sum_imps'] ) ? $dates[$data->adtech_day]['sum_imps'] : $sum_imps;
                   
               $dates[$data->adtech_day]['sum_click'] = $dates[$data->adtech_day]['sum_click'] + $data->clicks;
               $dates[$data->adtech_day]['sum_imps']  = $dates[$data->adtech_day]['sum_imps'] + $data->imps;
               $dates[$data->adtech_day]['date']      = Carbon::parse($data->adtech_day)->format('d m Y');
               
           endif;
           
           
       endforeach;
       
        $compile      = [];
        $total_clicks = 0;
        $total_imps   = 0;
       
       foreach ($dates as $value ):
           
           $percentage = $value['sum_click'] / $value['sum_imps'] * 100;
       
            $final[] =   [  
                
                            'BREAK'         => '',
                            'WEBSITE'       => $value['date'], 
                            'IMPRESSIONS'   =>  number_format($value['sum_imps'], 0 ," "," "), 
                            'CLICS'         =>  number_format($value['sum_click'], 0 ," "," "), 
                            'TAUX DE CLICS' =>  number_format($percentage,2).' %'
                         ];
            
            $total_clicks = isset( $compile['total'][2] ) ? $compile['total'][2] : $total_clicks ;
            $total_imps = isset( $compile['total'][1] ) ? $compile['total'][1] : $total_imps ;
            
       endforeach;
       
        
        $compile['number'] = count($final) + $row;
        $compile['next']   = $row + 1;
        $compile['data']   = $final;
        $compile['total']  = array('','TOTAL', number_format($i_total, 0 ," "," "), number_format($c_total, 0 ," "," "), number_format($tx_percentage, 2).' %' ) ;
        
    return $compile;
   } 
    
    /*
     * Get campaign impression/click per Website
    */
    private function getWeImpCliTDC($row,$datax,$i_total,$c_total,$tx_percentage)
    {
        
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
        
        if (array_key_exists("ZINFOS974_MOBILE",$website)):
            
            $child_imps = $website['ZINFOS974_MOBILE'][0]['IMPRESSIONS'];
            $child_clks = $website['ZINFOS974_MOBILE'][0]['CLICS'];

            $website['ZINFOS974'][0] = [

                'IMPRESSIONS' => $website['ZINFOS974'][0]['IMPRESSIONS'] + $child_imps,
                'CLICS'       => $website['ZINFOS974'][0]['CLICS'] + $child_clks

            ];

            unset($website['ZINFOS974_MOBILE']);
            
        endif;
        
        if (array_key_exists("7MAGAZINE_MOBILE",$website)):
            
            $child_imps = $website['7MAGAZINE_MOBILE'][0]['IMPRESSIONS'];
            $child_clks = $website['7MAGAZINE_MOBILE'][0]['CLICS'];

            $website['7MAGAZINE'][0] = [

                'IMPRESSIONS' => $website['7MAGAZINE'][0]['IMPRESSIONS'] + $child_imps,
                'CLICS'       => $website['7MAGAZINE'][0]['CLICS'] + $child_clks

            ];

            unset($website['7MAGAZINE_MOBILE']);
            
        endif;
        
        if (array_key_exists("FAITSDIVERS_MOBILE",$website)):
            
            $child_imps = $website['FAITSDIVERS_MOBILE'][0]['IMPRESSIONS'];
            $child_clks = $website['FAITSDIVERS_MOBILE'][0]['CLICS'];

            $website['FAITSDIVERS'][0] = [

                'IMPRESSIONS' => $website['FAITSDIVERS'][0]['IMPRESSIONS'] + $child_imps,
                'CLICS'       => $website['FAITSDIVERS'][0]['CLICS'] + $child_clks

            ];

            unset($website['FAITSDIVERS_MOBILE']);
            
        endif;
        
        if (array_key_exists("LAPUB_MOBILE",$website)):
            
            $child_imps = $website['LAPUB_MOBILE'][0]['IMPRESSIONS'];
            $child_clks = $website['LAPUB_MOBILE'][0]['CLICS'];

            $website['LAPUB'][0] = [

                'IMPRESSIONS' => $website['LAPUB'][0]['IMPRESSIONS'] + $child_imps,
                'CLICS'       => $website['LAPUB'][0]['CLICS'] + $child_clks

            ];

            unset($website['LAPUB_MOBILE']);
            
        endif;
        
        if (array_key_exists("FAITSDIVERS",$website)):
            
            $child_imps = $website['FAITSDIVERS'][0]['IMPRESSIONS'];
            $child_clks = $website['FAITSDIVERS'][0]['CLICS'];

            $website['ZINFOS974'][0] = [

                'IMPRESSIONS' => $website['ZINFOS974'][0]['IMPRESSIONS'] + $child_imps,
                'CLICS'       => $website['ZINFOS974'][0]['CLICS'] + $child_clks

            ];

            unset($website['FAITSDIVERS']);
            
        endif;
        
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
                        'IMPRESSIONS'   =>  $t_imps, 
                        'CLICS'         =>  number_format($t_click, 0 ," "," "), 
                        'TAUX DE CLICS' =>  number_format($t_percentage,2).' %'
                    ];
            
        endforeach; 
        
        $datas = $this->multid_sort($data, 'IMPRESSIONS');
     
        $quality = [];
        
        foreach ($datas as $data):
            
            $quality[]= [
                            'BREAK'         =>  '',
                            'WEBSITE'       => $data['WEBSITE'],
                            'IMPRESSIONS'   => number_format($data['IMPRESSIONS'], 0 ," "," "),
                            'CLICS'         => $data['CLICS'],
                            'TAUX DE CLICS' => $data['TAUX DE CLICS'],
                        ];
            
        endforeach;
        
        $compile           = [];
        $compile['number'] = count($quality) + $row;
        $compile['next']   = $row + 1;
        $compile['data']   = $quality;
        $compile['total']  = array('','TOTAL', number_format($i_total, 0 ," "," "), number_format($c_total, 0 ," "," "), number_format($tx_percentage, 2).' %' ) ;
        
        return $compile;
        
    }
    
    /*
     * Get campaign impression/click per Flight
    */
    private function getFlImpCliTDC()
    {
        
        $this->slaves = AdrunCampaignModel::getInstance()->getADRUNSlaveCampaignByMID($this->campaign_id);
        
        $datax   = $this->getImpressionsClicksPerWebsite();
        $i_total = 0;
        $c_total = 0;
        
        foreach ($datax as $key => $value ):

            $impressions  = isset($value['total']['impressions']) ?$value['total']['impressions']: '0';
            $clics        = isset($value['total']['clics']) ? $value['total']['clics'] : '0';
            $percentage   = ($clics / $impressions) * 100;

                $data[]   =   [
                    'FLIGHT'        => $key, 
                    'IMPRESSIONS'   =>  number_format($impressions , 0 ," "," "), 
                    'CLICS'         =>  number_format($clics, 0 ," "," "), 
                    'TAUX DE CLICS' => isset($percentage) ? number_format($percentage, 2).' %' : number_format('0', 2).' %'
                ];

            $i_total      = $i_total += $impressions;
            $c_total      = $c_total += $clics; 

        endforeach;
          
        $tx_percentage    = $c_total / $i_total * 100;
        $fine_tune_data   = $this->FlImpCliFineTunning( $data );

        $compile                  = [];
        $compile['xxx']           = $datax;
        $compile['number']        = count($fine_tune_data) + 17;
        $compile['data']          = $fine_tune_data;
        $compile['i_total']       = $i_total;
        $compile['c_total']       = $c_total;
        $compile['tx_percentage'] = $tx_percentage;
        $compile['total']         = array('','TOTAL', number_format($i_total, 0 ," "," "), number_format($c_total, 0 ," "," "), number_format($tx_percentage, 2).' %' );
        
        return $compile;
        
    }
    
    /*
     * Sort the multidimention  Array
    */
    
    private function multid_sort($arr, $index) {
        $b = array();
        $c = array();
        foreach ($arr as $key => $value) {
            $b[$key] = $value[$index];
        }

        arsort($b);

        foreach ($b as $key => $value) {
            $c[] = $arr[$key];
        }

        return $c;
    }
    
    private function getImpressionsClicksPerWebsite()
    {
        
        $items= [];
        $i=1;
        
        foreach( $this->slaves as $slave ):
          
            $datas = AdrunReportModel::getInstance()->getImpressionPerWebsiteByCampaign($slave->id_adtech);
            
            if(!$datas->isEmpty()):
                
                $total = 0;
                $sum   = 0;
                
                foreach ($datas as $data) :
                    
                $ttc         = isset($items[$slave->name]['data'][$data->editeur]['clics']) ? $items[$slave->name]['data'][$data->editeur]['clics']: '0';
                $tti         = isset($items[$slave->name]['data'][$data->editeur]['impressions']) ? $items[$slave->name]['data'][$data->editeur]['impressions']: '0';
                $slave->name = mb_strtoupper(str_replace(" ","_",$slave->name));
                
                $items[$slave->name]['data'][$data->editeur] =  [
                                                                    'clics'       => $ttc+= $data->clicks,
                                                                    'impressions' => $tti+= $data->imps,

                                                                ];
                
                $items[$slave->name]['total']                = [
                                                                    'impressions'   => $total += $data->imps,
                                                                    'clics'         => $sum   += $data->clicks,
                                                                ];
                endforeach;
                
            endif;
            
        endforeach;
        
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
        
    }
    
    public function FlImpCliFineTunning ( $datas ) {
        
        $finetune = [];
        $imp = 0;
        
        foreach( $datas as $data ):
            
            $data["IMPRESSIONS"] = (int) str_replace(" ","",$data["IMPRESSIONS"]);
            $data["CLICS"]       = (int) str_replace(" ","",$data["CLICS"]);
            
            $params = explode("_",$data["FLIGHT"]);
            
            if(end($params) != 'OFFERT'):
                
                $finetune[$params[0]]['IMPRESSIONS'] = (isset($finetune[$params[0]]['IMPRESSIONS'])) ? $finetune[$params[0]]['IMPRESSIONS'] : 0;
                $finetune[$params[0]]['CLICS']       = (isset($finetune[$params[0]]['CLICS'])) ? $finetune[$params[0]]['CLICS'] : 0;

                $finetune[$params[0]]['NAME']        = $params[0];
                $finetune[$params[0]]['IMPRESSIONS'] = $finetune[$params[0]]['IMPRESSIONS'] += $data["IMPRESSIONS"];
                $finetune[$params[0]]['CLICS']       = $finetune[$params[0]]['CLICS'] += $data["CLICS"];
            
            elseif(end($params) === 'OFFERT'):
                
                $finetune[$params[0].'-OFFERT']['IMPRESSIONS'] = (isset($finetune[$params[0].'-OFFERT']['IMPRESSIONS'])) ? $finetune[$params[0].'-OFFERT']['IMPRESSIONS'] : 0;
                $finetune[$params[0].'-OFFERT']['CLICS']       = (isset($finetune[$params[0].'-OFFERT']['CLICS'])) ? $finetune[$params[0].'-OFFERT']['CLICS'] : 0;

                $finetune[$params[0].'-OFFERT']['NAME']        = $params[0].'-OFFERT';
                $finetune[$params[0].'-OFFERT']['IMPRESSIONS'] = $finetune[$params[0].'-OFFERT']['IMPRESSIONS'] += $data["IMPRESSIONS"];
                $finetune[$params[0].'-OFFERT']['CLICS']       = $finetune[$params[0].'-OFFERT']['CLICS'] += $data["CLICS"];
                
                
            else:
                
                
                
            endif;
            
        endforeach;
        
        foreach( $finetune as $value):
            
            $percentage   = ( $value["CLICS"] / $value["IMPRESSIONS"] ) * 100;
        
            $datat[]   =   [
                    'BREAK'        => '',
                    'FLIGHT'        => $value["NAME"], 
                    'IMPRESSIONS'   =>  number_format($value["IMPRESSIONS"] , 0 ," "," "), 
                    'CLICS'         =>  number_format($value["CLICS"], 0 ," "," "), 
                    'TAUX DE CLICS' => isset($percentage) ? number_format($percentage, 2).' %' : number_format('0', 2).' %'
                ];
        
        endforeach;
        
        return $datat;
        
    }
    
    
}
