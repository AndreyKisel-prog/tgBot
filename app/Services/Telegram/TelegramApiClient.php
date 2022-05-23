<?php

namespace App\Services\Telegram;

use Illuminate\Support\Facades\Http;

use function env;

class TelegramApiClient
{
    /** * @var Http */
    protected $http;
    /** @var string */
    protected $botToken;

    /**
     * @param Http $http
     * @param string $botToken
     */
    public function __construct(Http $http, string $botToken)
    {
        $this->http     = $http;
        $this->botToken = $botToken;
    }

    /**
     * @param string $chatId
     * @param string $message
     * @return \Illuminate\Http\Client\Response
     */
    public function sendMessage(string $chatId, string $message): \Illuminate\Http\Client\Response
    {
        return $this->http::post(
            env('BASE_BOT_URL')
            . $this->botToken
            . '/sendMessage',
            [
                'chat_id'    => $chatId,
                'text'       => $message,
                'parse_mode' => 'html',
            ]
        );
    }

    /**
     * @param string $chatId
     * @param string $message
     * @param string $button
     * @return \Illuminate\Http\Client\Response
     */
    public function sendButtons(
        string $chatId,
        string $message,
        string $button
    ): \Illuminate\Http\Client\Response {
        return $this->http::post(
            env("BASE_BOT_URL")
            . $this->botToken
            . '/sendMessage',
            [
                'chat_id'      => $chatId,
                'text'         => $message,
                'parse_mode'   => 'html',
                'reply_markup' => $button
            ]
        );
    }

    /**
     * @param string $chatId
     * @param float $latitude
     * @param float $longitude
     * @return \Illuminate\Http\Client\Response
     */
    public function sendLocation(
        string $chatId,
        float $latitude,
        float $longitude
    ): \Illuminate\Http\Client\Response {
        return $this->http::post(
            env("BASE_BOT_URL")
            . $this->botToken
            . '/sendLocation',
            [
                'chat_id'   => $chatId,
                'latitude'  => $latitude,
                'longitude' => $longitude
            ]
        );
    }
}
