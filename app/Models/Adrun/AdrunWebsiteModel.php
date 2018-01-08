<?php

namespace App\Models\Adrun;

use Illuminate\Database\Eloquent\Model;
use DB;
use stdClass;
use Carbon\Carbon;

class AdrunWebsiteModel extends Model
{
    private static $_instance;
    
    public function __construct()
    {
        
        $this->tbl_campaign   = \Config::get('adrun.table.campaign');
        $this->tbl_advertiser = \Config::get('adrun.table.advertiser');
        $this->tbl_editeur    = \Config::get('adrun.table.editeur');
        $this->tbl_report_sum = \Config::get('adrun.table.TBL_REPORT_SUMMARY');
        
    }
    
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    
    public function getEditeurList() 
    {
        
        $editeurs = DB::table($this->tbl_editeur . ' AS e')
            ->select('e.id AS id', 'e.id_adtech AS id_adtech')
            ->whereNotNull('id_adtech')
            ->orderBy('e.id', 'desc')
            ->get();
        
        return $editeurs;
        
    }
    
    public function getAllEditeurs() 
    {
        
        $editeurs = DB::table($this->tbl_editeur . ' AS e')
            ->whereNotNull('e.id_adtech')
            ->where('e.adrun', '=', 0)
            ->orderBy('e.id', 'desc')
            ->get();
        
        return $editeurs;
        
    }
    
    public function getAllEditeursLastMonth() 
    {
        
        $editeurs = DB::table($this->tbl_editeur . ' AS e')
            ->whereNotNull('e.id_adtech')
            ->where('e.adrun', '=', 0)
            ->orderBy('e.id', 'desc')
            ->get();
        
        return $editeurs;
        
    }
    
    public function getImpression($id)
    {
        $start = new Carbon('first day of last month');
        $end  = new Carbon('last day of last month');
        
        $editeur = DB::table($this->tbl_report_sum.' AS c')
            ->select( DB::raw('SUM(c.imps) AS cimps'))
            ->whereBetween('adrun_day', array($start->endOfDay(), $end->endOfDay()))
            ->where('c.websiteId', '=', $id)
            ->get();
        
        if(count($editeur[0]->cimps) > 0):
            
            return number_format($editeur[0]->cimps);
        
        endif;
        
    
        
        return 0;
        
        
    }
    
    public function getClick($id)
    {
        $start = new Carbon('first day of last month');
        $end  = new Carbon('last day of last month');
        
        
        $editeur = DB::table($this->tbl_report_sum.' AS c')
            ->select( DB::raw('SUM(c.clicks) AS cclicks'))
            ->whereBetween('adrun_day', array($start->endOfDay(), $end->endOfDay()))
            ->where('c.websiteId', '=', $id)
            ->get();
        
        
        if(count($editeur[0]->cclicks) > 0):
            
            return number_format($editeur[0]->cclicks);
        
        endif;
        
        return 0;
        
        
    }
    
    public function checkEditeurADRUN($id)
    {
        $editeur = DB::table($this->tbl_editeur)->where('id_adtech', '=', $id)->first();
        
        if(is_null($editeur)):
            
            return FALSE;
        
        endif;
        
        return TRUE;
    }
    
    public function addEditeurToADRUN($data)
    {
        
        //To change table name
        $id = DB::table($this->tbl_editeur)->insertGetId(
            [ 'id_adtech'             => $data->id, 
              'url'                   => $data->URL,
              'name'                  => $data->name, 
              'target'                => 'Local',
              'contact_first_name'    => $data->contact->firstName, 
              'contact_last_name'     => $data->contact->lastName,
              'company_name'                  => $data->name,  
            ]
            );
        
        
        return $id;
        
    }
    
    
    
}
