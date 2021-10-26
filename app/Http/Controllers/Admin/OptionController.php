<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\RespondsWithHttpStatusController;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Term;
use App\Options\DefaultRole;
use App\Options\TermOption;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Support\Facades\Schema;

class OptionController extends RespondsWithHttpStatusController
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function terms()
    {
        $terms =  defaultOptionNames(TermOption::class);

        return $this->respond(['termOptions' => $terms]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function permissions()
    {
        if (Cache::has('PermissionName')){
            return $this->respond(['permissionOptions' => Cache::get('PermissionName')]);
        }

        $permissions =  QueryBuilder::for(Permission::class)
            ->select(['id','name'])
            ->get();

        Cache::put('PermissionName', $permissions, now()->addDay());

        return $this->respond(['permissionOptions' => Cache::get('PermissionName')]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function roles()
    {
        if (Cache::has('roleName')){
            return $this->respond(['roleOptions' => Cache::get('roleName')]);
        }

        $roles =  QueryBuilder::for(Role::class)
            ->where('name', '!=', DefaultRole::SUPERADMIN)
            ->select(['id','name'])
            ->get();

        Cache::put('roleName', $roles, now()->addDay());

        return $this->respond(['roleOptions' => Cache::get('roleName')]);
    }
}
