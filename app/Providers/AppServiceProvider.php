<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

use App\Models\User;
use App\Policies\UserPolicy;
use App\Models\Task;
use App\Policies\TaskPolicy;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(Task::class, TaskPolicy::class);

        Paginator::useBootstrapFive();

        /*
        |--------------------------------------------------------------------------
        | Web Login
        |--------------------------------------------------------------------------
        |
        | 5 attempts per minute for the same email + IP.
        |
        */
        RateLimiter::for('login', function (Request $request) {

            $email = strtolower(
                (string) $request->input('email')
            );

            return Limit::perMinute(5)
                ->by($email . '|' . $request->ip());
        });


        /*
        |--------------------------------------------------------------------------
        | API Login
        |--------------------------------------------------------------------------
        |
        | Slightly stricter because this endpoint can be attacked
        | programmatically.
        |
        */
        RateLimiter::for('api-login', function (Request $request) {

            $email = strtolower(
                (string) $request->input('email')
            );

            return Limit::perMinute(5)
                ->by($email . '|' . $request->ip());
        });


        /*
        |--------------------------------------------------------------------------
        | General API
        |--------------------------------------------------------------------------
        |
        | Per authenticated user, with IP fallback for guests.
        |
        */
        RateLimiter::for('api', function (Request $request) {

            return Limit::perMinute(60)
                ->by(
                    $request->user()?->id
                    ?? $request->ip()
                );
        });

       
    }
}