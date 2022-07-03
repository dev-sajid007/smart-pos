<?php

namespace App\Providers;

use App\Permission;
use Facades\App\Repositories\PermissionCache;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view) {
            if (Auth::check()) {
                view()->share([
                    'slugs' => PermissionCache::allPermittedSlugs()
                ]);
            }
        });
    }
}
