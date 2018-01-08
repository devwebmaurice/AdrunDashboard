<?php

namespace App\Models\Adrun;

use Illuminate\Database\Eloquent\Model;

use DB;
use stdClass;

class AdrunCustomerModel extends Model
{
    private static $_instance;
    
    public function __construct()
    {
        
        $this->tbl_campaign   = \Config::get('adrun.table.campaign');
        $this->tbl_advertiser = \Config::get('adrun.table.advertiser');
        
    }
    
    public static function getInstance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self;
        }
        return self::$_instance;
    }
    
    public function getADTECHAdvertiserIDList() 
    {
        
        $campaigns = DB::table($this->tbl_advertiser . ' AS c')
            ->select('c.id AS id')
            ->orderBy('c.id', 'desc')
            ->get();
        
        return $campaigns;
        
    }
    
    public function checkAdvertiserADRUN($id)
    {
        $campaign = DB::table($this->tbl_advertiser)->where('id_adtech', '=', $id)->first();
        
        if(is_null($campaign)):
            
            return FALSE;
        
        endif;
        
        return TRUE;
    }
    
    public function addAdvertiserToADRUN($data)
    {
        $data->assignedUsers = serialize($data->assignedUsers);
        $data->companyData   = serialize($data->companyData);
        $data->name = str_replace("'"," ",$data->name);
        $data->name = htmlentities($data->name, ENT_QUOTES | ENT_IGNORE, "UTF-8");
        
        $id = DB::table($this->tbl_advertiser)->insertGetId(
            [   'id_adtech'        => $data->id, 
                'name'             => $data->name,
                'archiveDate'      => $data->archiveDate,
                'archiveStatus'    => $data->archiveStatus,
                'assignedUsers'    => $data->assignedUsers,
                'companyData'      => $data->companyData,
                'deleted'          => $data->deleted,
                'description'      => $data->description,
                'miscCategoryId'   => $data->miscCategoryId,
                'statusId'         => $data->statusId,
                'subNetwork'       => $data->subNetwork,
            ]
            );
            
            return $id;
        
    }
    
}
