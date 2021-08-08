<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission as BasePermission;

/**
 * App\Models\Permission.
 *
 * @property int                                                         $id
 * @property string                                                      $name
 * @property string                                                      $guard_name
 * @property null|string                                                 $description
 * @property null|\Illuminate\Support\Carbon                             $created_at
 * @property null|\Illuminate\Support\Carbon                             $updated_at
 * @property string                                                      $display_name
 * @property \Illuminate\Database\Eloquent\Collection|Permission[]       $permissions
 * @property null|int                                                    $permissions_count
 * @property \App\Models\Role[]|\Illuminate\Database\Eloquent\Collection $roles
 * @property null|int                                                    $roles_count
 * @property \App\Models\User[]|\Illuminate\Database\Eloquent\Collection $users
 * @property null|int                                                    $users_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission permission($permissions)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission query()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission role($roles, $guard = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereGuardName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Permission whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Permission extends BasePermission
{
    use HasFactory;

    const PERMISSIONS = [
        User::class,
        Role::class,
    ];

    protected $guard_name = '*';

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['display_name'];

    /**
     * Set the Permission's name.
     *
     * @param string $value
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = Str::slug($value);
    }

    /**
     * Get the display name of the Permission.
     *
     * @return string
     */
    public function getDisplayNameAttribute(): string
    {
        return ucwords(str_replace('-', ' ', $this->name));
    }
}
