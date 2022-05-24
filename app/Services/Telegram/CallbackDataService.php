<?php

namespace App\Services\Telegram;

use App\Models\User;
use App\Assets\MessageTextToSend;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Cache;
use App\Services\Telegram\SearchConditions;
use App\Services\Tomtom\TomtomService;

class CallbackDataService extends BaseService
{
    use ProcessTrait;

    /**
     * @return Response|void
     */
    public function handle()
    {
        if (stripos($this->data['callbackData'], 'search') !== false) {
            return $this->startForSearchHandle();
        }
        //если в исходном мессадже бота с кнопками было слово 'радиус', то
        if (stripos($this->data['callbackText'], 'радиус') !== false) {
            return $this->radiusHandle();
        }
    }

    /**
     * @return Response
     */
    private function radiusHandle(): \Illuminate\Http\Client\Response
    {
        $user         = User::getUser($this->data['nickName']);
        $callbackData = (int)($this->data['callbackData']);
        // сохраняем радиус в базу данных
        User::setLastSearchRadius($user, $callbackData);
        // сохраняем радиус в кэш
        $keyRadius = 'last_radius_' . $user->user_name;
        Cache::put($keyRadius, $this->data['callbackData'], 1440);
        //отправляем сообщение: спс за радиус
        $message = MessageTextToSend::MESSAGE_TEXT_TYPES['radiusSentReply'];
        $this->telegram()
            ->sendMessage($this->data['chatId'], $message);
        return (new SearchConditions($this->data))->checkConditionsForSearch($user);
    }

    private function startForSearchHandle()
    {
        $user       = User::getUser($this->data['nickName']);
        $cache      = new CacheManager();
        $latitude   = $cache->getLatitude($user->user_name);
        $longitude  = $cache->getLongitude($user->user_name);
        $searchWord = $cache->getSearchWord($user->user_name);
        $radius     = $cache->getRadius($user->user_name);

        return (new TomtomService($this->data))->handle(
            $latitude,
            (float)$longitude,
            $searchWord,
            (int)$radius
        );
    }

}
