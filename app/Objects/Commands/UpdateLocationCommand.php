<?php

namespace App\Objects\Commands;

use App\Objects\KeyBoard\KeyBoard;
use App\Objects\MessageText\MessageTextToSend;
use Illuminate\Support\Facades\Log;

class UpdateLocationCommand extends BaseCommand
{

    public $keyboard;

    public function __construct($data)
    {
        parent::__construct($data);
        $this->keyboard = (new KeyBoard());
    }

    public function handle()
    {
        $message = MessageTextToSend::MESSAGE_TEXT_TYPES['locationNeed'];
        // получаем обьект настроек отправляемой кнопки
        $inlineKeyboardMarkup = $this->keyboard->getReplyKeyboardMarkup($this->keyboard->getKeyboardWithRequestLocation());
        // отправляем сообщение с кнопкой отправки геолокации
        $this->telegram()->sendButtons($this->data['chat_id'], $message, json_encode($inlineKeyboardMarkup));
    }
}
