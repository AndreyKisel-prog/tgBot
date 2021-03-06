<?php

namespace App\Services\Telegram;

use App\Http\Requests\TelegramUpdateRequest;
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
     * @param TelegramUpdateRequest $request
     * @return void
     */
   public function __construct(TelegramUpdateRequest $request)
    {
        $this->data = (new UpdateTelegramTransformer($request->input()))->handle();
    }

    /**
     * @return void
     */
    public function handle()
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
        return true;
    }

    /**
     * @param string $typeUpdate
     *
     * вызываем класс обработчик в зависимости от типа апдейта (месадж, команды)
     */
    private function handleUpdateByType(string $typeUpdate)
    {
        $webhook_update_type = self::WEBHOOK_UPDATE_TYPES[$typeUpdate];
        (new $webhook_update_type($this->data))->handle();
    }
}
