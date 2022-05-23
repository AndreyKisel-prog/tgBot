<?php

namespace App\Services\Telegram;

use App\Services\Telegram\TelegramApiClient;
use Illuminate\Support\Facades\Http;


trait ProcessTrait
{

    public function telegram(): TelegramApiClient
    {
        return new TelegramApiClient(new Http(), env('TG_BOT_TOKEN'));
    }
}

