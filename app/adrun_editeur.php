<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use stdClass;

use Adrun\Settings\SettingsController;

class adrun_editeur extends Model
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

    public static function getEditeurs()
    {
        $getParam = self::getParam();
        
        $annonceurs = DB::table($getParam['TBL_EDITEUR'].' AS a')
            ->join($getParam['TBL_ANNONCEUR_STU'].' AS s', 'a.status', '=', 's.id')
            ->join($getParam['TBL_USER'].' AS u', 'u.id', '=', 'a.created_by')
            ->select('a.id  AS id', 'a.target AS target', 'a.impression AS impression', 'a.editeur_percentage AS editeur', 'a.regie_percentage AS regie', 'a.company_name AS company', 'a.name AS name', 'a.date_created AS created_date', 's.name AS status', 'u.name AS created_by')
            ->get();
       
        return $annonceurs;
    }
    
    public static function getEditeurCiblagesByID($id)
    {
        $getParam = self::getParam();
        
        $ciblages = DB::table($getParam['TBL_EDITEUR_HAS_CIBLAGE'].' AS ec')
                ->join('adrun_ciblage AS c', 'ec.id_ciblage', '=', 'c.id')
                    ->where('id_editeur', '=', $id)
                    ->select('c.name AS name', 'ec.id AS id')
                    ->get();
        
        return $ciblages;
        
    }
    
    public static function deleteEditeurCiblagesByID($id)
    {
        $getParam = self::getParam();
        
        DB::table($getParam['TBL_EDITEUR_HAS_CIBLAGE'])->where('id', '=', $id)->delete();
        
    }
    
    public static function editEditeur($data,$id)
    {
        
        $getParam = self::getParam();
        
        DB::table($getParam['TBL_EDITEUR'])
            ->where('id', $id)
            ->update($data);
        
    }
    
}
