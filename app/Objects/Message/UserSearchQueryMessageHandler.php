<?php

namespace App\Objects\Message;

use App\Objects\KeyBoard\KeyBoard;
use App\Objects\MessageText\MessageTextToSend;
use App\Repositories\Users\UserRepository;
use Illuminate\Support\Facades\Log;
use App\Services\Tomtom\TomtomService;

class UserSearchQueryMessageHandler extends BaseMessageHandler
{
    public function handle()
    {
        Log::info('UserSearchQueryMessageHandler @handle');
        $data = $this->data;
        $repository = new UserRepository();
        $user = $repository->getUser($data['nickName']);

        // если запрос есть, но в бд отсутствуют данные о локации, то запрашиваем локацию
        if (!$user->last_latitude || !$user->last_longitude) {
            $message = MessageTextToSend::MESSAGE_TEXT_TYPES['locationNeed'];
            $keyboard = (new KeyBoard());
            $inlineKeyboardMarkup = $keyboard->getReplyKeyboardMarkup($keyboard->getKeyboardWithRequestLocation());
            return $this->telegram()->sendButtons($data['chat_id'], $message, json_encode($inlineKeyboardMarkup));
        }
        // если данные локации есть в БД, то
        //сохраняем в бд ключеовое слово
        $repository->setLastSearchWord($user, $data['messageText']);
        // передаем управление в TomTomService
        $messages = (new TomtomService())->handle($user->last_latitude, $user->last_longitude, $user->last_search_word, $user->last_search_radius);
        foreach ($messages as $item) {
            $this->telegram()->sendMessage($data['chat_id'], $item['message']);
            $this->telegram()->sendLocation($data['chat_id'], $item['latitude'], $item['longitude']);
        }
        return 1;
    }
}
