<?php

namespace App\Http\Controllers\Adrun;

use App\User;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Adrun\StoreAnnonceurRequest;
use App\Http\Requests\Admin\UpdateUsersRequest;
use DB;
use stdClass;

use Adrun\Settings\SettingsController;
use App\adrun_annonceur;


class AdrunSettingsAnnonceurController extends Controller
{
   
    public function index()
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        
        $annonceurs = adrun_annonceur::getInstance()->getAnnonceurs();
        
        return view('adrun.settings-annonceur', compact('annonceurs'));
    }

    public function create()
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        
        $status = new stdClass();
        $status->status = [ '0' => 'active', '1' => 'deactive'];

        return view('adrun.annonceur.create', compact('status'));
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
        
        $data['id_status'] = 0;
        
        unset($data['id'],$data['id_adtech'],$data['id_sage']);
        
        DB::table('adrun_annonceur')->insert($data);
        
        return redirect()->route('admin.annonceur.index');
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
        
        $annonceurs = adrun_annonceur::getInstance()->deleteAnnonceur($id);
        
        
        return redirect()->route('admin.annonceur.index');
    }
    
    public function edit($id)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        $roles = Role::get()->pluck('name', 'name');

        $annonceur = adrun_annonceur::getAnnonceurByID($id);
        
        return view('adrun.annonceur.edit', compact('annonceur', 'roles'));
        
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
        
        unset($data['_method']);
        
        $annonceur = adrun_annonceur::editAnnonceur($data,$id);
        
        return redirect()->route('admin.annonceur.index');
    }
    
    
    
    /**
     * Delete all selected User at once.
     *
     * @param Request $request
     */
    public function massDestroy(Request $request)
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }
        if ($request->input('ids')) {
            $entries = User::whereIn('id', $request->input('ids'))->get();

            foreach ($entries as $entry) {
                $entry->delete();
            }
        }
    }

}

