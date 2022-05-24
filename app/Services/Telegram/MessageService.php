<?php

namespace App\Services\Telegram;

use App\Models\User;
use App\Assets\MessageTextToSend;
use App\Services\Telegram\CacheManager;
use Illuminate\Http\Client\Response;

class MessageService extends BaseService
{
    use ProcessTrait;

    public function handle()
    {
        $user = User::getUser($this->data['nickName']);
        // сообщение содержит локацию ?  обратываем его  локацию
        // либо как простое текстовое сообщение
        return ($this->data['location'])
            ? $this->userLocationHandle($user)
            : $this->userSearchQueryHandle($user);
    }

    /**
     * @param User $user
     * @return Response
     * обрабатываем пришедшие от юзера данные о геолокации
     */
    private function userLocationHandle(User $user): Response
    {
        // сохраняем в БД
        User::updateUserLocation($user, $this->data);
        // сохраняем в кэш редис
        (new CacheManager())->setCacheLocation(
            $this->data['latitude'],
            $this->data['longitude'],
            $user->user_name
        );
        // сообщение пользователю: спс за локацию
        $message = MessageTextToSend::MESSAGE_TEXT_TYPES['locationSentReply'];
        $this->telegram()->sendMessage($this->data['chatId'], $message);
        return (new SearchConditions($this->data))->checkConditionsForSearch($user);
    }

    /**
     * @param User $user
     * обработка поискового запроса от юзера
     */
    private function userSearchQueryHandle(User $user)
    {
        // сохраняем поисковой запрос в бд
        User::setLastSearchWord($user, $this->data['messageText']);
        // сохраняем поисковой запрос в кэш
        (new CacheManager())->setCacheSearchWord($user->user_name, $this->data['messageText']);
        return (new SearchConditions($this->data))->checkConditionsForSearch($user);
    }
}
