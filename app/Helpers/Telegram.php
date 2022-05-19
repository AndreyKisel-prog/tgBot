<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class Telegram
{
    protected $http;
    protected $botToken;

    public function __construct(Http $http, $botToken)
    {
        $this->http = $http;
        $this->botToken = $botToken;
    }

    public function sendMessage($chat_id, $message)
    {
        return $this->http::post(env('BASE_BOT_URL') . $this->botToken . '/sendMessage',
            [
                'chat_id' => $chat_id,
                'text' => $message,
                'parse_mode' => 'html',
            ]
        );
    }

    public function sendButtons($chat_id, $message, $button)
    {
        return $this->http::post(env("BASE_BOT_URL") . $this->botToken . '/sendMessage',
            [
                'chat_id' => $chat_id,
                'text' => $message,
                'parse_mode' => 'html',
                'reply_markup' => $button
            ]
        );
    }

    public function sendLocation($chat_id, $latitude, $longitude){
        return $this->http::post(env("BASE_BOT_URL") . $this->botToken . '/sendLocation',
            [
                'chat_id' => $chat_id,
                'latitude' => $latitude,
                'longitude' => $longitude
            ]
        );
    }
}
