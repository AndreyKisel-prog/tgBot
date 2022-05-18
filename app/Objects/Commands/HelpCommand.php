<?php

namespace App\Objects\Commands;

use App\Objects\MessageText\MessageTextToSend;
use Illuminate\Support\Facades\Log;


class HelpCommand extends BaseCommand
{
    public function handle()
    {
        Log::info('Help Command');
        $message = MessageTextToSend::MESSAGE_TEXT_TYPES['helpAnswer'];
        // отправляем сообщение с реакцией на просьбу о помощи
        $this->telegram()->sendMessage($this->data['chat_id'], $message);
    }
}
