<?php

namespace App\Providers;

use App\Services\Telegram\TelegramApiClient;
use App\Services\Tomtom\TomtomApiClient;
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
        $this->app->bind(TelegramApiClient::class, function () {
            return new TelegramApiClient(new Http(), env('TG_BOT_TOKEN'));
        });

        $this->app->bind(TomtomApiClient::class, function(){
            return new TelegramApiClient(new Http(), env('TOMTOM_API_KEY'));
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        //
    }
}
