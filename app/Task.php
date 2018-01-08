<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Adrun\Settings\SettingsController;
use Illuminate\Support\Facades\Gate;
use DB;
use stdClass;

class Task extends Model
{
    public static function getParam () {
        
        return SettingsController::getAdrunParams();
    }
    
    public static function find($id) {
        
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        
        $getParam = self::getParam();
        
        $websites = DB::table($getParam['TBL_PACK'].' AS p')
            ->join($getParam['TBL_ANNONCEUR_STU'].' AS s', 'p.status', '=', 's.id')
            ->join($getParam['TBL_USER'].' AS u', 'u.id', '=', 'p.created_by')
            ->leftjoin($getParam['TBL_PACK_HAS_WEBSITE'].' AS pw', 'pw.id_pack', '=', 'p.id')
            ->leftjoin($getParam['TBL_EDITEUR'].' AS e', 'e.id', '=', 'pw.id_editeur')
            ->where('p.id', '=', $id)
            ->select('p.id  AS id', 'pw.id  AS pwid', 'e.id  AS eid','p.name AS name', 'p.date_created AS created_date', 's.name AS status', 'u.name AS created_by', 'e.name AS editeur_name', 'e.url AS website')
            ->get();
        
        $contents = self::getAjaxWebsite($websites);
        
        
      return $contents;  
    }
    
    public static function getAjaxWebsite($contents) {
        
        $html = [];
        $html['name'] = $contents[0]->name;
        
        if(is_null($contents[0]->editeur_name)):
            
            $structure = '<div class="alert alert-warning">';
            $structure .= '<strong>Warning!</strong> No Website Connected';
            $structure .= '</div>';
            
        else:
            
            $structure = '<table class="table table-bordered table-striped datatable  dt-select">';
            $structure .= '<thead>';
            $structure .= '<tr>';
            $structure .= '<th style="text-align:center;"><input type="checkbox" id="select-all" /></th>';
         
            $structure .= '<th>Name</th>';
            $structure .= '<th>URL</th>';
            $structure .= '<th>Impression</th>';
     
            $structure .= '</tr>';
            $structure .= '</thead>';
            
            foreach ($contents as $content):
               
                $structure .= '<tr data-entry-id="'.$content->id.'">';
                $structure .= '<td style="text-align:center;">
                                 <button class="btn btn-xs btn-danger delete-website"  value="'.$content->pwid.'">
                                     <i class="fa fa-trash-o" aria-hidden="true"></i>
                                 </button>
                                </td>';
            
                $structure .= '<td>'.$content->editeur_name.'</td>';
                $structure .= '<td>'.$content->website.'</td>';
                $structure .= '<td>0</td>';
                $structure .= '</tr>';
                
                
            endforeach;
                
            $structure .= '</table>';
            

        endif;
        
        $html['content'] = $structure;
        
        
        return $html;
    }
    
    public static function addWebsiteToPack($id) {
        
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        
        $getParam = self::getParam();
        
        $name = DB::table($getParam['TBL_PACK'])->where('id', $id)->pluck('name');
        
        $websites = DB::table($getParam['TBL_PACK'].' AS p')
            ->join($getParam['TBL_PACK_HAS_WEBSITE'].' AS pw', 'pw.id_pack', '=', 'p.id')
            ->rightjoin($getParam['TBL_EDITEUR'].' AS e', 'e.id', '=', 'pw.id_editeur')
            ->select('p.id  AS id','e.id  AS eid', 'pw.id AS pwid', 'p.name AS name', 'p.date_created AS created_date','e.name AS editeur_name', 'e.url AS website','pw.id_pack AS status')
            ->get();
        
        $contents = self::getAjaxListWebsite($websites,$id,$name);
        
      return $contents;
        
        
    }
    
    public static function getAjaxListWebsite($contents,$id,$name) {
        
        $html = [];
        $html['name'] = $name;
        
        if(is_null($contents[0]->editeur_name)):
            
            $structure = '<div class="alert alert-warning">';
            $structure .= '<strong>Warning!</strong> No Website Connected';
            $structure .= '</div>';
            
        else:
            
            $structure = '<table class="table table-bordered table-striped datatable  dt-select">';
            $structure .= '<thead>';
            $structure .= '<tr>';
            $structure .= '<th style="text-align:center;"><input type="checkbox" id="select-all" /></th>';
         
            $structure .= '<th>Name</th>';
            $structure .= '<th>URL</th>';
            $structure .= '<th>Impression</th>';
     
            $structure .= '</tr>';
            $structure .= '</thead>';
            
            $group = [];
            
            foreach ($contents as $content):
               
                $group[$content->editeur_name][] = (OBJECT) array( 'id' => $content->id,'eid' => $content->eid,'pwid' => $content->pwid,'editeur' => $content->editeur_name, 'website' => $content->website, 'status' => $content->status);
                
            endforeach;
            
            foreach($group as $key =>  $values):
                
                $structure .= '<tr data-entry-id="'.$values[0]->id.'">';
            
                $kev = true;
                
                foreach ( $values as $value ):
                
                    if( (int) $id === (int) $value->status):
                        
                        $kev = false;
                        
                        $super_id = $value->pwid;
                        
                    endif;
                
                
                endforeach;
                
                
                if( $kev === false ):
                  
                        $structure .= '<td style="text-align:center;">
                                 <button class="btn btn-xs btn-danger delete-website" id="btn'.$super_id.'" value="'.$super_id.'">
                                     <i class="fa fa-trash-o" aria-hidden="true"></i>
                                 </button>
                                </td>';
                              
                    else:
                        
                        $structure .= '<td style="text-align:center;">
                                 <button class="btn btn-xs btn-success  add-website" value="'.$values[0]->id.'-'.$values[0]->eid.'">
                                     <i class="fa fa-plus-square" aria-hidden="true"></i>
                                     
                                 </button>
                                </td>';
                    
                    endif;

                $structure .= '<td>'.$values[0]->editeur.'</td>';
                $structure .= '<td>'.$values[0]->website.'</td>';
                $structure .= '<td>0</td>';
                $structure .= '</tr>';
                
            endforeach;
            
            $structure .= '</table>';

        endif;
        
        $html['content'] = $structure;
        
        return $html;
    }
    
    public static function deleteWebsiteToPack($id) {
        
        $getParam = self::getParam();
        
        $resp = DB::table($getParam['TBL_PACK_HAS_WEBSITE'])->where('id', '=', $id)->delete();
        
        return $resp;
    }
    
    
    
}
