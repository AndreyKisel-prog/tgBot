<?php

namespace App\Services\Telegram;

use App\Models\User;
use App\Assets\MessageTextToSend;
use App\Services\Tomtom\TomtomService;

// Сервис создан пока избыточно, бот пока не общается, только принимает
// запрос на ключевые слова для поиска
class MessageService extends BaseService
{
    use ProcessTrait;

    /**
     * @return \Illuminate\Http\Client\Response|int
     */
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
     * @return \Illuminate\Http\Client\Response
     */
    private function userLocationHandle(User $user): \Illuminate\Http\Client\Response
    {
        $data = $this->data;
        User::updateUserLocation($user, $data);
        // сообщение: спс за локацию
        $message = MessageTextToSend::MESSAGE_TEXT_TYPES['locationSentReply'];
        $this->telegram()->sendMessage($data['chatId'], $message);
        // сообщение к юзеру c просьбой отправить радиус с соотв. кнопками
        return (new CommandService($data))->changeRadiusCommandHandle();
    }

    /**
     * @param User $user
     * @return \Illuminate\Http\Client\Response|int
     */
    private function userSearchQueryHandle(User $user)
    {
        $data = $this->data;
        // если запрос есть, но в бд отсутствуют данные о локации,
        // то запрашиваем локацию
        if (!$user->last_latitude || !$user->last_longitude) {
            $message              = MessageTextToSend::MESSAGE_TEXT_TYPES['locationNeed'];
            $keyboard             = (new KeyBoard());
            $inlineKeyboardMarkup = $keyboard
                ->getReplyKeyboardMarkup($keyboard->getKeyboardWithRequestLocation());
            return $this->telegram()->sendButtons(
                $data['chatId'],
                $message,
                json_encode($inlineKeyboardMarkup)
            );
        }
        // если данные о локации есть в БД, то сохраняем в бд ключеовое слово
        User::setLastSearchWord($user, $data['messageText']);
        // передаем управление в TomTomService
        $messages = (new TomtomService())->handle(
            $user->last_latitude,
            $user->last_longitude,
            $user->last_search_word,
            $user->last_search_radius
        );
        // если из Томтома не возвращена ни одна организация
        if (!count($messages)) {
            $message = MessageTextToSend::MESSAGE_TEXT_TYPES['nothingFound'];
            return $this->telegram()
                ->sendMessage($data['chatId'], $message);
        }
        // если из Томтома возвращен массив хотя бы с одной организацией
        foreach ($messages as $item) {
            $this->telegram()
                ->sendMessage($data['chatId'], $item['message']);
            $this->telegram()
                ->sendLocation(
                    $data['chatId'],
                    $item['latitude'],
                    $item['longitude']
                );
        }
        return 1;
    }
}
