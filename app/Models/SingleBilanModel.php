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
        $this->TAB_1_TITLE       = "BILAN DE CAMPAGNE";
        $this->TAB_2_TITLE       = "PAR SITE WEB";
        $this->TAB_3_TITLE       = "PAR FLIGHT PAR SITE WEB";
        $this->TAB_1_SUB_1_TITLE = "////// > Global";
        $this->creator           = "Me";
        $this->company           = "ADRUN LTD";
        
        $this->report_main_path  = ( $_SERVER['APP_ENV'] === 'local' ) ? base_path()."/adrun" : '/var/www/html/adrun/services/dashboard/adrun';
        $this->report_campaign   = $this->report_main_path . "/campaign/";
    }
    
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    
    public function createBilan($campaign_id,$extra)
    {
        
        $day                  = Carbon::now()->subDays(2)->format('d-m-Y');
        $campaign             = AdrunCampaignModel::getInstance()->getADRUNCampaignByID($campaign_id);
        $this->campaign_id    = $campaign->cid_adtech;
        $this->detail         = $this->createFileDetail($campaign,$day);
        $this->extra         = $extra;
        
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
            
            
            Excel::create($namexcx, function($excel) use($campaign,$extra){
                
                $date = $this->createDateDetail($campaign->start, $campaign->end);

                // Set the title
                $excel->setTitle($this->detail['title']);

                // Chain the setters
                $excel->setCreator($this->creator)->setCompany($this->company);

                $excel->setDescription($this->detail['description']);

                $data = [ ];

                $excel->sheet($this->TAB_1, function ($sheet) use ($data,$date,$extra) {

                    $sheet->setStyle(array(
                        'font' => array(
                            'name'      =>  'Calibri',
                            'size'      =>  13,
                        )
                    ));
                    
                    $sheet->setOrientation('landscape');
                    $sheet->mergeCells('A1:D1');
                    $sheet->mergeCells('B3:D3');
                    $sheet->mergeCells('B4:D4');
                    $sheet->mergeCells('B5:D5');
                    $sheet->mergeCells('B6:D6');
                    $sheet->setBorder('A2:B4', 'thin');
                    
                    $sheet->cells('A3:A6', function($cells) {

                        $cells->setBackground('#cde4f2');
                        $cells->setFontColor('#00538c');

                    });
                    
                    $sheet->cells('B3:B6', function($cells) {

                        $cells->setFontColor('#00538c');

                    });
                    
                    $sheet->cells('A9:D13', function($cells) {

                        $cells->setBackground('#0085c1');
                        $cells->setFontColor('#FFFFFF');
                        
                        $cells->setAlignment('left');

                    });
                    
                    $sheet->cells('A16:D16', function($cells) {

                        $cells->setBackground('#0085c1');
                        $cells->setFontColor('#FFFFFF');
                        
                        $cells->setFont(array(
                            'bold'       =>  true
                        ));

                    });
                    
                    $sheet->cells('A9:A13', function($cells) {
                        
                        $cells->setFont(array(
                            'bold'       =>  true
                        ));
                        
                    });
                    
                    // Set width for multiple cells
                    $sheet->setWidth(array(
                        'A'     =>  25,
                        'B'     =>  25,
                        'C'     =>  25,
                        'D'     =>  25
                    ));
                    
                    // Set height for multiple rows
                    $sheet->setHeight(array(
                        
                    ));
                    
                    // Set font with ->setStyle()`
                    $sheet->setStyle(array(
                        'font' => array(
                            'name'      =>  'Calibri',
                            'size'      =>  13,
                        )
                    ));
                    
                    //$sheet->setPageMargin( array( 0.25, 0.30, 0.25, 0.30 ) );

                    $sheet->cell('B3', function($cell) {
                        
                        // Set font
                        $cell->setFont(array(
                            'bold'       =>  true
                        ));

                    });
                    
                    

                    $sheet->cell('A1', function($cell) {
                        // manipulate the cell
                        $cell->setValue($this->TAB_1_TITLE);
                        // Set font
                        $cell->setFont(array(
                            'family'     => 'Calibri',
                            'size'       => '19',
                            'bold'       =>  true
                        ));
                        
                        $cell->setBackground('#00538c');
                        $cell->setFontColor('#ffffff');
                        $cell->setAlignment('center');

                    });

                    $sheet->row(3, array( 'Annonceur :', $this->detail['annoceur_label'] ));
                    $sheet->row(4, array( 'Campagne :', $this->detail['campagne'] .' '.$this->detail['devis'] ));
                    $sheet->row(5, array( 'Début :', $date['start']));
                    $sheet->row(6, array( 'Fin :', $date['end'] ));
                    
                    
                    $sheet->cell('A8', function($cell) {
                        // manipulate the cell
                        $cell->setValue($this->TAB_1_SUB_1_TITLE);
                        // Set font
                        $cell->setFont(array(
                            'family'     => 'Calibri',
                            'size'       => '9',
                            'bold'       =>  false
                        ));

                    });
                    
                    $sheet->cell('A9', function($cell) { $cell->setValue('Impressions'); });
                    $sheet->cell('A10', function($cell) { $cell->setValue('Clics'); });
                    $sheet->cell('A11', function($cell) { $cell->setValue('CTR'); });
                    $sheet->cell('A12', function($cell) { $cell->setValue('Visiteurs Uniques'); });
                    $sheet->cell('A13', function($cell) { $cell->setValue('Répétition'); });
                    
                    $FICC = $this->getFlImpCliTDC();
                    
                    $this->imp  = $FICC['total'][1];
                    $this->clk  = $FICC['total'][2];
                    $this->rate = $FICC['total'][3];
                    
                    //$repe = $extra['imps'] - $extra['vu'];
                    
                    
                    
                    
//                    $sheet->cell('D9', function($cell) { $cell->setValue( $this->imp ); });
//                    $sheet->cell('D10', function($cell) { $cell->setValue($this->clk); });
//                    $sheet->cell('D11', function($cell) { $cell->setValue($this->rate); });
//                    $sheet->cell('D12', function($cell) { $cell->setValue('UNDEFINED'); });
//                    $sheet->cell('D13', function($cell) { $cell->setValue('UNDEFINED'); });
                    
                    $sheet->cell('D9', function($cell) { 
                        
                        $imps = preg_replace('/\s+/u', '', $this->extra['imps']);
                         
                        $cell->setValue( number_format($imps) ); 
                        
                        
                    });
                    $sheet->cell('D10', function($cell) { $cell->setValue( $this->extra['clic'] ); });
                    $sheet->cell('D11', function($cell) { $cell->setValue( $this->rate ); });
                    $sheet->cell('D12', function($cell) { 
                       
                        $vu = preg_replace('/\s+/u', '', $this->extra['vu']);
                        
                        $cell->setValue( $vu ); 
                        
                        
                    });
                    $sheet->cell('D13', function($cell) { 
                        
                        
                        
                        $cell->setValue( 2131 ); 
                        
                        
                        
                    });
                    
                    
                    $sheet->cell('A15', function($cell) {
                        // manipulate the cell
                        $cell->setValue('////// > Détails par Insertion');
                        // Set font
                        $cell->setFont(array(
                            'family'     => 'Calibri',
                            'size'       => '9',
                            'bold'       =>  false
                        ));

                    });
                    
                    $sheet->row(16, array('Flight', 'Impressions','Clics', 'CTR') );
                    
                    $sheet->fromArray( $FICC['data'], NULL, 'A17',FALSE,FALSE );
                    
                    $sheet->cells("B{$FICC['number']}:D{$FICC['number']}", function($cells) {

                        $cells->setBorder('solid', 'none', 'solid', 'none');
                        // Set alignment to center
                        $cells->setAlignment('right');
                        
                        $cells->setFont(array(
                            'bold'       =>  true
                        ));

                    });
                    
                    $sheet->row($FICC['number'], $FICC['total'] );
                    
                    $next_row = $FICC['number'] + 2 ;
                    
                    $sheet->cells("A{$next_row}:D{$next_row}", function($cells) {

                        $cells->setBackground('#0085c1');
                        $cells->setFontColor('#FFFFFF');
                        
                        $cells->setFont(array(
                            'bold'       =>  true
                        ));

                    });
                    
                    $sheet->row($next_row, array('Website', 'Impressions','Clics', 'CTR') );
                    
                    $WICC = $this->getWeImpCliTDC($next_row, $FICC['xxx'],$FICC['i_total'],$FICC['c_total'],$FICC['tx_percentage']);
                    
                    $total_row = $WICC['number'] + 1;
                    
                    $sheet->rows( $WICC['data'], NULL, "A{$WICC['next']}",FALSE,FALSE );
                    
                    $sheet->row($total_row, $WICC['total'] );
                    
                    $sheet->cells("A{$total_row}:D{$total_row}", function($cells) {
                        
                        $cells->setBorder('solid', 'none', 'solid', 'none');
                        // Set alignment to center
                        $cells->setAlignment('right');
                        $cells->setFont(array(
                            'bold'       =>  true
                        ));
                        

                    });
                    
                    $sheet->cells("A{$WICC['next']}:D{$WICC['number']}", function($cells) {

                        $cells->setAlignment('left');

                    });
                    
                    
                    $footer_row = $WICC['number'] + 4 ;
                    
                    $sheet->mergeCells("A{$footer_row}:D{$footer_row}");
                     
                    $sheet->cell("A{$footer_row}", function($cell) {
                        // manipulate the cell
                        $cell->setValue('ADRUN Inteligence Dashboard');
                        
                        $cell->setAlignment('center');
                        $cell->setValignment('center');
                        // Set font
                        $cell->setFont(array(
                            'family'     => 'Calibri',
                            'size'       => '7'
                        ));
                        
                    });
                    
                    $sheet->setHeight($footer_row, 50); 
                    
                    
                });

                $excel->setActiveSheetIndex(0);

            })->store('xlsx', $this->report_campaign.$day.'/');
            
            return $this->detail;
        
         endif;
         
         
            return null;
    }
    
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
                        'CLICS'         =>  number_format($t_click), 
                        'TAUX DE CLICS' =>  number_format($t_percentage,2).' %'
                    ];
            
            
        endforeach; 
        
        $datas = $this->multid_sort($data, 'IMPRESSIONS');
        
        $quality = [];
        
        foreach ($datas as $data):
            
            $quality[]= [
                            'WEBSITE'       => $data['WEBSITE'],
                            'IMPRESSIONS'   => number_format($data['IMPRESSIONS']),
                            'CLICS'         => $data['CLICS'],
                            'TAUX DE CLICS' => $data['TAUX DE CLICS'],
                        ];
            
        endforeach;
        
        $compile           = [];
        $compile['number'] = count($quality) + $row;
        $compile['next']   = $row + 1;
        $compile['data']   = $quality;
        $compile['total']  = array('', number_format($i_total), number_format($c_total), number_format($tx_percentage, 2).' %' ) ;
        
        return $compile;
        
    }
    
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
    
    
    private function getFlImpCliTDC()
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
           
           $compile                  = [];
           $compile['xxx']           = $datax;
           $compile['number']        = count($data) + 17;
           $compile['data']          = $data;
           $compile['i_total']       = $i_total;
           $compile['c_total']       = $c_total;
           $compile['tx_percentage'] = $tx_percentage;
           $compile['total']         = array('', number_format($i_total), number_format($c_total), number_format($tx_percentage, 2).' %' );
           
        return $compile;
        
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
