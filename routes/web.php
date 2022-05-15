<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

Route::get('/setWebhook', function () {
    $http = Http::get(
        env('BASE_BOT_URL')
        . env('TG_BOT_TOKEN')
        . '/setWebHook?url='
        . env('NGROK_URL')
        . '/api/webhook?allowed_updates=["message"]'
    );
    dd(json_decode($http));
});

Route::get('/deleteWebhook', function () {
    $http = Http::get(
        env('BASE_BOT_URL')
        . env('TG_BOT_TOKEN')
        . '/deleteWebHook?drop_pending_updates=true');
    dd(json_decode($http));
});


