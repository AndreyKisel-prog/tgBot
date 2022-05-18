<?php

namespace App\Objects\Commands;

use App\Objects\KeyBoard\KeyBoard;
use App\Objects\MessageText\MessageTextToSend;

class StartCommand extends BaseCommand
{

    public $keyboard;

    public function __construct($data)
    {
        parent::__construct($data);
        $this->keyboard = (new KeyBoard());
    }

    public function handle()
    {
        $message = MessageTextToSend::MESSAGE_TEXT_TYPES['greeting']
            . $this->data['userName']
            . MessageTextToSend::MESSAGE_TEXT_TYPES['intro'];
        // получаем обьект настроек отправляемой кнопки
        $inlineKeyboardMarkup = $this->keyboard->getInlineKeyboardMarkupSettings($this->keyboard->getKeyboardSettings());
        // отправляем приветственное сообщение с кнопкой отправки геолокации
        $this->telegram()->sendButtons($this->data['chat_id'], $message, json_encode($inlineKeyboardMarkup));
    }
}
