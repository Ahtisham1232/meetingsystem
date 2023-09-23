<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator;

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
    public function boot()
    {
        Validator::extend('different_emails', function ($attribute, $value, $parameters, $validator) {
            $otherField = $parameters[0];

            $otherValue = $validator->getData()[$otherField];

            return $value !== $otherValue;
        });
    }
}
