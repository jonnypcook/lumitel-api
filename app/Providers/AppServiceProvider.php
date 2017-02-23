<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
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
        // extend validator to include numericArray
        Validator::extend('numericArray', function($attribute, $value, $parameters)
        {
            if (!is_array($value)) {
                return false;
            }

            return count($value) === count(preg_grep('/^[\d]+$/', $value));
        });


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
