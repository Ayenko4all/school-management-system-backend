<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\RespondsWithHttpStatusController;
use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;

class RolePermissionsController extends RespondsWithHttpStatusController
{
    /**
     * Add permissions or permission to a role.
     *
     * @param Request $request
     * @param Role $role
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function attach(Request $request, Role $role)
    {
        $this->authorize('update', $role);

        $request->validate([
            'permissions'   => ['required', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $role->givePermissionTo($request->input('permissions'));

        return $this->respond(['permissions' => PermissionResource::collection($role->permissions)]);
    }

    /**
     * Remove permissions or permission from a role
     *
     * @param Request $request
     * @param Role $role
     *
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function detach(Request $request, Role $role)
    {
        $this->authorize('delete', $role);

        $request->validate([
            'permissions'   => ['required', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $permissions = Permission::query()
            ->whereIn('id', $request->input('permissions'))
            ->cursor();

        foreach ($permissions as $permission) {
            /** @var Permission $permission */
            if (! $role->hasPermissionTo($permission)) {
                continue;
            }

            $role->revokePermissionTo($permission);
        }

        return $this->respond(['permissions' => PermissionResource::collection($role->permissions)]);
    }
}
