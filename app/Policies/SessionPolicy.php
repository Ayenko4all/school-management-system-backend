<?php

namespace App\Policies;

use App\Enums\RoleEnum;
use App\Models\Session;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class SessionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Session  $session
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Session $session)
    {
        return  $user->hasRole(RoleEnum::ADMIN)
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return  $user->hasRole(RoleEnum::ADMIN)
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Session  $session
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Session $session)
    {
        return  $user->hasRole(RoleEnum::ADMIN)
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Session  $session
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Session $session)
    {
        if ($user->hasRole(RoleEnum::ADMIN)){
            return Response::allow();
        }

        return Response::deny();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Session  $session
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Session $session)
    {
        if ($user->hasRole(RoleEnum::ADMIN)){
            return Response::allow();
        }

        return Response::deny();

    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Session  $session
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Session $session)
    {
        return  $user->hasRole(RoleEnum::ADMIN)
            ? Response::allow()
            : Response::deny();
    }
}
