<?php

namespace App\Services\Tomtom;

use App\Assets\MessageTextToSend;
use App\Services\BaseService;
use App\Services\Telegram\ProcessTrait;
use App\Transformers\TomtomTransformer;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class TomtomService extends BaseService
{
    use ProcessTrait;

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @param float $latitude
     * @param float $longitude
     * @param string $message
     * @param int $radius
     * @return bool|\Illuminate\Http\Client\Response
     */
    public function handle(
        float $latitude,
        float $longitude,
        string $message,
        int $radius
    ) {
        //сначала проверяем есть ли в кэше предыдущие результаты
        // запроса в томтом с такими же параметрами
        $key = $latitude . '_' . $longitude . '_' . $message . '_' . $radius;

        if (!Cache::has($key)) {
            $tomtom   = new TomtomApiClient(new Http(), env('TOMTOM_API_KEY'));
            $responce = $tomtom->fetchPOI(
                $latitude,
                $longitude,
                $message,
                $radius
            );
            // обработка ответа из томтома, возвращается массив с данными
            $messages = (new TomtomTransformer($responce))->handle();
            // сразу кэшируем
            Cache::put($key, $messages);
        } else {
            $messages = Cache::get($key);
            $this->telegram()
                ->sendMessage($this->data['chatId'], 'Внимание! Сообщение взято из кэша!');
        }
        //отправляем кучу соообщений в телеграм, итоговый результат для юзера
        return $this->tomtomResponseToUserSend($messages);
    }

    private function tomtomResponseToUserSend($messages)
    {
        // если из Томтома не возвращена ни одна организация
        if (!count($messages)) {
            $message = MessageTextToSend::MESSAGE_TEXT_TYPES['nothingFound'];
            return $this->telegram()
                ->sendMessage($this->data['chatId'], $message);
        }
        // если из Томтома возвращен массив хотя бы с одной организацией
        foreach ($messages as $item) {
            $this->telegram()
                ->sendMessage($this->data['chatId'], $item['message']);
            $this->telegram()
                ->sendLocation(
                    $this->data['chatId'],
                    $item['latitude'],
                    $item['longitude']
                );
        }
        return true;
    }
}


