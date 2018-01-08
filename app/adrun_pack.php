<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use stdClass;

use Adrun\Settings\SettingsController;

class adrun_pack extends Model
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

    public static function getPacks()
    {
        $getParam = self::getParam();
        
        $packs = DB::table($getParam['TBL_PACK'].' AS a')
            ->join($getParam['TBL_ANNONCEUR_STU'].' AS s', 'a.status', '=', 's.id')
            ->join($getParam['TBL_USER'].' AS u', 'u.id', '=', 'a.created_by')
            ->leftjoin($getParam['TBL_PACK_HAS_WEBSITE'].' AS w', 'a.id', '=', 'w.id_pack')
            ->leftjoin($getParam['TBL_EDITEUR'].' AS e', 'e.id', '=', 'w.id_editeur')
            ->select(DB::raw('count(w.id) as count_website,a.id  AS id, a.name AS name, a.date_created AS created_date, s.name AS status, u.name AS created_by'))
            ->groupBy('a.id')
            ->get();
        
        $packsx = [];
        
        foreach($packs as $pack):
            
            $packsx[$pack->name]['datas'][] = $pack ;
            $packsx[$pack->name]['id']      = $pack->id;
            $packsx[$pack->name]['status']  = $pack->status;
            $packsx[$pack->name]['name']    = $pack->name;
            $packsx[$pack->name]['count']   = $pack->count_website;
            
        endforeach;
        
        return $packsx;
    }
}
