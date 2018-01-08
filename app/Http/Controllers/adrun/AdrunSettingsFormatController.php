<?php

namespace App\Http\Controllers\Adrun;

use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Adrun\StoreAnnonceurRequest;

use Adrun\Settings\SettingsController;
use App\adrun_format;

class AdrunSettingsFormatController extends Controller
{
   
    public function index()
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        $formats = adrun_format::getInstance()->getFormats();

        return view('adrun.format.index', compact('formats'));
    }

    public function test() {
        
        
    }
    
    public function create()
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        
        $types    = adrun_format::getInstance()->getFormatTypes();
        $targets  = adrun_format::getInstance()->getFormatTargets();
        $methodes = adrun_format::getInstance()->getFormatMethods();
        
        $tune     = [];
        $method   = [];
        $target   = [];
        
        foreach( $types as $type):
            
            $tune[$type->id] = $type->name;
        
        endforeach;
        
        foreach( $targets as $target_lv):
            
            $target[$target_lv->id] = $target_lv->name;
        
        endforeach;
        
        foreach( $methodes as $methode_lv):
            
            $method[$methode_lv->id] = $methode_lv->name;
        
        endforeach;
        
        $types = $tune;
        unset($types[0]);
        unset($target[0]);
        unset($method[0]);
        
        return view('adrun.format.create', compact('types','target', 'method'));
        
    }
    
        /**
     * Store a newly created User in storage.
     *
     * @param  \App\Http\Requests\StoreUsersRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAnnonceurRequest $request)
    {
        
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        
        $data = request()->except(['_token']);
        
        unset($data['id'],$data['id_adtech'],$data['id_sage']);
        
        adrun_format::getInstance()->insertFormat($data);
        
        return redirect()->route('admin.format.index');
        
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
        
       adrun_format::getInstance()->deleteFormat($id);
        
        
        return redirect()->route('admin.format.index');
    }
    
    public function edit($id)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        
        $types    = adrun_format::getInstance()->getFormatTypes();
        $targets  = adrun_format::getInstance()->getFormatTargets();
        $methodes = adrun_format::getInstance()->getFormatMethods();
        
        $tune     = [];
        $method   = [];
        $target   = [];
        
        foreach( $types as $type):
            
            $tune[$type->id] = $type->name;
        
        endforeach;
        
        foreach( $targets as $target_lv):
            
            $target[$target_lv->id] = $target_lv->name;
        
        endforeach;
        
        foreach( $methodes as $methode_lv):
            
            $method[$methode_lv->id] = $methode_lv->name;
        
        endforeach;
        
        $types = $tune;
        unset($types[0]);
        unset($target[0]);
        unset($method[0]);
        
        $format = adrun_format::getInstance()->getFormatByID($id);
        
        return view('adrun.format.edit', compact('format','types','target', 'method'));
        
    }
    
     /**
     * Update User in storage.
     *
     * @param  \App\Http\Requests\UpdateUsersRequest  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(StoreAnnonceurRequest $request, $id)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        
        $data = request()->except(['_token']);
        
        $data['id_status'] = 0;
        
        unset($data['_method'],$data['id_status']);
        
        $annonceur = adrun_format::getInstance()->editFormat($data,$id);
        
        return redirect()->route('admin.format.index');
    }
   

}