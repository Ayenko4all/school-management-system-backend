<?php

namespace App\Models;
use App\Enums\RoleEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Permission\Models\Role as BaseRole;

/**
 * App\Models\Role.
 *
 * @property int                                                               $id
 * @property string                                                            $name
 * @property string                                                            $guard_name
 * @property null|string                                                       $description
 * @property null|\Illuminate\Support\Carbon                                   $created_at
 * @property null|\Illuminate\Support\Carbon                                   $updated_at
 * @property bool                                                              $can_be_renamed
 * @property \App\Models\Permission[]|\Illuminate\Database\Eloquent\Collection $permissions
 * @property null|int                                                          $permissions_count
 * @property \App\Models\User[]|\Illuminate\Database\Eloquent\Collection       $users
 * @property null|int                                                          $users_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Role newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Role permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|Role query()
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Role whereUpdatedAt($value)
 * @mixin \Eloquent
 */

class Role extends BaseRole
{

    use HasFactory;

    protected $guard_name = '*';


    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['can_be_renamed'];

    /**
     * Set the role's name.
     *
     * @param string $value
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = isset($value) ? mb_strtolower($value) : null;
    }

    /**
     * Check whether name of the role can be changed.
     *
     * @return bool
     */
    public function getCanBeRenamedAttribute()
    {
        return in_array($this->name, RoleEnum::values()) ? false : true;
    }


}
