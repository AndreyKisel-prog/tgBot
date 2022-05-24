<?php

namespace App\Services\Telegram;

use App\Assets\MessageTextToSend;
use App\Services\Telegram\CommandService;
use App\Services\Telegram\KeyBoard;
use Illuminate\Support\Facades\Cache;

class SearchConditions
{
    use ProcessTrait;

    public $data;
    /** @var \App\Services\Telegram\KeyBoard */
    public $keyboard;

    public function __construct($data)
    {
        $this->data     = $data;
        $this->keyboard = (new KeyBoard());
    }

    public function checkConditionsForSearch($user)
    {
        // проверяем есть ли данные о локации в кэше
        $isLocationCached = Cache::has('last_latitude_' . $user->user_name)
            && Cache::has('last_longitude_' . $user->user_name);
        // проверяем есть ли данные о локации в кэше
        $isRadiusCached = Cache::has('last_radius_' . $user->user_name);
        //  проверяем есть ли данные о поисковом запросе в кэше
        $isSearchWordCached = Cache::has('last_search_word_' . $user->user_name);
        //если все условия соблюдены, то отправляем пользователю сообщение
        // с кнопкой для сверки поиска
        if ($isLocationCached && $isRadiusCached && $isSearchWordCached) {
            $cache      = new CacheManager();
            $latitude   = $cache->getLatitude($user->user_name);
            $longitude  = $cache->getLongitude($user->user_name);
            $searchWord = $cache->getSearchWord($user->user_name);
            $radius     = $cache->getRadius($user->user_name);

            $message = MessageTextToSend::MESSAGE_TEXT_TYPES['settingsHead'] . PHP_EOL
                . MessageTextToSend::MESSAGE_TEXT_TYPES['radiusSet'] . $radius . 'м. ' . PHP_EOL
                . MessageTextToSend::MESSAGE_TEXT_TYPES['searchWordSet'] . ' "' . $searchWord . '". ' . PHP_EOL
                . MessageTextToSend::MESSAGE_TEXT_TYPES['locationSet']
                . 'широта - ' . $latitude . ', долгота - ' . $longitude . '. ' . PHP_EOL
                . MessageTextToSend::MESSAGE_TEXT_TYPES['noteForChangeSettings'];

            $buttonOptions = [
                'Начать поиск' => 'search',
            ];
            $keyboard      = $this->keyboard->getOnelineKeyboardWithCallback($buttonOptions);
            // общая настройка клавиатуры
            $inlineKeyboardMarkup = $this->keyboard->getInlineKeyboardMarkup($keyboard);
            // отправляем сообщение с кнопками
            return $this->telegram()->sendButtons(
                $this->data['chatId'],
                $message,
                json_encode($inlineKeyboardMarkup)
            );
        }
        if (!$isLocationCached) {
            // проверяем, есть ли данные о локации в БД и если нет,
            // то отправляем пользователю запрос на локацию
            if (!$user->last_latitude || !$user->last_longitude) {
                return $this->locationNeedQuery();
            }
            // а если в БД локация есть, но нет в кэше, то сохраняем ее также и в кеш
            (new CacheManager())->setCacheLocation(
                $user->last_latitude,
                $user->last_longitude,
                $user->user_name
            );
        }
        if (!$isRadiusCached) {
            // проверяем, есть ли данные о радиусе в БД и если нет,
            // то отправляем пользователю запрос на радиус
            if (!$user->last_search_radius) {
                return (new CommandService($this->data))->changeRadiusCommandHandle();
            }
            // а если в БД радиус есть, но нет в кэше, то сохраняем его также и в кеш
            (new CacheManager())->setCacheRadius(
                $user->last_search_radius,
                $user->user_name
            );
        }

        if (!$isSearchWordCached) {
            // проверяем, есть ли данные о поисковом запросе в БД и если нет,
            // то отправляем пользователю запрос на поисковое слово
            if (!$user->last_search_word) {
                return $this->searchWordNeedQuery();
            }
            // а если в БД поисковое слово есть, но нет в кэше, то сохраняем его также и в кеш
            (new CacheManager())->setCacheSearchWord(
                $user->user_name,
                $user->last_search_word,
            );
        }
        return true;
    }

    public function locationNeedQuery()
    {
        $message              = MessageTextToSend::MESSAGE_TEXT_TYPES['locationNeed'];
        $keyboard             = (new KeyBoard());
        $inlineKeyboardMarkup = $keyboard
            ->getReplyKeyboardMarkup($keyboard->getKeyboardWithRequestLocation());
        return $this->telegram()->sendButtons(
            $this->data['chatId'],
            $message,
            json_encode($inlineKeyboardMarkup)
        );
    }


    public function radiusNeedQuery(): \Illuminate\Http\Client\Response
    {
        $message = MessageTextToSend::MESSAGE_TEXT_TYPES['radiusNeed'];
        // получаем клавиатуру с кнопками
        $buttonsOptions = [
            '1км' => 1000,
            '3км' => 3000,
            '5км' => 5000,
            '7км' => 7000,
            '9км' => 9000,
        ];
        $keyboard       = $this->keyboard->getOnelineKeyboardWithCallback($buttonsOptions);
        // общая настройка клавиатуры
        $inlineKeyboardMarkup = $this->keyboard->getInlineKeyboardMarkup($keyboard);
        // отправляем сообщение с кнопками
        return $this->telegram()
            ->sendButtons(
                $this->data['chatId'],
                $message,
                json_encode($inlineKeyboardMarkup)
            );
    }

    public function searchWordNeedQuery()
    {
        $message = MessageTextToSend::MESSAGE_TEXT_TYPES['searchWordNeed'];
        return $this->telegram()
            ->sendMessage(
                $this->data['chatId'],
                $message
            );
    }
}
