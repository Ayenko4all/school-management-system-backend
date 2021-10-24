<?php

namespace App\Http\Controllers\Admin;

use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\RespondsWithHttpStatusController;

class UserPermissionController extends RespondsWithHttpStatusController
{
    /**
     * Attach the direct permission to a user.
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
            'permissions'   => ['required', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $user->givePermissionTo($request->input('permissions'));

        return $this->respond(['permissions' => PermissionResource::collection($user->permissions)]);
    }

    /**
     * Remove direct permission from a user.
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
            'permissions'   => ['required', 'array'],
            'permissions.*' => ['exists:permissions,id'],
        ]);

        $permissions = Permission::query()
            ->whereIn('id', $request->input('permissions'))
            ->cursor();

        /** @var Permission $permission */
        foreach ($permissions as $permission) {
            if (! $user->hasPermissionTo($permission)) {
                continue;
            }

            $user->revokePermissionTo($permission);
        }

        return $this->respond(['permissions' => PermissionResource::collection($user->permissions)]);
    }
}
