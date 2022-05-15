<?php

namespace App\Providers;

use App\Helpers\Telegram;
use App\Helpers\Tomtom;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(Telegram::class, function () {
            return new Telegram(new Http(), env('TG_BOT_TOKEN'));
        });

        $this->app->bind(Tomtom::class, function(){
            return new Tomtom(new Http(), env('TOMTOM_API_KEY') );
        });

    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
