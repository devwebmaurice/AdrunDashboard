<?php

namespace App\Http\Controllers\Adrun;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUsersRequest;
use App\Http\Requests\Admin\UpdateUsersRequest;

use Adrun\Settings\SettingsController;
use App\adrun_pack;
use App\adrun_editeur;

class AdrunSettingsPackController extends Controller
{
   
    public function index()
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        $packs     = adrun_pack::getInstance()->getPacks();
        $websitexs = adrun_editeur::getInstance()->getEditeurs();
        $websites  = [];
        
        foreach( $websitexs as $websitex):
            
            $websites[$websitex->id] = $websitex->name;
        
        endforeach;
        
        return view('adrun.pack.index', compact('packs','websites'));
    }


   

}