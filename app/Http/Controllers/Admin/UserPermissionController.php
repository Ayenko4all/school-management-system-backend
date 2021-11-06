<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\UserPermissionRequest;
use App\Http\Resources\PermissionResource;
use App\Models\Permission;
use App\Models\User;
use App\Rules\UserPermissionRule;
use Illuminate\Http\Request;
use App\Http\Controllers\RespondsWithHttpStatusController;
use Illuminate\Validation\Rule;

class UserPermissionController extends RespondsWithHttpStatusController
{
    /**
     * Attach the direct permission to a user.
     *
     * @param UserPermissionRequest $request
     * @param User $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function attach(UserPermissionRequest $request, User $user)
    {
        $user->givePermissionTo($request->input('permissions'));

        return $this->respond(['permissions' => PermissionResource::collection($user->permissions)]);
    }

    /**
     * Remove direct permission from a user.
     *
     * @param UserPermissionRequest $request
     * @param User $user
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function detach(UserPermissionRequest $request, User $user)
    {
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
