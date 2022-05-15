<?php

use App\Helpers\Telegram;
use App\Helpers\Tomtom;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/webhook', function (Request $request, Telegram $telegram, Tomtom $tomtom) {

    Log::info($request->input());
    if (isset($request["message"])) {

        $messageType = $request["message"]["entities"][0]['type'] ?? null;
        $isBotCommand = $messageType === "bot_command";
        $textBotCommand = $request["message"]["text"] ?? null;
        $userName = $request["message"]["chat"]["first_name"] ?? null;
        $nickName = $request["message"]["chat"]["username"] ?? null;
        $chat_id = $request["message"]["chat"]["id"] ?? null;
        $isMessageSentByBot = $request["message"]["from"]["is_bot"] ?? null;
        $location = $request["message"]["location"] ?? null;
        $latitude = $location["latitude"] ?? null;
        $longitude = $location["longitude"] ?? null;
        $mesageText = $request["message"]["text"] ?? null;
        // сохряняем пользователя в бд или поднимаем его запись
        if (isset($nickName)) {
            $user = User::firstOrCreate(['user_name' => $nickName]);
        }


        //     если от пользователя пришла команда старт
        if ($isBotCommand && ($textBotCommand === '/start') && !$isMessageSentByBot) {
            $greeting = 'Привет, ' . $userName . '!';
            $introduction = ' Я - бот, который ищет ближайшие организации. Для поиска мне нужны данные твоего местоположения. Нажми кнопку "Отправить геолокацию", которая расположена ниже';
            $message = $greeting . $introduction;
            // то отправляем приветственное сообщение с кнопкой отправки геолокации
            $keyboardSendLocation = [[[
                'text' => 'Отправить геолокацию',
                "request_location" => true,
            ],],];
            $inlineKeyboardMarkup = [
                'keyboard' => $keyboardSendLocation,
                "resize_keyboard" => true,
                "one_time_keyboard" => true
            ];
            $telegram->sendButtons($chat_id, $message, json_encode($inlineKeyboardMarkup));
        }

//     если  в сообщении пользоваля содержится геолокация,
        if (isset($user) && $location && !$isMessageSentByBot) {
//            то сохраним ее в БД
            $user->update([
                'last_latitude' => $latitude,
                'last_longitude' => $longitude
            ]);
// , и отправляем месседж с предложением отправить ключевое слово для поиска
            $message = "Отлично! Вы отправили геолокацию, теперь введите ключевое слово для поиска";
            $telegram->sendMessage($chat_id, $message);
        }

//        если в сообщении пользователя есть текст, но нет геолокации, то будем считать это за поисковой запрос
        if (isset($user) && !$location && !$isMessageSentByBot && !$isBotCommand && $mesageText) {
            //сначала проверим, есть ли в БД информация о геолокации
            // если нет
            if ($user->last_latitude === null
                && $user->last_longitude === null
            ) {
                $message = 'Для поиска мне нужны данные твоего местоположения. Нажми кнопку "Отправить геолокацию", которая расположена ниже';
                // то отправляем сообщение с кнопкой запроса геолокации
                $keyboardSendLocation = [[[
                    'text' => 'Отправить геолокацию',
                    "request_location" => true,
                ],],];
                $inlineKeyboardMarkup = [
                    'keyboard' => $keyboardSendLocation,
                    "resize_keyboard" => true,
                    "one_time_keyboard" => true
                ];
                $telegram->sendButtons($chat_id, $message, json_encode($inlineKeyboardMarkup));
            } else {
                // если геолокация получена ранее
                //достаем из бд локацию
                $latitude = $user->last_latitude;
                $longitude = $user->last_longitude;
                //сохраняем в бд ключеовое слово
                $user->update(['last_search_word' => $mesageText]);
                // запрос в томтом по ключевому слову
                $responseTomTom = $tomtom->fetchPOI($latitude, $longitude, $mesageText);

                // вылавливаем данные из ответа томтома
                //выданное томтомом количество организаций
                $numResults = $responseTomTom["summary"]["query"] ?? null;
                $results = $responseTomTom["results"] ?? null;

                if ($numResults) {
                    foreach ($results as $index => $item) {
                        $firmDescription = [];
                        $poi = $item["poi"] ?? null;
                        if (isset($poi)) {
                            $name = $poi["name"] ?? null;
                            $phone = $poi["phone"] ?? null;
                            $url = $poi["url"] ?? null;
                        }
                        $score = isset($item["score"]) ? round($item["score"], 2) : null;
                        $address = $item["address"]["freeformAddress"] ?? null;
                        $dist = $item["dist"] ? round($item["dist"]) : null;
                        $latitude = $item["position"]["lat"] ?? null;
                        $longitude = $item["position"]["lon"] ?? null;
                        // формируем массив строк для сообщения пользователю телеграм


                        $firmDescription[] = "<b>Место № </b><i>" . $index . "</i>" . PHP_EOL;
                        if (isset($name)) $firmDescription[] = "<b>Название</b>: <i>" . $name . "</i>" . PHP_EOL;
                        if (isset($score)) $firmDescription[] = "<b>Оценка заведения</b>: <i>" . $score . "</i>" . PHP_EOL;
                        if (isset($dist)) $firmDescription[] = "<b>Расстояние до</b>: <i>" . $dist . "м</i>" . PHP_EOL;
                        if (isset($phone)) $firmDescription[] = "<b>Тел</b>: <i>" . $phone . "</i>" . PHP_EOL;
                        if (isset($address)) $firmDescription[] = "<b>Адрес</b>: <i>" . $address . "</i>" . PHP_EOL;
                        if (isset($url)) $firmDescription[] = "<b>Сайт</b>: <i>" . $url . "</i>" . PHP_EOL;
                        $firmDescription[] = " " . PHP_EOL;
                        $firmDescription = implode(" ", $firmDescription);

                        $telegram->sendMessage($chat_id, $firmDescription);
                        $telegram->sendLocation($chat_id, $latitude, $longitude);
                    }
                }
            }
        }
    }
    return response()->json([
        'result' => true
    ], 200);
});
