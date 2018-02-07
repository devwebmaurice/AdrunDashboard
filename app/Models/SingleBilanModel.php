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
    }
    
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    
    //Create The Excel File
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
        
        //rmdir($this->report_campaign.$day);
        
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

                    $objDrawing = new PHPExcel_Worksheet_Drawing;
                    $objDrawing->setPath(public_path('img/Logo100x100.png')); //your image path
                    $objDrawing->setCoordinates('D3');
                    $objDrawing->setName("ADRUN");
                    $objDrawing->setDescription("ADRUN");                                                       
                    $objDrawing->setWorksheet($sheet);
                    
                    
                    $sheet->setStyle(array(
                        'font' => array(
                            'name'      =>  'Calibri',
                            'size'      =>  13,
                        )
                    ));
                    
                    $sheet->setOrientation('landscape');
                    $sheet->mergeCells('A1:D1');
                    $sheet->mergeCells('B3:C3');
                    $sheet->mergeCells('B4:C4');
                    $sheet->mergeCells('B5:C5');
                    $sheet->mergeCells('B6:C6');
                    $sheet->mergeCells('D3:D6');
                    $sheet->setBorder('A2:B4', 'thin');
                    
                    $sheet->cells('A3:A6', function($cells) {

                        $cells->setBackground('#D8D8D8');
                        $cells->setFontColor('#00538c');

                    });
                    
                    $sheet->cells('B3:B6', function($cells) {

                        $cells->setFontColor('#00538c');

                    });
                    
                    $sheet->cells('A9:D13', function($cells) {

                        //$cells->setBackground('#0085c1');
                        $cells->setFontColor('#000000');
                        $cells->setAlignment('left');

                    });
                    
                    $sheet->cells('A16:D16', function($cells) {

                        $cells->setBackground('#D8D8D8');
                        $cells->setFontColor('#000000');
                        
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
                    
                    $sheet->cell('D3', function($cell) {
                        
                        $cell->setAlignment('center');

                    });
                    

                    $sheet->row(3, array( 'Annonceur :', $this->detail['annoceur_label'] ));
                    $sheet->row(4, array( 'Campagne :', $this->detail['campagne'] .' '.$this->detail['devis'] ));
                    $sheet->row(5, array( 'Début :', $date['start']));
                    $sheet->row(6, array( 'Fin :', $date['end'] ));
                    
                    $sheet->cell('A8', function($cell) {
                        // manipulate the cell
                        //$cell->setValue($this->TAB_1_SUB_1_TITLE);
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
                    
                    $FICC       = $this->getFlImpCliTDC();
                    $this->imp  = $FICC['total'][1];
                    $this->clk  = $FICC['total'][2];
                    $this->rate = $FICC['total'][3];
                    
                    $sheet->cell('D9', function($cell) { 
                        
                        $cell->setValue( $this->imp ); 
                        
                    });
                    $sheet->cell('D10', function($cell) { $cell->setValue( $this->clk ); });
                    $sheet->cell('D11', function($cell) { $cell->setValue( $this->rate ); });
                    $sheet->cell('D12', function($cell) { 
                       
                        $vu = preg_replace('/\s+/u', '', $this->extra['vu']);
                        
                        $cell->setValue( number_format($vu, 0 ," "," ") ); 
                        
                    });
                    $sheet->cell('D13', function($cell) { 
                        
                        $vu        = (int) preg_replace('/\s+/u', '', $this->extra['vu']);
                        $this->imp = str_replace(' ','',$this->imp);
                        
                        $rep = $this->imp / (int) $vu;
                        
                        $cell->setValue( number_format( $rep , 2 ,"."," ") ); 
                        
                    });
                    
                    $sheet->cell('A15', function($cell) {
                        // manipulate the cell
                        //$cell->setValue('////// > Détails par Insertion');
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

                        $cells->setBackground('#D8D8D8');
                        $cells->setFontColor('#000000');
                        
                        $cells->setFont(array(
                            'bold'       =>  true
                        ));

                    });
                    
                    $sheet->row($next_row, array('Website', 'Impressions','Clics', 'CTR') );
                    
                    $WICC      = $this->getWeImpCliTDC($next_row, $FICC['xxx'],$FICC['i_total'],$FICC['c_total'],$FICC['tx_percentage']);
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
                    
                    $next_row2 = $WICC['number'] + 3 ;
                    
                    $sheet->cells("A{$next_row2}:D{$next_row2}", function($cells) {

                        $cells->setBackground('#D8D8D8');
                        $cells->setFontColor('#000000');
                        
                        $cells->setFont(array(
                            'bold'       =>  true
                        ));

                    });
                    
                    $sheet->row($next_row2, array('Date', 'Impressions','Clics', 'CTR') );
                    
                    $DICC       = $this->getDateImpCliTDC($next_row2,$FICC['i_total'],$FICC['c_total'],$FICC['tx_percentage']);
                    $total_rowx = $DICC['number'] + 1;
                    $sheet->rows( $DICC['data'], NULL, "A{$DICC['next']}",FALSE,FALSE );
                    $sheet->row($total_rowx, $DICC['total'] );
                    
                   $sheet->cells("A{$total_rowx}:D{$total_rowx}", function($cells) {
                        
                        $cells->setBorder('solid', 'none', 'solid', 'none');
                        // Set alignment to center
                        $cells->setAlignment('right');
                        $cells->setFont(array(
                            'bold'       =>  true
                        ));

                    });
                    
                    $sheet->cells("A{$DICC['next']}:D{$DICC['number']}", function($cells) {

                        $cells->setAlignment('left');

                    });
                    
                    $footer_row = $DICC['number'] + 4 ;
                    
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
            
            die("phase five");
            
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
                             'WEBSITE'        => $value['date'], 
                             'IMPRESSIONS'   =>  number_format($value['sum_imps'], 0 ," "," "), 
                             'CLICS'         =>  number_format($value['sum_click'], 0 ," "," "), 
                             'TAUX DE CLICS' =>  number_format($percentage,2).' %'
                         ];
            
            $total_clicks = isset( $compile['total'][2] ) ? $compile['total'][2] : $total_clicks ;
            $total_imps = isset( $compile['total'][1] ) ? $compile['total'][1] : $total_imps ;
            
            
//            $i_total = $total_imps + $value['sum_imps'];
//            $c_total = $total_clicks + $value['sum_click'];
            
       endforeach;
       
        
        $compile['number'] = count($final) + $row;
        $compile['next']   = $row + 1;
        $compile['data']   = $final;
        $compile['total']  = array('', number_format($i_total, 0 ," "," "), number_format($c_total, 0 ," "," "), number_format($tx_percentage, 2).' %' ) ;
        

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
        $compile['total']  = array('', number_format($i_total, 0 ," "," "), number_format($c_total, 0 ," "," "), number_format($tx_percentage, 2).' %' ) ;
        
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

        $compile                  = [];
        $compile['xxx']           = $datax;
        $compile['number']        = count($data) + 17;
        $compile['data']          = $data;
        $compile['i_total']       = $i_total;
        $compile['c_total']       = $c_total;
        $compile['tx_percentage'] = $tx_percentage;
        $compile['total']         = array('', number_format($i_total, 0 ," "," "), number_format($c_total, 0 ," "," "), number_format($tx_percentage, 2).' %' );
        
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
    
    
}
