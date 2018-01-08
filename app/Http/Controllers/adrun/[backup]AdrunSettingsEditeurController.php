<?php

namespace App\Http\Controllers\Adrun;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUsersRequest;
use App\Http\Requests\Admin\UpdateUsersRequest;

use Adrun\Settings\SettingsController;
use App\adrun_editeur;

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

    public function test() {
        
        
    }
   

}
