<?php

namespace App\Services\Telegram;

abstract class BaseTelegramService
{
    public $data;
    public function __construct($data){
        $this->data = $data;
    }
    abstract function handle();
}
