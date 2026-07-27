<?php

namespace App\Providers;

use App\Models\Tasks;
use App\Policies\TaskPolicy;
use Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */

    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        Gate::policy(Tasks::class,TaskPolicy::class);


        RateLimiter::for('login',function (Request $request){
            return Limit::perMinute(5)->by($request->ip());
        });

        RateLimiter::for('api',function (Request $request){
            return Limit::perMinute(60)->by(
                $request->user()?->id ?: $request->ip()
            );
        });

    }
}
