<?php

namespace App\Policies;

use App\Enums\RoleEnum;
use App\Models\School;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;

class SchoolPolicy
{
    use HandlesAuthorization;

    /**
     * Perform pre-authorization checks.
     *
     * @param  \App\Models\User  $user
     * @param  string  $ability
     * @return void|bool
     */
    public function before(User $user, $ability)
    {
        if ($user->hasRole(RoleEnum::SITEADMIN)) {
            return true;
        }
    }

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return Response
     */
    public function viewAny(User $user)
    {

    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\School  $school
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, School $school)
    {
        return $user->id === $school->user_id
            ? Response::allow()
            : Response::deny('You do not own this school.');

    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return $user->hasRole(RoleEnum::SCHOOLOWNER)
            ? Response::allow()
            : Response::deny('You don not have authority to perform this action.');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\School  $school
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, School $school)
    {
        return $user->id === $school->user_id
            ? Response::allow()
            : Response::deny('You do not own this school.');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\School  $school
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, School $school)
    {
        return $user->hasRole(RoleEnum::SITEADMIN)
            ? Response::allow()
            : Response::deny('You don not have authority to perform this action.');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\School  $school
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, School $school)
    {
        return $user->hasRole(RoleEnum::SITEADMIN)
            ? Response::allow()
            : Response::deny('You don not have authority to perform this action.');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\School  $school
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, School $school)
    {
        return $user->hasRole(RoleEnum::SITEADMIN)
            ? Response::allow()
            : Response::deny('You don not have authority to perform this action.');
    }
}
