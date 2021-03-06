<?php

namespace App\Policies;

use App\Enums\RoleEnum;
use App\Models\Term;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class TermPolicy
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
     * @param  \App\Models\Term  $term
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Term $term)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasRole(RoleEnum::ADMIN)
            ? Response::allow()
            : Response::deny();
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Term  $term
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Term $term)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Term  $term
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Term $term)
    {
        if ($user->hasRole(RoleEnum::ADMIN)){
            Response::allow();
        }

        return Response::deny();
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Term  $term
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Term $term)
    {
        if ($term->deleted_at){
            return Response::allow();
        }

        if ($user->hasRole(RoleEnum::ADMIN)){
            Response::allow();
        }

        return Response::deny();
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Term  $term
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Term $term)
    {
        //
    }
}
