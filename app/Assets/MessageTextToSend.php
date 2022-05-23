<?php

namespace App\Assets;

class MessageTextToSend
{
    public const MESSAGE_TEXT_TYPES = [
        'intro'             => '. Я - бот, который ищет ближайшие организации. Для поиска мне нужны данные твоего местоположения. Нажми кнопку "Отправить геолокацию", которая расположена ниже',
        'greeting'          => 'Привет, ',
        'helpAnswer'        => 'Скорая помощь уже в пути',
        'locationSentReply' => 'Отлично! Вы отправили геолокацию',
        'locationNeed'      => 'Для поиска мне нужны данные твоего местоположения. Нажми кнопку "Отправить геолокацию", которая расположена ниже',
        'radiusNeed'        => 'Теперь установите радиус поиска',
        'radiusSentReply'   => 'Отлично! Вы установили радиус, теперь введите ключевое слово для поиска',
        'nothingFound'      => 'ничего не найдено. Попробуй увеличить радиус поиска(с помощью команды в меню), либо ввести другое ключевое слово для поиска'
    ];

    /**
     * @param string $typeTextMessage
     * @return string
     */
    public function getMessageTextToSend(string $typeTextMessage): string
    {
        return self::MESSAGE_TEXT_TYPES[$typeTextMessage];
    }
}
