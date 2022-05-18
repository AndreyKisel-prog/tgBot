<?php

namespace App\Services\Telegram;
use App\Transformers\UpdateTelegramTransformer;
use Illuminate\Support\Facades\Log;

class WebhookNewRequestService
{
    public $rawUpdate;
    public $data;
    const COMMAND = 'command';
    const MESSAGE = 'message';
    const WEBHOOK_UPDATE_TYPES = [
        self::COMMAND => CommandService::class,
        self::MESSAGE => MessageService::class,
    ];

    public function __construct($rawUpdate)
    {
        $this->rawUpdate = $rawUpdate;
        $this->data = (new UpdateTelegramTransformer($this->rawUpdate->input()))->handle();
    }

    public function handle()
    {
        $typeUpdate = $this->recognizeTypeUpdate($this->data);
        $this->handleUpdateByType($typeUpdate);
    }

    // выясняем: от юзера пришло сообщение или команда либо иной вариант
    public function recognizeTypeUpdate($data)
    {
        if ($data['isBotCommand']) {
            return 'command';
        } else if ($data['isMessage']) {
            return 'message';
        }
        return 'undefined';
    }

    // вызываем класс обработчик в зависимости от типа апдейта (месадж, команды)
    public function handleUpdateByType($typeUpdate)
    {
        Log::info('webhookupdate @handleUpdateByType');
        $webhook_update_type = self::WEBHOOK_UPDATE_TYPES[$typeUpdate];
        return (new $webhook_update_type($this->data))->handle();
    }
}
