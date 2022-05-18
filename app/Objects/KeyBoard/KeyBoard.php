<?php

namespace App\Objects\KeyBoard;

class KeyBoard
{
    public function getKeyboardSettings()
    {
        return [[[
            'text' => 'Отправить геолокацию',
            "request_location" => true,
        ],],];
    }

    public function getInlineKeyboardMarkupSettings($keyboardSettings)
    {
        return
            [
                'keyboard' => $keyboardSettings,
                "resize_keyboard" => true,
                "one_time_keyboard" => true
            ];
    }
}
