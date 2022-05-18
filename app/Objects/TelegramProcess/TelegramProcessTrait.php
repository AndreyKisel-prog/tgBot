<?php

namespace App\Objects\TelegramProcess;

use App\Helpers\Telegram;
use Illuminate\Support\Facades\Http;


trait TelegramProcessTrait
{

    public function telegram()
    {
        return new Telegram(new Http(), env('TG_BOT_TOKEN'));
    }
}

