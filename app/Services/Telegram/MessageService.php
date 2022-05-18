<?php

namespace App\Services\Telegram;

use App\Objects\Message\UserSearchQueryMessageHandler;
use App\Objects\Message\UserLocationMessageHandler;
use Illuminate\Support\Facades\Log;

// Сервис создан пока избыточно, бот пока не общается, только принимает запрос на ключевые слова для поиска
class MessageService extends BaseTelegramService
{
    public function handle()
    {
        // если сообщение содержит локацию, то обратываем его как локацию
        // если локации нет, то обрабатываем как простое текстовое сообщение
        Log::info('MessageService @handle');
        return ($this->data['location'])
            ? (new UserLocationMessageHandler($this->data))->handle()
            : (new UserSearchQueryMessageHandler($this->data))->handle();
    }
}
