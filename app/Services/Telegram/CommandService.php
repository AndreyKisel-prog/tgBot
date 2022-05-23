<?php

namespace App\Services\Telegram;

use App\Assets\MessageTextToSend;
use App\Services\Telegram\KeyBoard;

class CommandService extends BaseService
{
    use ProcessTrait;

    /** @var string */
    public $command_message;
    /** @var \App\Services\Telegram\KeyBoard */
    public $keyboard;

    public const START = '/start';
    public const HELP = '/help';
    public const UPDATE_LOCATION = '/update_location';
    public const CHANGE_RADIUS_SEARCH = '/change_radius_search';

    // запускаемые для конкретных команд методы
    public const COMMANDS = [
        self::START                => 'startCommandHandle',
        self::HELP                 => 'helpCommandHandle',
        self::UPDATE_LOCATION      => 'updateLocationCommandHandle',
        self::CHANGE_RADIUS_SEARCH => 'changeRadiusCommandHandle'
    ];

    public function __construct(array $data)
    {
        parent::__construct($data);
        $this->keyboard        = (new KeyBoard());
        $this->command_message = $data['messageText'];
    }

    /**
     * @return mixed
     */
    public function handle()
    {
        $command = self::COMMANDS[$this->command_message];
        return $this->$command();
    }

    /**
     * @return \Illuminate\Http\Client\Response
     */
    public function startCommandHandle(): \Illuminate\Http\Client\Response
    {
        $message = MessageTextToSend::MESSAGE_TEXT_TYPES['greeting']
            . $this->data['userName']
            . MessageTextToSend::MESSAGE_TEXT_TYPES['intro'];
        // получаем обьект настроек отправляемой кнопки
        $inlineKeyboardMarkup = $this->keyboard
            ->getReplyKeyboardMarkup(
                $this->keyboard->getKeyboardWithRequestLocation()
            );
        // отправляем приветственное сообщение с кнопкой отправки геолокации
        return $this->telegram()
            ->sendButtons(
                $this->data['chatId'],
                $message,
                json_encode($inlineKeyboardMarkup)
            );
    }

    /**
     * @return \Illuminate\Http\Client\Response
     */
    private function helpCommandHandle(): \Illuminate\Http\Client\Response
    {
        $message = MessageTextToSend::MESSAGE_TEXT_TYPES['helpAnswer'];
        // отправляем сообщение с реакцией на просьбу о помощи
        return $this->telegram()
            ->sendMessage($this->data['chatId'], $message);
    }

    /**
     * @return \Illuminate\Http\Client\Response
     */
    private function updateLocationCommandHandle(): \Illuminate\Http\Client\Response
    {
        $message = MessageTextToSend::MESSAGE_TEXT_TYPES['locationNeed'];
        // получаем обьект настроек отправляемой кнопки
        $inlineKeyboardMarkup = $this->keyboard
            ->getReplyKeyboardMarkup(
                $this->keyboard
                    ->getKeyboardWithRequestLocation()
            );
        // отправляем сообщение с кнопкой отправки геолокации
        return $this->telegram()->sendButtons(
            $this->data['chatId'],
            $message,
            json_encode($inlineKeyboardMarkup)
        );
    }

    /**
     * @return \Illuminate\Http\Client\Response
     */
    public function changeRadiusCommandHandle(): \Illuminate\Http\Client\Response
    {
        $message = MessageTextToSend::MESSAGE_TEXT_TYPES['radiusNeed'];
        // получаем клавиатуру с кнопками
        $buttonsOptions = [
            '1км' => 1000,
            '3км' => 3000,
            '5км' => 5000,
            '7км' => 7000,
            '9км' => 9000,
        ];
        $keyboard       = $this->keyboard
            ->getOnelineKeyboardWithCallback($buttonsOptions);
        // общая настройка клавиатуры
        $inlineKeyboardMarkup = $this->keyboard
            ->getInlineKeyboardMarkup($keyboard);
        // отправляем сообщение с кнопками
        return $this->telegram()
            ->sendButtons(
                $this->data['chatId'],
                $message,
                json_encode($inlineKeyboardMarkup)
            );
    }
}
