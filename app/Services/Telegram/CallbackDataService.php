<?php

namespace App\Services\Telegram;

use App\Models\User;
use App\Assets\MessageTextToSend;

class CallbackDataService extends BaseService
{
    use ProcessTrait;

    /**
     * @return \Illuminate\Http\Client\Response|int
     */
    public function handle()
    {
        //если в исходном мессадже бота с кнопками было слово 'радиус', то
        if (stripos($this->data['callbackText'], 'радиус') !== false) {
            return $this->radiusCallbackDataHandle();
        }
        return 1;
    }

    /**
     * @return \Illuminate\Http\Client\Response
     */
    private function radiusCallbackDataHandle(): \Illuminate\Http\Client\Response
    {
        $user         = User::getUser($this->data['nickName']);
        $callbackData = (int)($this->data['callbackData']);
        User::setLastSearchRadius(
            $user,
            $callbackData
        );
        $message = MessageTextToSend::MESSAGE_TEXT_TYPES['radiusSentReply'];
        return $this->telegram()
            ->sendMessage($this->data['chatId'], $message);
    }
}
