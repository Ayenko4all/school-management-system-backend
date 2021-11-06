<?php

namespace App\Models;

use App\Enums\RoleEnum;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


/**
 * App\Models\User.
 *
 * @property null|string                                                                                               $password
 * @property null|string                                                                                               $first_name
 * @property null|string                                                                                               $last_name
 * @property null|string                                                                                               $email
 * @property null|string                                                                                               $telephone
 * @property null|string                                                                                               $gender
 * @property null|\Illuminate\Support\Carbon                                                                           $email_verified_at
 * @property null|\Illuminate\Support\Carbon                                                                           $telephone_verified_at
 * @property null|\Illuminate\Support\Carbon                                                                           $created_at
 * @property null|\Illuminate\Support\Carbon                                                                           $updated_at
 * @property null|\Illuminate\Support\Carbon                                                                           $deleted_at
 * @property \App\Models\Permission[]|Collection                                                                       $permissions
 * @property \App\Models\Role[]|Collection                                                                             $roles
 *
 *@mixin \Eloquent
 */

class User extends Authenticatable
{
    use HasFactory,HasRoles, Notifiable, HasApiTokens, SoftDeletes;

    public $guard_name = '*';

    protected $guarded = [];

    protected $dates = ['deleted_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
        'telephone_verified_at' => 'datetime'
    ];

    public function assignRoleToUser($roles)
    {
        return $this->assignRole($roles);
    }

    public static function authAccessToken($param){
        return self::where('email', $param)->first()->createToken(config('auth.token.name'));
    }

    public function teacher(){
        return $this->hasOne(Teacher::class, 'id', 'teacher_id');
    }

    public function expiration($token){
        $expiration = $this->tokens()->where('id', $token)->select(['expires_in'])->first();
        return $expiration->expires_in;
    }



}
