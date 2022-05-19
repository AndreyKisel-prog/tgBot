<?php

namespace App\Objects\TelegramCallbackDataHandlers;

use App\Objects\TelegramProcess\TelegramProcessTrait;

abstract class BaseCallbackDataHandler
{
    public $data;
    use TelegramProcessTrait;

    public function __construct($data)
    {
        $this->data = $data;
    }

    abstract public function handle();
}
