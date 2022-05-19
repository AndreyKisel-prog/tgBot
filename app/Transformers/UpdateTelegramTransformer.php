<?php

namespace App\Transformers;

use Illuminate\Support\Facades\Log;

class UpdateTelegramTransformer extends BaseTransformer
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function handle(): array
    {
        // если апдейт мессадж или команда для бота
        $messageObj = $this->data["message"] ?? null;

        $messageEntitiesType = $messageObj["entities"][0]['type'] ?? null;
        $isBotCommand = $messageEntitiesType === "bot_command";

        //если апдейт - это callback_query, то есть нажата кнопка inlineKeyboard и возращен вложенный сallback_data
        $callbackQueryObj = $this->data["callback_query"] ?? null;
        $callbackData = $callbackQueryObj['data'] ?? null;
        $callbackQueryTextFromReply = $callbackQueryObj['message']['text'] ?? null;

        $userName = $messageObj["chat"]["first_name"]
            ?? $callbackQueryObj["from"]["first_name"]
            ?? null;
        $nickName = $messageObj["chat"]["username"]
            ?? $callbackQueryObj["from"]["username"]
            ?? null;

        $chat_id = $messageObj["chat"]["id"]
            ?? $callbackQueryObj["message"]["chat"]["id"]
            ?? null;
        $isMessageSentByBot = $messageObj["from"]["is_bot"] ?? null;

        $messageText = $messageObj["text"] ?? null;
        $location = $messageObj["location"] ?? null;
        $latitude = $location["latitude"] ?? null;
        $longitude = $location["longitude"] ?? null;

        return [
            'messageObj' => $messageObj,
            'messageEntitiesType' => $messageEntitiesType,
            'isBotCommand' => $isBotCommand,

            'userName' => $userName,
            'nickName' => $nickName,
            'chat_id' => $chat_id,
            'isMessageSentByBot' => $isMessageSentByBot,
            'location' => $location,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'messageText' => $messageText,

            'callbackQueryObj' => $callbackQueryObj,
            'callbackData' => $callbackData,
            'callbackQueryTextFromReply' => $callbackQueryTextFromReply,
        ];
    }
}
