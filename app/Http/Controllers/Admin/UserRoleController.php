<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\RespondsWithHttpStatusController;
use App\Http\Resources\PermissionResource;
use App\Http\Resources\RoleResource;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class UserRoleController extends RespondsWithHttpStatusController
{
    /**
     * Attach the role to a user.
     *
     * @param \Illuminate\Http\Request $request
     * @param User                     $user
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function attach(Request $request, User $user)
    {
        $request->validate([
            'roles'   => ['required', 'array'],
            'roles.*' => ['exists:roles,name'],
        ]);

        $user->assignRole($request->input('roles'));

        return $this->respond(['permissions' => RoleResource::collection($user->roles)]);
    }

    /**
     * Remove role from a user
     *
     * @param \Illuminate\Http\Request $request
     * @param User                     $user
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detach(Request $request, User $user)
    {
        $request->validate([
            'roles'   => ['required', 'array'],
            'roles.*' => ['exists:roles,name'],
        ]);

        $roles = Role::query()
            ->whereIn('name', $request->input('roles'))
            ->cursor();

        /** @var Role $role */
        foreach ($roles as $role) {
            if (! $user->hasRole($role)) {
                continue;
            }

            $user->removeRole($role);
        }

        return $this->respond(['roles' => RoleResource::collection($user->roles)]);
    }
}
