<?php

namespace App\Providers;

use App\Models\League;
use App\Models\User;
use App\Models\VideoProgress;
use App\Policies\LeaguesPolicy;
use App\Policies\UserPolicy;
use App\Policies\VideoProgressPolicy;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        League::class => LeaguesPolicy::class,
        VideoProgress::class => VideoProgressPolicy::class,
        User::class => UserPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        ResetPassword::createUrlUsing(function ($user, string $token) {
            $email = $user->email;
            return config('app.url') . "/password-reset/email/$email/token/$token/";
        });

        if (! $this->app->routesAreCached()) {
            Passport::routes(function ($router) {
                $router->forAccessTokens();
            });
        }
    }
}
