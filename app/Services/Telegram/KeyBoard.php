<?php

namespace App\Services\Telegram;

class KeyBoard
{

    /**
     * @param array $buttonWithOptions
     * @return array[]
     */
    public function getKeyboard(array $buttonWithOptions): array
    {
        $buttons = [$buttonWithOptions];
        return [$buttons];
    }

    /**
     * @return array[]
     */
    public function getKeyboardWithRequestLocation(): array
    {
        $buttonWithOptions = [
            'text'             => 'Отправить геолокацию',
            "request_location" => true,
        ];
        return $this->getKeyboard($buttonWithOptions);
    }

    /**
     * получаем клавиатуру где все кнопки в одну линую принимает массив,
     * где ключ - текст, значение - возвращаемое при клике значение callback_data
     * @param array $buttonsOptions
     * @return array[]
     */
    public function getOnelineKeyboardWithCallback($buttonsOptions): array
    {
        // строка с кнопками
        $LineWithButtons = [];
        foreach ($buttonsOptions as $text => $callbackData) {
            $LineWithButtons[] = [
                'text'          => $text,
                'callback_data' => $callbackData,
            ];
        }
        // вся клавиатура целиком
        return [
            $LineWithButtons
        ];
    }

    /**
     * @param array $keyboard
     * @return array
     */
    public function getReplyKeyboardMarkup(array $keyboard): array
    {
        return
            [
                'keyboard'          => $keyboard,
                "resize_keyboard"   => true,
                "one_time_keyboard" => true,
            ];
    }

    /**
     * @param array $keyboard
     * @return array
     */
    public function getInlineKeyboardMarkup(array $keyboard): array
    {
        return ['inline_keyboard' => $keyboard];
    }

}
