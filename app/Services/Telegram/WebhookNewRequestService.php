<?php

namespace App\Services\Telegram;

use App\Transformers\UpdateTelegramTransformer;


class WebhookNewRequestService
{
    public $data;
    public const COMMAND = 'COMMAND';
    public const MESSAGE = 'MESSAGE';
    public const CALLBACK_DATA = 'CALLBACK_DATA';

    public const WEBHOOK_UPDATE_TYPES = [
        self::COMMAND       => CommandService::class,
        self::MESSAGE       => MessageService::class,
        self::CALLBACK_DATA => CallbackDataService::class,
    ];

    /**
     * @param $rawUpdate
     */
    public function __construct($rawUpdate)
    {
        $this->data = (new UpdateTelegramTransformer($rawUpdate->input()))->handle();
    }

    /**
     * @return void
     */
    public function handle(): void
    {
        $typeUpdate = $this->recognizeTypeUpdate($this->data);
        $this->handleUpdateByType($typeUpdate);
    }

    /**
     * выясняем: от юзера пришло сообщение или команда либо иной вариант
     * @param $data
     * @return string
     */
    private function recognizeTypeUpdate($data): string
    {
        if ($data['isBotCommand']) {
            return self::COMMAND;
        }
        if ($data['messageObj']) {
            return self::MESSAGE;
        }
        if ($data['callbackQueryObj']) {
            return self::CALLBACK_DATA;
        }
        // TODO: сделать обработку на 'undefined'
        return 'undefined';
    }

    /**
     * @param string $typeUpdate
     * @return mixed
     * // вызываем класс обработчик в зависимости от типа апдейта (месадж, команды)
     */
    private function handleUpdateByType(string $typeUpdate)
    {
        $webhook_update_type = self::WEBHOOK_UPDATE_TYPES[$typeUpdate];
        return (new $webhook_update_type($this->data))->handle();
    }
}
