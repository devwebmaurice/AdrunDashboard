<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use stdClass;

use Adrun\Settings\SettingsController;

class adrun_annonceur extends Model
{
    
    public static $params;
    private static $_instance; // L'attribut qui stockera l'instance unique

    public function __construct() {
        
             
        
    }
    
    public static function getParam () {
        
        return SettingsController::getAdrunParams();
    }

    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }

    public static function getAnnonceurs()
    {
        $getParam = self::getParam();
        
        $annonceurs = DB::table($getParam['TBL_ANNONCEUR'].' AS a')
            ->join($getParam['TBL_ANNONCEUR_STU'].' AS s', 'a.id_status', '=', 's.id')
            ->join($getParam['TBL_USER'].' AS u', 'u.id', '=', 'a.id_created_by')
            ->select('a.id  AS id', 'a.v_name AS name', 'a.v_type AS type', 'a.created_date AS created_date', 's.name AS status', 'u.name AS created_by')
            ->get();
       
        return $annonceurs;
    }
    
    
    public static function deleteAnnonceur($id)
    {
        $getParam = self::getParam();
        
        DB::table($getParam['TBL_ANNONCEUR'])->where('id', '=', $id)->delete();
        
    }
    
    public static function editAnnonceur($data,$id)
    {
        
        $getParam = self::getParam();
        
        DB::table($getParam['TBL_ANNONCEUR'])
            ->where('id', $id)
            ->update($data);
        
    }
    
    public static function getAnnonceurByID($id)
    {
        $getParam = self::getParam();
        
        $annonceur = DB::table($getParam['TBL_ANNONCEUR'])->where('id', $id)->first();
        
        return $annonceur;
    }
    
}
