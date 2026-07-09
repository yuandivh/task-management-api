<?php

namespace App\Providers;

use App\Models\Tasks;
use App\Policies\TaskPolicy;
use Gate;
use Illuminate\Support\ServiceProvider;

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
    }
}
