<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Validator::extend(
            'alpha_spaces', function ($attribute, $value) {
                // This will only accept alpha and spaces.
                // If you want to accept hyphens use: /^[\pL\s-]+$/u.
                return preg_match('/^[a-zA-Z0-9\\-\\s\/\\\\]+$/u', $value);
            }
        );
        Validator::extend(
            'alpha_spaces_slash', function ($attribute, $value) {
                // This will only accept alpha and spaces.
                // If you want to accept hyphens use: /^[\pL\s-]+$/u.
                return preg_match('/^[a-zA-Z0-9\\-\\s\/\\\\]+$/u', $value);
            }
        );
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
