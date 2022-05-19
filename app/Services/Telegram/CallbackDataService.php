<?php

namespace App\Services\Telegram;

use App\Objects\TelegramCallbackDataHandlers\RadiusCallbackDataHandler;
use Illuminate\Support\Facades\Log;

class CallbackDataService extends BaseTelegramService
{

    public function handle()
    {
        Log::info($this->data['callbackQueryTextFromReply']);
        Log::info(stripos($this->data['callbackQueryTextFromReply'], 'радиус'));

        //если в исходном мессадже с кнопками было слово радиус, то
        if (stripos($this->data['callbackQueryTextFromReply'], 'радиус') !== false){
            return (new RadiusCallbackDataHandler($this->data))->handle();
        }
        return 1;
    }
}
