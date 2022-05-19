<?php

namespace App\Objects\KeyBoard;

class KeyBoard
{

    public function getKeyboard($buttonWithOptions)
    {
        $buttons = [$buttonWithOptions];
        return [$buttons];
    }

    public function getKeyboardWithRequestLocation()
    {
        $buttonWithOptions = [
            'text' => 'Отправить геолокацию',
            "request_location" => true,
        ];
        return $this->getKeyboard($buttonWithOptions);
    }

    // получаем клавиатуру где все кнопки в одну линую
    //принимает массив, где ключ - текст, значение - возвращаемое при клике значение callback_data
    public function getOnelineKeyboardWithCallback($buttonsOptions)
    {
        // строка с кнопками
        $LineWithButtons = [];
        foreach ($buttonsOptions as $text => $callbackData) {
            $LineWithButtons[] = [
                'text' => $text,
                'callback_data' => $callbackData,
            ];
        }
        // вся клавиатура целиком
        return [
            $LineWithButtons
        ];
    }

    public function getReplyKeyboardMarkup($keyboard)
    {
        return
            [
                'keyboard' => $keyboard,
                "resize_keyboard" => true,
                "one_time_keyboard" => true,
            ];
    }

    public function getInlineKeyboardMarkup($keyboard)
    {
        return ['inline_keyboard' => $keyboard];
    }

}
