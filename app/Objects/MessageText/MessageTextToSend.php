<?php

namespace App\Objects\MessageText;

class MessageTextToSend
{
    public const MESSAGE_TEXT_TYPES = [
        'intro' => '. Я - бот, который ищет ближайшие организации. Для поиска мне нужны данные твоего местоположения. Нажми кнопку "Отправить геолокацию", которая расположена ниже',
        'greeting' => 'Привет, ',
        'helpAnswer' => 'Скорая помощь уже в пути',
        'locationSentReply' => 'Отлично! Вы отправили геолокацию',
        'locationNeed' => 'Для поиска мне нужны данные твоего местоположения. Нажми кнопку "Отправить геолокацию", которая расположена ниже',
        'radiusNeed' => 'Теперь установите радиус поиска',
        'radiusSentReply' => 'Отлично! Вы установили радиус, теперь введите ключевое слово для поиска',
    ];

    public function getMessageTextToSend($typeTextMessage)
    {
        return self::MESSAGE_TEXT_TYPES[$typeTextMessage];
    }
}
