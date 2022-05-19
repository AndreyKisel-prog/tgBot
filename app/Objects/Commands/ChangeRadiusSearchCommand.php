<?php

namespace App\Objects\Commands;

use App\Objects\KeyBoard\KeyBoard;
use App\Objects\MessageText\MessageTextToSend;
use Illuminate\Support\Facades\Log;

class ChangeRadiusSearchCommand extends BaseCommand
{
    public $keyboard;

    public function __construct($data)
    {
        parent::__construct($data);
        $this->keyboard = (new KeyBoard());
    }

    public function handle()
    {
        $message = MessageTextToSend::MESSAGE_TEXT_TYPES['radiusNeed'];
        // получаем клавиатуру с кнопками
        $buttonsOptions = ['5км' => 5, '7км' => 7, '9км' => 9];
        $keyboard = $this->keyboard->getOnelineKeyboardWithCallback($buttonsOptions);
        // общая настройка клавиатуры
        $inlineKeyboardMarkup = $this->keyboard->getInlineKeyboardMarkup($keyboard);
        // отправляем сообщение с кнопками
        $this->telegram()->sendButtons($this->data['chat_id'], $message, json_encode($inlineKeyboardMarkup));
    }
}
