<?php

namespace App\Http\Controllers\Adrun;

use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Adrun\StoreCiblageRequest;

use Adrun\Settings\SettingsController;
use App\adrun_ciblage;
use stdClass;
use DB;

class AdrunSettingsCiblageController extends Controller
{
   
    public function index() {
        
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        $ciblages = adrun_ciblage::getInstance()->getCiblages();
        
        return view('adrun.ciblage.index', compact('ciblages'));
    }

    public function create()
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        
        $ciblages = adrun_ciblage::getCiblagesNameOnly();
        
        
        $tune   = [];
        
        foreach( $ciblages as $ciblage):
            
            $tune[$ciblage->id] = $ciblage->name;
        
        endforeach;
        
        unset($tune[0]);
        
        $combinations = new stdClass();
        $combinations->type = $tune; 
        
        
        return view('adrun.ciblage.create', compact('combinations'));
    }
    
        /**
     * Store a newly created User in storage.
     *
     * @param  \App\Http\Requests\StoreUsersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCiblageRequest $request)
    {
        
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        
        $data = request()->except(['_token']);
        
        unset($data['id'],$data['id_adtech'],$data['id_sage']);
        
        $data['status'] = 1;
        
        if( !$request->input('combination') ){ 
            
            $id_e = DB::table('adrun_ciblage')->insertGetId($data);
            
            $add = [];
            $add['id_ciblage_master'] = (int) $id_e;
            $add['id_ciblage_slave']  = (int) $id_e;
            
            DB::table('adrun_ciblage_has_combination')->insertGetId($add);
            
        } else {
            
            $add = [];
            $add['name']   = $data['name'];
            $add['status'] = $data['status'];
            
            $id_e = DB::table('adrun_ciblage')->insertGetId($add);
           
            foreach($data['combination'] as $combination):

                $add2 = [];
                $add2['id_ciblage_master'] = (int) $id_e;
                $add2['id_ciblage_slave']  = (int) $combination;

                DB::table('adrun_ciblage_has_combination')->insertGetId($add2);

            endforeach;
            
            
            
        }
        
        return redirect()->route('admin.ciblage.index');
        
    }
    
            /**
     * Remove User from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        
        DB::table('adrun_ciblage')->where('id', '=', $id)->delete();
        
        $ciblages = DB::table('adrun_ciblage_has_combination')
                    ->where('id_ciblage_master', '=', $id)
                    ->get();
        
        foreach($ciblages as $ciblage):
            
            DB::table('adrun_ciblage_has_combination')->where('id', '=', $ciblage->id)->delete();
            
        endforeach;
        
        return redirect()->route('admin.ciblage.index');
    }
    

    
    public function edit($id)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        
        
        $ciblagex = DB::table('adrun_ciblage_has_combination AS cc')
                    ->join('adrun_ciblage AS c', 'cc.id_ciblage_slave', '=', 'c.id')
                    ->where('cc.id_ciblage_master', '=', $id)
                    ->select('c.name AS name','c.id AS id')
                    ->get();

        $ciblage_details = DB::table('adrun_ciblage')
                    ->where('id', '=', $id)
                    ->first();
        
        $ciblages = adrun_ciblage::getCiblagesNameOnly();
        
        $tune   = [];
        
        foreach( $ciblages as $ciblage):
            
            $tune[$ciblage->name] = $ciblage->name;
        
        endforeach;
        
        unset($tune['All']);
        
        $tunex   = [];
        
        foreach( $ciblagex as $ciblage):
            
            $tunex[$ciblage->name] = $ciblage->name;
        
        endforeach;
        
        $ciblages = new stdClass();
        $ciblages->type = $tune; 
        
        $combinations = $tunex;
        $ciblage = $ciblage_details;
        
        return view('adrun.ciblage.edit', compact('ciblage','ciblages','combinations'));
        
    }
    
        /**
     * Update User in storage.
     *
     * @param  \App\Http\Requests\UpdateUsersRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreEditeurRequest $request, $id)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        
        $data = request()->except(['_token']);
        
        $data['id_status'] = 0;
        
        unset($data['_method']);
        
//        $ciblages = adrun_editeur::getEditeurCiblagesByID($id);
//        
//        foreach ($ciblages as $ciblage):
//            
//            adrun_editeur::deleteEditeurCiblagesByID($ciblage->id);
//            
//        endforeach;
//        
//        foreach ($data['ciblage'] as $ciblage) :
//            
//            $id_c = adrun_ciblage::getCiblagesByName($ciblage);
//        
//            DB::table('adrun_editeur_has_ciblage')->insert( ['id_editeur' => $id, 'id_ciblage' => $id_c] );
//            
//        endforeach;
//        
//        //Need Attention
//        unset($data['ciblage'],$data['id_status'],$data['id'],$data['status']);
//        
//        adrun_editeur::editEditeur($data,$id);
        
        return redirect()->route('admin.editeur.index');
    }
   

}
