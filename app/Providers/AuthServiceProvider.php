<?php

namespace App\Providers;

use App\Models\PersonalAccessToken;
use App\Models\Role;
use App\Models\Session;
use App\Models\Teacher;
use App\Models\Term;
use App\Models\User;
use App\Policies\RolePolicy;
use App\Policies\SessionPolicy;
use App\Policies\TeacherPolicy;
use App\Policies\TermPolicy;
use App\Policies\UserPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use Laravel\Sanctum\Sanctum;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Model' => 'App\Policies\ModelPolicy',
        Session::class => SessionPolicy::class,
        Term::class => TermPolicy::class,
        Teacher::class => TeacherPolicy::class,
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Sanctum::usePersonalAccessTokenModel(PersonalAccessToken::class);

//        if (! $this->app->routesAreCached()) {
//            Passport::routes();
//        }

    }
}
