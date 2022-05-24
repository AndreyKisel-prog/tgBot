<?php

namespace App\Assets;

class MessageTextToSend
{
    public const MESSAGE_TEXT_TYPES = [
        'intro'                 => '. Я - бот, который ищет ближайшие организации по ключевым словам и радиусу',
        'greeting'              => 'Привет, ',
        'helpAnswer'            => 'Скорая помощь уже в пути',
        'locationSentReply'     => 'Отлично! Вы отправили геолокацию',
        'locationNeed'          => 'Для поиска мне нужны данные твоего местоположения. Нажми кнопку "Отправить геолокацию", которая расположена ниже',
        'radiusNeed'            => 'Теперь установите радиус поиска',
        'radiusSentReply'       => 'Отлично! Вы установили радиус!',
        'searchWordNeed'        => 'Осталось ввести ключевое слово для поиска. ',
        'nothingFound'          => 'Ничего не найдено. Попробуй увеличить радиус поиска(с помощью команды в меню), либо ввести другое ключевое слово для поиска',

        'settingsHead' => 'Настройки поиска: ',
        'radiusSet'             => 'радиус: ',
        'locationSet'           => 'геолокация: ',
        'searchWordSet'         => 'запрос: ',
        'noteForChangeSettings' => 'Чтобы изменить радиус либо обновить геолокацию, используйте команды в меню. ' .PHP_EOL. 'Чтобы изменить запрос, отправьте его сообщением.' .PHP_EOL. 'Если настройки верны, нажмите кнопку поиска',
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
