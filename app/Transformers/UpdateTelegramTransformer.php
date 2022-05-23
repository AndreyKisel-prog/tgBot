<?php

namespace App\Transformers;

class UpdateTelegramTransformer extends BaseTransformer
{
    /** @var array */
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @return array
     */
    public function handle(): array
    {
        // если апдейт мессадж или команда для бота
        $messageObj = $this->data["message"] ?? null;

        $messageType  = $messageObj["entities"][0]['type'] ?? null;
        $isBotCommand = $messageType === "bot_command";

        //если апдейт - это callback_query, то есть нажата кнопка
        // inlineKeyboard и возращен вложенный сallback_data
        $callbackQueryObj = $this->data["callback_query"] ?? null;
        $callbackData     = $callbackQueryObj['data'] ?? null;
        $callbackText     = $callbackQueryObj['message']['text'] ?? null;

        $userName = $messageObj["chat"]["first_name"]
            ?? $callbackQueryObj["from"]["first_name"]
            ?? null;
        $nickName = $messageObj["chat"]["username"]
            ?? $callbackQueryObj["from"]["username"]
            ?? null;

        $chatId             = $messageObj["chat"]["id"]
            ?? $callbackQueryObj["message"]["chat"]["id"]
            ?? null;
        $isMessageSentByBot = $messageObj["from"]["is_bot"] ?? null;

        $messageText = $messageObj["text"] ?? null;
        $location    = $messageObj["location"] ?? null;
        $latitude    = $location["latitude"] ?? null;
        $longitude   = $location["longitude"] ?? null;

        return [
            'messageObj'   => $messageObj,
            'messageType'  => $messageType,
            'isBotCommand' => $isBotCommand,

            'userName'           => $userName,
            'nickName'           => $nickName,
            'chatId'             => $chatId,
            'isMessageSentByBot' => $isMessageSentByBot,
            'location'           => $location,
            'latitude'           => $latitude,
            'longitude'          => $longitude,
            'messageText'        => $messageText,

            'callbackQueryObj' => $callbackQueryObj,
            'callbackData'     => $callbackData,
            'callbackText'     => $callbackText,
        ];
    }
}
