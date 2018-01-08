<?php

namespace App\Http\Controllers\adrun;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;


use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use App\Http\Requests\Adrun\StoreEditeurRequest;

use Adrun\Settings\SettingsController;
use App\adrun_editeur;
use App\adrun_ciblage;

use stdClass;
use DB;

class AdrunSettingsEditeurController extends Controller
{
    public function index()
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        $editeurs = adrun_editeur::getInstance()->getEditeurs();

        return view('adrun.editeur.index', compact('editeurs'));
    }

    public function create()
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        
        $ciblages = adrun_ciblage::getCiblagesNameOnly();
        
        
        $tune   = [];
        
        foreach( $ciblages as $ciblage):
            
            $tune[$ciblage->name] = $ciblage->name;
        
        endforeach;
        
        unset($tune['All']);
        
        $ciblages = new stdClass();
        $ciblages->type = $tune; 
        
        
        return view('adrun.editeur.create', compact('ciblages'));
    }
    
        /**
     * Store a newly created User in storage.
     *
     * @param  \App\Http\Requests\StoreUsersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreEditeurRequest $request)
    {
        
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        
        $data = request()->except(['ciblage']);
        
        unset($data['_token'],$data['id'],$data['id_adtech'],$data['id_sage']);
        
        $data['status'] = 0;
        
        $id_e = DB::table('adrun_editeur')->insertGetId($data);
        
        $ciblages = $request->input('ciblage') ? $request->input('ciblage') : [];
        
        
        foreach ($ciblages as $ciblage) :
            
            $id = adrun_ciblage::getCiblagesByName($ciblage);
        
            DB::table('adrun_editeur_has_ciblage')->insert( ['id_editeur' => $id_e, 'id_ciblage' => $id] );
            
        endforeach;
        
        return redirect()->route('admin.editeur.index');
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
        
        DB::table('adrun_editeur')->where('id', '=', $id)->delete();
        
        $editeurs = DB::table('adrun_editeur_has_ciblage')
                    ->where('id_editeur', '=', $id)
                    ->get();
        
        foreach($editeurs as $editeur):
            
            DB::table('adrun_editeur_has_ciblage')->where('id', '=', $editeur->id)->delete();
            
            
        endforeach;
        
        return redirect()->route('admin.editeur.index');
    }
    
    public function edit($id)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        
        
        $ciblagex = DB::table('adrun_editeur_has_ciblage AS ec')
                ->join('adrun_ciblage AS c', 'ec.id_ciblage', '=', 'c.id')
                    ->where('id_editeur', '=', $id)
                    ->select('c.name AS name')
                    ->get();

        $editeur = DB::table('adrun_editeur')
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
        
        $edit_ciblages = $tunex;
        
        
        return view('adrun.editeur.edit', compact('editeur','ciblages','edit_ciblages'));
        
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
        
        $ciblages = adrun_editeur::getEditeurCiblagesByID($id);
        
        foreach ($ciblages as $ciblage):
            
            adrun_editeur::deleteEditeurCiblagesByID($ciblage->id);
            
        endforeach;
        
        foreach ($data['ciblage'] as $ciblage) :
            
            $id_c = adrun_ciblage::getCiblagesByName($ciblage);
        
            DB::table('adrun_editeur_has_ciblage')->insert( ['id_editeur' => $id, 'id_ciblage' => $id_c] );
            
        endforeach;
        
        //Need Attention
        unset($data['ciblage'],$data['id_status'],$data['id'],$data['status']);
        
        adrun_editeur::editEditeur($data,$id);
        
        return redirect()->route('admin.editeur.index');
    }
    
    
    
}
