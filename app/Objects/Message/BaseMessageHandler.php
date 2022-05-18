<?php

namespace App\Objects\Message;

use App\Objects\TelegramProcess\TelegramProcessTrait;

class BaseMessageHandler implements BaseMessageHandlerInterface
{
    public $data;
    use TelegramProcessTrait;
    public function __construct($data)
    {
        $this->data = $data;
    }
    public function handle(){
    }
}
