<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\RespondsWithHttpStatusController;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Options\DefaultRole;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\QueryBuilder;

class PermissionController extends RespondsWithHttpStatusController
{
    public function index(Request $request){

        $permissions = QueryBuilder::for(Permission::class)
            ->allowedSorts('name')
            ->allowedFilters('name')
            ->jsonPaginate()
            ->appends($request->query());

        return $this->respond(['permissions' => PermissionResource::collection($permissions)->response()->getData(true)]);
    }

    /**
     * store the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function store(Request $request)
    {
        $this->authorize('create', User::class);

        $request->validate([
            'permission' => ['required', 'unique:permissions,name'],
        ], [
            '*.unique' => 'The provided :attribute already exists.'
        ]);

        $permission = Permission::create([
            'name' => $request->input('permission'),
        ]);

        return $this->respond(['permission' => new PermissionResource($permission)]);
    }

    /**
     * Display the specified resource.
     *
     * @param Permission $permission
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Permission $permission)
    {
        $this->authorize('view', $permission);

        return $this->respond(['permission' => new PermissionResource($permission)]);
    }

    /**
     * store the specified resource.
     *
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Permission $permission)
    {
        $this->authorize('update', Permission::class);

        $request->validate([
            'permission' => ['required', Rule::unique('permissions', 'name')->ignore($permission->id)],
        ], [
            '*.unique' => 'The provided :attribute already exists.'
        ]);

//        $role =  Role::query()
//            ->where('id', $id)
//            ->firstOrFail();

        $permission->update(['name' => $request->input('permission')]);

        return $this->respond(['role' => new RoleResource($permission)]);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Permission $permission
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function users(Request $request, Permission $permission)
    {
        $this->authorize('view', $permission);

        $users = QueryBuilder::for(User::class)
            ->whereRelation('roles.permissions', 'permission_id', '=', $permission->id)
            ->orwhereRelation('permissions', 'permission_id', '=', $permission->id)
            ->allowedSorts('-created_at')
            ->allowedFilters('first_name', 'last_name', 'email')
            ->jsonPaginate()
            ->appends($request->query());

        return $this->respond(['users' => UserResource::collection($users)->response()->getData(true)]);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @param Permission $permission
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function roles(Request $request, Permission $permission): \Illuminate\Http\JsonResponse
    {
        $this->authorize('view', $permission);

        $roles = QueryBuilder::for(Role::class)
            ->whereRelation('permissions', 'permission_id', '=', $permission->id)
            ->where('name', '!=', DefaultRole::SUPERADMIN)
            ->allowedSorts('-created_at')
            ->allowedFilters('name')
            ->jsonPaginate()
            ->appends($request->query());

        return $this->respond(['roles' => RoleResource::collection($roles)->response()->getData(true)]);
    }
}
