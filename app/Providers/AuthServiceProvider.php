<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;
use App\Peternak;
use Auth;
use Redirect;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Passport::routes();

        Gate::define('isAdmin', function($user) {
            return $user->role == 'admin';
        });

        Gate::define('isPeternak', function($user) {
            return $user->role == 'peternak';
        });

        Gate::define('isKetua', function($user) {
            return $user->role == 'ketua grup';
        });

    }
}
