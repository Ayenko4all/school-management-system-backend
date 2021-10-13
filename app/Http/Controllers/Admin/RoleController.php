<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RoleEnum;
use App\Http\Controllers\RespondsWithHttpStatusController;
use App\Http\Resources\RoleResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Validation\Rule;
use Spatie\QueryBuilder\QueryBuilder;

class RoleController extends RespondsWithHttpStatusController
{
    public function index(Request $request){
        $roles = QueryBuilder::for(Role::class)
            ->withTrashed()
            ->allowedSorts('name')
            ->allowedFilters('name')
            ->jsonPaginate()
            ->appends($request->query());

        return $this->respond(['roles' => RoleResource::collection($roles)->response()->getData(true)]);
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
            'role' => ['required', 'unique:roles,name'],
        ], [
            '*.unique' => 'The provided :attribute already exists.'
        ]);

        $role = Role::create([
            'name' => $request->input('role'),
        ]);

        return $this->respond(['role' => new RoleResource($role->load('permissions'))]);
    }

    /**
     * Display the specified resource.
     *
     * @param Role $role
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Role $role)
    {
        $this->authorize('view', $role);

        return $this->respond(['role' => new RoleResource($role->load('permissions'))]);
    }

    /**
     * store the specified resource.
     *
     * @param Request $request
     * @param Role $role
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, Role $role)
    {
        $this->authorize('update', Role::class);

        $request->validate([
            'role' => ['required', Rule::unique('roles', 'name')->ignore($role->id)],
        ], [
            '*.unique' => 'The provided :attribute already exists.'
        ]);

        $role->update([
            'name' => $request->input('role'),
        ]);

        return $this->respond(['role' => new RoleResource($role->load('permissions'))]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     * @return JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(Role $role)
    {
        $this->authorize('delete', $role);

        $role->delete();

        return $this->responseOk(['message' => 'A Role was deleted successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Role $role
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function restore($id)
    {
        $role =  Role::query()
            ->where('id', $id)
            ->withTrashed()
            ->firstOrFail();

        $this->authorize('restore', $role);

        $role->restore();

        return $this->respond([
            'message' => 'Role was restore successfully',
            'role' => new RoleResource($role->load(['permissions']))
        ]);
    }
}
