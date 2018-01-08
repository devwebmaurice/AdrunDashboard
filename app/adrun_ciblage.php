<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use stdClass;

use Adrun\Settings\SettingsController;

class adrun_ciblage extends Model
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

    public static function getCiblages()
    {
        $getParam = self::getParam();
        
        $ciblages = DB::table($getParam['TBL_CIBLAGE'].' AS a')
            ->join($getParam['TBL_ANNONCEUR_STU'].' AS s', 'a.status', '=', 's.id')
            ->join($getParam['TBL_USER'].' AS u', 'u.id', '=', 'a.created_by')
            ->join($getParam['TBL_CIBLAGE_HAS_COMBINATION'].' AS c', 'a.id', '=', 'c.id_ciblage_master')
            ->join($getParam['TBL_CIBLAGE'].' AS ab', 'ab.id', '=', 'c.id_ciblage_slave')
            ->select('a.id  AS id', 'a.name AS name', 'a.date_created AS created_date', 's.name AS status', 'u.name AS created_by', 'ab.name AS combine')
            ->get();
        
        foreach($ciblages as $ciblage):
            
            $x[$ciblage->name]['datas'][] = $ciblage ;
            $x[$ciblage->name]['id']      = $ciblage->id;
            $x[$ciblage->name]['status']  = $ciblage->status;
            $x[$ciblage->name]['name']    = $ciblage->name;
        endforeach;
        
        return $x;
    }
    public static function getCiblagesNameOnly()
    {
        $getParam = self::getParam();
        
        $ciblages = DB::table($getParam['TBL_CIBLAGE'].' AS a')
            ->join($getParam['TBL_ANNONCEUR_STU'].' AS s', 'a.status', '=', 's.id')
            ->join($getParam['TBL_USER'].' AS u', 'u.id', '=', 'a.created_by')
            ->where('a.status', '=', 0)
            ->select('a.name AS name','a.id AS id')
            ->get();
       
        return $ciblages;
    }
    
    public static function getCiblagesByName($name)
    {
        $getParam = self::getParam();
        
        $id = DB::table($getParam['TBL_CIBLAGE'])
                    ->where('name', '=', $name)
                    ->select('id')
                    ->get();
        
        return $id[0]->id;
    }
}
