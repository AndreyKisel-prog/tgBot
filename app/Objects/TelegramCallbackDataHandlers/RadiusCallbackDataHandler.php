<?php

namespace App\Objects\TelegramCallbackDataHandlers;

use App\Objects\MessageText\MessageTextToSend;
use App\Repositories\Users\UserRepository;
use Illuminate\Support\Facades\Log;

class RadiusCallbackDataHandler extends BaseCallbackDataHandler
{
    public function handle()
    {
        Log::info(json_encode('RadiusCallbackDataHandler'));

        $repository = new UserRepository();
        $user = $repository->getUser($this->data['nickName']);

        $repository->setLastSearchRadius($user, (int)($this->data['callbackData']));

        $message = MessageTextToSend::MESSAGE_TEXT_TYPES['radiusSentReply'];
        return $this->telegram()->sendMessage($this->data['chat_id'], $message);
    }
}
