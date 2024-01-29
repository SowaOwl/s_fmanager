<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Guard\JwtGuard;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        //
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Auth::extend('jwt', function ($app) {
            $guard = new JwtGuard($app['tymon.jwt'], $app['request']);
            $app->refresh('request', $guard, 'setRequest');
            return $guard;
        });
    }
}
