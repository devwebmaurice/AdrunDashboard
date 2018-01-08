<?php

namespace App\Http\Controllers\Adrun;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRolesRequest;
use App\Http\Requests\Admin\UpdateRolesRequest;

class AvailabilityController extends Controller
{
   
    public function index()
    {
        if (! Gate::allows('users_manage')) {
            return abort(401);
        }

        $roles = Role::all();

        return view('adrun.index', compact('roles'));
    }

    public function test() {
        
        
    }
   

}
