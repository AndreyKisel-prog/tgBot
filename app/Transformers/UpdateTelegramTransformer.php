<?php

namespace App\Transformers;

class UpdateTelegramTransformer extends BaseTransformer
{
    public $data;
    public function __construct($data)
    {
        $this->data = $data;
    }

    public function handle():array
    {
        $isMessage = $this->data["message"] ?? null;
        $messageEntitiesType = $this->data["message"]["entities"][0]['type'] ?? null;
        $isBotCommand = $messageEntitiesType === "bot_command";
        $textBotCommand = $this->data["message"]["text"] ?? null;
        $userName = $this->data["message"]["chat"]["first_name"] ?? null;
        $nickName = $this->data["message"]["chat"]["username"] ?? null;
        $chat_id = $this->data["message"]["chat"]["id"] ?? null;
        $isMessageSentByBot = $this->data["message"]["from"]["is_bot"] ?? null;
        $location = $this->data["message"]["location"] ?? null;
        $latitude = $location["latitude"] ?? null;
        $longitude = $location["longitude"] ?? null;
        $messageText = $this->data["message"]["text"] ?? null;

        return [
            'isMessage' => $isMessage,
            'messageEntitiesType' => $messageEntitiesType,
            'isBotCommand' => $isBotCommand,
            'textBotCommand' => $textBotCommand,
            'userName' => $userName,
            'nickName' => $nickName,
            'chat_id' => $chat_id,
            'isMessageSentByBot' => $isMessageSentByBot,
            'location' => $location,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'messageText' => $messageText,
        ];
    }
}
