<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use stdClass;

use Adrun\Settings\SettingsController;

class adrun_format extends Model
{
    
   public static $params;
    private static $_instance; // L'attribut qui stockera l'instance unique

    public function __construct() { }
     
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

    public static function getFormats()
    {
        $getParam = self::getParam();
        
        $formats = DB::table($getParam['TBL_FORMAT'].' AS f')
                ->join($getParam['TBL_ANNONCEUR_STU'].' AS s', 'f.status', '=', 's.id')
                ->join($getParam['TBL_USER'].' AS u', 'u.id', '=', 'f.created_by')
                ->join($getParam['TBL_FORMAT_HAS_TYPE'].' AS t', 't.id', '=', 'f.type')
                ->join($getParam['TBL_FORMAT_HAS_TARGET'].' AS tt', 'tt.id', '=', 'f.target')
                ->join($getParam['TBL_FORMAT_HAS_METHODE'].' AS m', 'm.id', '=', 'f.methode')
                ->select('f.id  AS id', 'f.v_name AS name', 's.name AS status', 'u.name AS created_by', 't.name AS type', 'tt.name AS target', 'm.name AS methode')
                ->get();
        
        return $formats;
    }
    
    public static function getFormatTypes()
    {
        $getParam = self::getParam();
        
        $types = DB::table($getParam['TBL_FORMAT_HAS_TYPE'].' AS t')
                ->select('t.id  AS id', 't.name AS name')
                ->get();
        
        return $types;
        
    }
    
    public static function getFormatTargets()
    {
        $getParam = self::getParam();
        
        $types = DB::table($getParam['TBL_FORMAT_HAS_TARGET'].' AS t')
                ->select('t.id  AS id', 't.name AS name')
                ->get();
        
        return $types;
        
    }
    
    public static function getFormatMethods()
    {
        $getParam = self::getParam();
        
        $types = DB::table($getParam['TBL_FORMAT_HAS_METHODE'].' AS t')
                ->select('t.id  AS id', 't.name AS name')
                ->get();
        
        return $types;
        
    }
    
    public static function insertFormat($data)
    {
        
        $getParam = self::getParam();
        
        DB::table($getParam['TBL_FORMAT'])->insert($data);
        
    }
    
    public static function deleteFormat($id)
    {
        $getParam = self::getParam();
        
        DB::table($getParam['TBL_FORMAT'])->where('id', '=', $id)->delete();
        
    }
    
    public static function getFormatByID($id){
        
        $getParam = self::getParam();
        
        $format = DB::table($getParam['TBL_FORMAT'])->where('id', $id)->first();
        
        return $format;
        
    }
    
    public static function editFormat($data,$id)
    {
        
        $getParam = self::getParam();
        
        DB::table($getParam['TBL_FORMAT'])
            ->where('id', $id)
            ->update($data);
        
    }
    
    
}
