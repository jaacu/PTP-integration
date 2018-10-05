<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use App\SoapPTP;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * Register the Soap Helper class
         */
        $this->app->singleton('soap' , function(){
            return new SoapPTP();
        });
    }
}
